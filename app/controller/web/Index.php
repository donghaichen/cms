<?php


namespace app\controller\admin;


class Index
{
    public function index()
    {
        $data = [
            'name'  => 'ThinkPHP',
            'email' => 'thinkphp@qq.com'
        ];
        return view('index', $data);
    }
}