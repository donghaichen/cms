<?php


namespace app\controller;


use app\BaseController;
use app\Request;
use think\facade\Db;

class Page extends BaseController
{
    protected $module = 'page';
    protected $categoryName = '关于我们';


    public function view($id)
    {
        $data = Db::table('cms_content_page')
            ->where('id', $id)
            ->find();

        $data['module'] = $this->module;
        $count = Db::table('cms_content_page')
            ->where('parent_id', $id)
            ->count();
        $parentCount = Db::table('cms_content_page')
            ->where('id', $data['parent_id'])
            ->find();
        if ($count > 0)
        {
            $data['categoryList'] = getPageList($id);
            $data['categoryName'] = $data['title'];
        }elseif(!is_null($parentCount)){
            $id = $parentCount['id'];
            $data['categoryList'] = getPageList($id);
            $data['categoryName'] = $parentCount['title'];
        }
        else{
            $data['categoryList'] = getPageList(1);
            $data['categoryName'] = $this->categoryName;
        }

        return view('page', $data);
    }
}