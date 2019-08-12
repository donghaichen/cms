<?php


namespace app\controller\admin;


use app\BaseController;
use app\model\Category as CategoryModel;
use app\validate\Category  as Validate;
use app\Request;
use think\exception\ValidateException;

class Category extends BaseController
{
    //@todo $parent 移动到函数内部
    protected $parent = [];

    protected function isValidate($data)
    {
        try {
            $result = validate(Validate::class)->batch(true)->check($data);
            if (true == $result) {
                return true;
            }
        } catch (ValidateException $e) {
            return $e->getError();
        }
    }

    public function index()
    {
        $data = $this->getCategoryList();
        return success($data);
    }

    public function cascader($type)
    {
        $none = [
            [
                "value" => 0,
                "label"=> "无上级"
            ]
        ];
        $data = $this->getCategory();
        if ($type != 'all')
        {
            $categoryModel = new CategoryModel();
            $slug = $categoryModel
                ->field('id, slug')
                ->where('id','<=',6)
                ->order(['sort','id'])
                ->select()
                ->toArray();
            foreach ($slug as $k => $v)
            {
                $temp[$v['slug']] = $v['id'];
            }
            $id = $temp[$type];
        }else{
            $id = 0;
        }
        if ($id > 0)
        {
            foreach ($data as $k => $v)
            {
                if ($v['id'] == $id)
                {
                    if (isset($v["children"]))
                    {
                        $data = $v["children"];
                    }else{
                        $data = [];
                    }

                }
            }
        }
        $data = array_merge($none, $data);
        return success($data);
    }

    public function save(Request $request)
    {
        $categoryModel = new CategoryModel();
        $request = $request->param();
        //系统顶级栏目别名不可以修改
        if ($request['id'] > 0 && $request['id'] <= 6)
        {
            unset($request['slug']);
            $request['parent_id'] = 0;
        }
        foreach ($request as $k => $v)
        {
            if ($v == 'null')
            {
                $request[$k] = '';
            }
        }
        $parent_id = array_reverse(explode(',', $request['parent_id']))[0];
        $request['parent_id'] = $parent_id;
        $request['is_show'] = $request['is_show'] == 'true' ? 1 : 0;
        $validate = $this->isValidate($request);
        if (is_array($validate))
        {
            foreach ($validate as $k => $v)
            {
                return error($v, 100000);
            }
        }
        if (isset($request['id']) && $request['id'] > 0) {
            $id = $request['id'];
            unset($request['id']);
            $rs = $categoryModel->where('id', $id)->save($request);
        }else{
            $rs = $categoryModel->save($request);
        }
        userLog(session('user_id'), '编辑栏目：' . $request['name'], $rs, 'category');
        if ($rs)
        {
            return success();
        }else{
            return error('操作失败');
        }
    }

    public function read(Request $request)
    {
        $categoryModel = new CategoryModel();
        $id = $request->param()['id'];
        $rs = $categoryModel->where('id', $id)->find();
        if ($rs)
        {
            if ($rs['parent_id'] > 0)
            {
                $rs['parent_id'] = $this->parent($rs['parent_id']);
            }else{
                $rs['parent_id'] = [0];
            }

            $rs['is_show'] = $rs['is_show'] == 1 ? true : false;
            return success($rs);
        }else{
            return error('获取数据失败');
        }
    }

    protected function parentData()
    {
        $categoryModel = new CategoryModel();
        $data = $categoryModel->field('id as value, parent_id')->select()->toArray();
        return $data;
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

    public function delete(Request $request)
    {
        $categoryModel = new CategoryModel();
        $id = $request->param()['id'];
        if ($id <= 6)
        {
            return error('该栏目为系统核心数据，不允许删除，你可以编辑栏目不展示该栏目');
        }
        if ($categoryModel->where('parent_id', $id)->count() > 0)
        {
            return error('该栏目下有子栏目，请先删除子栏目');
        }
        $rs = $categoryModel->destroy($id);
        userLog(session('user_id'), '删除栏目：' , $rs, 'category');
        if ($rs)
        {
            return success();
        }else{
            return error('删除失败');
        }
    }


    /**
     * +----------------------------------------------------------
     * 获取栏目
     * +----------------------------------------------------------
     * $parent_id 默认获取一级导航
     * $level 无限极分类层次
     * $mark 无限极分类标记
     * +----------------------------------------------------------
     */
    protected function getCategoryList($parent_id = 0, $level = 0, &$nav = [], $mark = ' - - ')
    {
        $categoryModel = new CategoryModel();
        $data = $categoryModel->order(['sort','id'])->select()->toArray();
        foreach ($data as $value) {
            if ($value['parent_id'] == $parent_id) {
                $value['mark'] = str_repeat($mark, $level);
                $value['name'] = str_repeat($mark, $level) . $value['name'];
                $nav[] = $value;
                $this->getCategoryList($value['id'], $level + 1, $nav);
            }
        }
        return $nav;
    }

    /**
     * +----------------------------------------------------------
     * 获取层级栏目
     * +----------------------------------------------------------
     * $parent_id 默认获取一级导航
     * +----------------------------------------------------------
     */
    protected function getCategory($parent_id = 0)
    {
        $categoryModel = new CategoryModel();
        $data = $categoryModel
            ->field('id, id as value, name as label, parent_id')
            ->where('parent_id', $parent_id)
            ->order(['sort','id'])
            ->select()
            ->toArray();
        foreach ($data as $k => $value) {
            $children = $categoryModel->where('parent_id', $value['id'])->count();
            if ($children > 0)
            {
                $data[$k]['children'] = $this->getCategory($value['id']);
            }
        }
        return $data;
    }

}
