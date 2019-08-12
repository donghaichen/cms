<?php


namespace app\controller\admin;


use app\BaseController;
use app\model\Category as CategoryModel;
use app\Request;
use think\App;
use think\facade\Db;

class Content extends BaseController
{

    protected $prefix = '';
    protected $parent = [];

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->prefix = config('database.connections.mysql.prefix');
    }

    protected function table($table)
    {
//        return 'cms_content_page';
        return $this->prefix . 'content_' . $table;
    }

    public function pageList()
    {
        return success($this->getPageNolevel());
    }

    public function pageView(Request $request)
    {
        $content_type = 'page';
        $id = $request->param()['id'];
        $rs = Db::table($this->table($content_type))->where('id', $id)->find();
        return success($rs);
    }

    public function pageSave(Request $request)
    {
        $content_type = 'page';
        $request = $request->param();
        foreach ($request as $k => $v)
        {
            if ($v == 'null')
            {
                $request[$k] = '';
            }
        }

        if (isset($request['id']) && $request['id'] > 0) {
            $id = $request['id'];
            unset($request['id']);
            $rs = Db::table($this->table($content_type))->where('id', $id)->save($request);
        }else{
            unset($request['id']);
            $rs = Db::table($this->table($content_type))->save($request);
        }
        userLog(session('user_id'), '编辑页面：' .$request['title'] , $rs, $this->table($content_type));
        if ($rs)
        {
            return success();
        }else{
            return error('操作失败');
        }
    }

    public function deletePage(Request $request)
    {
        $id = $request->param()['id'];
        $type = 'page';
        $model = Db::table($this->table($type));
        if ($model->where('parent_id', $id)->count() > 0)
        {
            return error('该页面下有子页面，请先删除子页面');
        }
        //todo $model where 调教追加
        $rs = Db::table($this->table($type))->where('id',$id)->delete();
        userLog(session('user_id'), '删除页面：' , $rs, $this->table($type));
        if ($rs)
        {
            return success();
        }else{
            return error('删除失败');
        }
    }

    /**
     * +----------------------------------------------------------
     * 获取无层次单页面列表
     * +----------------------------------------------------------
     * $parent_id 调用该ID下的所有单页面，为空时则调用所有
     * $level 无限极分类层次
     * $current_id 当前页面栏目ID
     * $mark 无限极分类标记
     * +----------------------------------------------------------
     */
    protected function getPageNolevel($parent_id = 0, $level = 0, $current_id = '', &$page_nolevel = array(), $mark = '----')
    {
        $data = Db::table($this->table('page'))->order(['id'])->select()->toArray();
        foreach ((array) $data as $value) {
            if ($value['parent_id'] == $parent_id && $value['id'] != $current_id) {
                $value['mark'] = str_repeat($mark, $level);
                $value['level'] = $level;
                $page_nolevel[] = $value;
                $this->getPageNolevel($value['id'], $level + 1, $current_id, $page_nolevel);
            }
        }
        return $page_nolevel;
    }


    /*
     * 内容列表
     */
    public function list($type)
    {
        $request = \request()->param();
        $where = [];
        if (isset($request['category_id']) && count($request['category_id']) > 0)
        {
            $category_id = array_reverse($request['category_id'])[0];
            if($category_id > 0)
            {
                $where[] = ['a.category_id', '=', $category_id];
            }
        }
        if (isset($request['date']) && count($request['date']) == 2 && !empty($request['date'][0]))
        {
            $start = date('Y-m-d',strtotime($request['date'][0]));
            $end = date('Y-m-d',strtotime($request['date'][1]));
            $where[] = ['a.created_at', 'between', [$start, $end]];
        }
        if (isset($request['title']) && !empty($request['title']))
        {
            $title = $request['title'];
            $where[] = ['a.title', 'like', "%$title%"];
        }
        if (isset($request['status']) && !empty($request['status']))
        {
            $status = $request['status'];
            $where[] = ['a.status', '=', $status];
        }

        $status_cn = ['draft' => '草稿', 'pending' => '等待复审', 'publish' => '已发布'];
        $model = Db::table($this->table($type));
        $per_page = input('get.per_page') > 0 ? input('get.type') : $this->pageSize;
        $data = $model
            ->field('a.*, c.name as category_name')
            ->alias('a')
            ->leftJoin( $this->prefix .'category c','a.category_id = c.id')
            ->where($where)
            ->order('id','desc')
            ->paginate($per_page)->toArray();
        foreach ($data['data'] as $k => $v)
        {
            $data['data'][$k]['status_cn'] =  $status_cn[$v['status']];
        }
        return success($data);
    }

    /*
     * 删除内容
     */
    public function contentDelete()
    {
        $request = \request()->param();
        $type = $request['type'];
        $model = Db::table($this->table($type));
        if (strpos($request['id'], ',') !== false)
        {
            $id = explode(',', $request['id']);
        }else{
            $id = $request['id'];
        }
        $rs = $model->delete($id);
        userLog(session('user_id'), '删除内容：' . $request['id'] , $rs, $this->table($type));
        if ($rs)
        {
            return success();
        }else{
            return error('删除失败');
        }
    }

    protected function parent($parent_id = 0)
    {
        $categoryModel = new CategoryModel();
        $parent = $categoryModel
            ->field('id, parent_id')
            ->where('id', $parent_id)
            ->find()->toArray();
        array_push($this->parent, $parent['id']);
        if ($parent['parent_id'] > 0)
        {
            $this->parent($parent['parent_id']);
        }
        $data = array_reverse($this->parent);
        return $data;
    }

    public function contentView($type, $id)
    {
        $rs = Db::table($this->table($type))->where('id', $id)->find();
        if ($rs['category_id'] > 6)
        {
            $rs['category_id'] = $this->parent($rs['category_id']);
        }else{
            $rs['category_id'] = [0];
        }
        return success($rs);
    }

    public function contentSave($type, $id)
    {

        $request = \request()->param();
        unset($request['created_at']);
        unset($request['updated_at']);
        unset($request['published_at']);
        unset($request['type']);
        if (isset($request['src']) && empty($request['src']))
        {
            return error('附件地址不能为空');
        }
        if (strpos(',', $request['category_id']) !== true)
        {
            $request['category_id'] = array_reverse(explode(',', $request['category_id']))[0];
        }
        if ($request['category_id'] == 0)
        {
            $categoryModel = new CategoryModel();
            $category_id = $categoryModel
                ->field('id')
                ->where('slug','=',$type)
                ->find();
            $request['category_id'] = $category_id->id;
        }
        foreach ($request as $k => $v)
        {
            if ($v == 'null')
            {
                $request[$k] = '';
            }
        }

        if ($id > 0) {
            $id = $request['id'];
            $rs = Db::table($this->table($type))->where('id', $id)->save($request);
        }else{
            $rs = Db::table($this->table($type))->insert($request);
        }
        userLog(session('user_id'), "编辑内容：$type ：" . $request['title'] , $rs, $this->table($type));
        if ($rs)
        {
            return success();
        }else{
            return error('操作失败');
        }
        return success($rs);
    }
}