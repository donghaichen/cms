<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

//前台
Route::post('/test', 'web.Index/Index');
Route::post('/msg', 'Index/msg');

// 注册路由到News控制器的read操作
Route::rule('product/view/:id','Product/view');
Route::rule('product/:category_id','Product/List');
Route::rule('news/view/:id','News/view');
Route::rule('news/:category_id','News/List');
Route::rule('case/view/:id','Cases/view');
Route::rule('case/:category_id','Cases/List');
Route::rule('video/view/:id','Video/view');
Route::rule('video/:category_id','Video/List');
Route::rule('page/:id','Page/view');

// 后台
Route::post('admin/v1/login', 'admin.User/login');
Route::get('admin/v1/geetest', 'admin.Common/geetest');
Route::get('admin/v1/bing', 'admin.Api/bing');
Route::get('admin/v1/log/getRunData', 'admin.Log/getRunData');
Route::get('admin/v1/common/msg/:id', 'admin.Common/msgView');
Route::get('admin/v1/common/msg', 'admin.Common/msg');
Route::get('admin/v1/common/localConfig', 'admin.Common/localConfig');
Route::get('admin/v1/common/config', 'admin.Common/config');
Route::post('admin/v1/common/upload', 'admin.Common/upload');
Route::get('admin/v1/common/file', 'admin.Common/fileList');
Route::post('admin/v1/common/setting', 'admin.Common/setting');
Route::get('admin/v1/common/setting', 'admin.Common/showSetting');
Route::get('admin/v1/common/menu', 'admin.Common/menu');
Route::get('admin/v1/common/dashboard', 'admin.Api/dashboard');

Route::resource('admin/v1/user', 'admin.User');
Route::get('admin/v1/user/list', 'admin.User/list');
Route::get('admin/v1/user/adminList', 'admin.User/adminList');
Route::get('admin/v1/log/admin', 'admin.Log/admin');
Route::get('admin/v1/log/sysLog', 'admin.Log/getSysLog');
Route::get('admin/v1/user/test', 'admin.User/test');
Route::resource('admin/v1/ad', 'admin.Ad');
Route::get('admin/v1/category/cascader/:type', 'admin.Category/cascader');
Route::resource('admin/v1/category', 'admin.Category');

//content
Route::get('admin/v1/content/list/:type', 'admin.Content/list')->pattern(['type' => '\w+']);
Route::get('admin/v1/content/view/:type/:id', 'admin.Content/contentView')->pattern(['type' => '\w+']);
Route::post('admin/v1/content/save/:type/:id', 'admin.Content/contentSave')->pattern(['type' => '\w+']);
Route::post('admin/v1/content/contentDelete', 'admin.Content/contentDelete');
Route::post('admin/v1/content/contentRecommend', 'admin.Content/contentRecommend');
Route::get('admin/v1/content/page/:id', 'admin.Content/pageView');
Route::post('admin/v1/content/page', 'admin.Content/pageSave');
Route::delete('admin/v1/content/page/:id', 'admin.Content/deletePage');
Route::get('admin/v1/content/page', 'admin.Content/pageList');


// m
Route::get('m/v1/home', 'm.Api/home');
Route::get('m/v1/news', 'm.Api/news');
Route::get('m/v1/product', 'm.Api/product');
Route::get('m/v1/view', 'm.Api/view');