<?php


namespace app\controller;


use app\BaseController;
use app\Request;
use think\facade\Db;

class News extends BaseController
{
    protected $module = 'news';
    protected $categoryName = '新闻中心';

    public function list(Request $request)
    {
        $categoryList = getCategoryList(1);
        $request = $request->param();
        $category_id = $request["category_id"];
        if ($category_id == 1)
        {
            $where = [];
        }else{
            $where = [
                ['category_id', '=', $category_id]
            ];
        }
        $page = isset($request['page']) ? $request['page'] : 0;
        $list = Db::table('cms_content_news')
            ->field('p.*, c.name as category_name')
            ->alias('p')
            ->leftJoin('cms_category c', 'c.id = p.category_id')
            ->where($where)
            ->order('p.id', 'desc')
            ->paginate([
                'list_rows'=> 9,
                'page' => $page,
            ]);
        // 获取分页显示
        $page = $list->render();
        $list = $list->toArray();
        foreach ($list['data'] as $k => $v)
        {
            $list['data'][$k]['content'] = subString($v['content'], 200);
            $list['data'][$k]['image'] = imgUrl($v['image']);
        }
        $module = $this->module;
        $categoryName = $this->categoryName;
        $data = compact('list', 'page', 'categoryList', 'module', 'categoryName');
        return view('news/list', $data);
    }

    public function view($id)
    {
        clickCount('cms_content_news', $id);
        $categoryList = getCategoryList(1);
        $data = Db::table('cms_content_news')
            ->field('p.*, c.name as category_name')
            ->alias('p')
            ->leftJoin('cms_category c', 'c.id = p.category_id')
            ->where('p.id', $id)
            ->find();
        $data['categoryList'] = $categoryList;
        $data['module'] = $this->module;
        $data['categoryName'] = $this->categoryName;
        return view('news/view', $data);
    }
}