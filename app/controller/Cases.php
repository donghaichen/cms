<?php


namespace app\controller;


use app\BaseController;
use app\Request;
use think\facade\Db;

class Cases extends BaseController
{
    protected $module = 'product';
    protected $categoryName = '案例展示';

    public function list(Request $request)
    {
        $categoryList = getCategoryList(2);
        $request = $request->param();
        $category_id = $request["category_id"];
        if ($category_id == 2)
        {
            $where = [];
        }else{
            $where = [
                ['category_id', '=', $category_id]
            ];
        }
        $page = isset($request['page']) ? $request['page'] : 0;
        $list = Db::table('cms_content_case')
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
        $module = $this->module;
        $categoryName = $this->categoryName;
        $data = compact('list', 'page', 'categoryList', 'module', 'categoryName');
        return view('case/list', $data);
    }

    public function view($id)
    {
        clickCount('cms_content_case', $id);
        $categoryList = getCategoryList(2);
        $data = Db::table('cms_content_case')
            ->field('p.*, c.name as category_name')
            ->alias('p')
            ->leftJoin('cms_category c', 'c.id = p.category_id')
            ->where('p.id', $id)
            ->find();

        $data['categoryList'] = $categoryList;
        $data['module'] = $this->module;
        $data['categoryName'] = $this->categoryName;
        return view('case/view', $data);
    }
}