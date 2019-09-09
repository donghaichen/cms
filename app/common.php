<?php

use app\model\Ad as AdModel;
use app\model\Category as CategoryModel;
use app\model\Log;
use app\model\Setting;
use think\facade\Db;

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

//新增响应成功失败
function success($data = [], $code = 0, $msg = '')
{
    $data = compact('code', 'msg', 'data');
    return json($data);
}

function error($msg = [], $code = 100000, $data = [])
{
    $data = compact('code', 'msg', 'data');
    return json($data);
}

function getIp()
{
    $onlineip='';
    if(getenv('HTTP_CLIENT_IP')&&strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')){
        $onlineip=getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR')&&strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')){
        $onlineip=getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR')&&strcasecmp(getenv('REMOTE_ADDR'),'unknown')){
        $onlineip=getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],'unknown')){
        $onlineip=$_SERVER['REMOTE_ADDR'];
    }
    return $onlineip;
}

/**
 * 对象 转 数组
 *
 * @param object $obj 对象
 * @return array
 */

function objectToArray($obj) {
    $obj = (array)$obj;
    foreach ($obj as $k => $v) {
        if (gettype($v) == 'resource') {
            return;
        }
        if (gettype($v) == 'object' || gettype($v) == 'array') {
            $obj[$k] = (array)objectToArray($v);
        }
    }

    return $obj;
}

function ipInfo($ip = '127.0.0.1')
{
    $url = 'http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT,60);
    $output = curl_exec($curl);
    curl_close($curl);
    $output = json_decode($output, true)['data'];
    $data = $output['country'] . '--' . $output['city'] . '--' . $output['isp'];
    return $data;
}

//记录用户操作
function userLog($user_id, $info = '', $response= [], $table = '')
{

    $response = json_encode($response);
    $method = \request()->method(true);
    $action = \request()->action(true);
    $controller = \request()->controller(true);
    $ua = $_SERVER['HTTP_USER_AGENT'];
    $url = \request()->url(true);
    $created_ip = getIp();
    $request = request()->param();
    if (isset($request['password']))
    {
        unset($request['password']);
    }
    $request = json_encode($request);
    $user_id = 1;
    $log = compact('user_id','url', 'info','method', 'controller', 'action', 'request', 'ua', 'response', 'created_ip');
    foreach ($log as $k => $v)
    {
        if (is_array($v))
        {
            $log[$k] = json_encode($v);
        }
    }
    $logModel = new Log();
    $logModel->save($log);
    if (!empty($table))
    {
        databaseLog($table);
    }
}

//记录系统数据
function databaseLog($table = '', $type = 'sql', $time = '0.0001')
{
//    $content = Db::table($table)->getLastSql();
//    Db::table('cms_sys_log')->save(compact('content', 'type', 'time'));
}

/**
 * +----------------------------------------------------------
 * 格式化商品价格
 * +----------------------------------------------------------
 * $price 需要格式化的价格
 * +----------------------------------------------------------
 */
function price_format($price = '') {
    $price = number_format($price, $GLOBALS['_CFG']['price_decimal']);
    $price_format = preg_replace('/d%/Ums', $price, $GLOBALS['_LANG']['price_format']);

    return $price_format;
}

/**
 * +----------------------------------------------------------
 * 获取当前分类下所有子分类
 * +----------------------------------------------------------
 * $table 数据表名
 * $parent_id 父类ID
 * $child 子类ID零时存储器
 * +----------------------------------------------------------
 */
function childId($table, $parent_id = '0', &$child_id = '') {
    $data = Db::query("SELECT * FROM " . table($table) . " ORDER BY sort ASC, cat_id ASC");
    foreach ((array) $data as $value) {
        if ($value['parent_id'] == $parent_id) {
            $child_id .= ',' . $value['cat_id'];
            childId($table, $value['cat_id'], $child_id);
        }
    }

    return $child_id;
}

function setting()
{
    $data = [];
    $setting = Setting::select()->toArray();
    foreach ($setting as $k => $v)
    {
        if ($v['name'] == 'product_attribute')
        {
            foreach (explode(',', $v['value']) as $key => $value)
            {
                $data['product_attribute'][$key]['name'] = $value;
                $data['product_attribute'][$key]['value'] = '';
            }
        }else{
            $data[$v['name']] = $v['value'];
        }
    }
    return $data;
}


if(! function_exists('curl')){
    //HTTP请求（支持HTTP/HTTPS，支持GET/POST）
    function curl($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}

function view($path = 'index', $data)
{
    if (isMobile())
    {
        return redirect('/m/');
    }
    $data = $data;
    $config = config();
    $settingArr = Setting::select()->toArray();
    foreach ($settingArr as $k => $v)
    {
        $setting[$v['name']] = $v['value'];
    }
    $ads = AdModel::where('type', 'in', ['banner', 'link'])
        ->where('status', 1)
        ->order('sort','asc')
        ->order('id','desc')
        ->select()->toArray();

    $nav = [
        getPageList(1),
        getCategoryList(1),
        getCategoryList(2),
        getPageList(16),
        Db::table('cms_content_page')
            ->where('parent_id', '0')
            ->where('id','>', '1')
            ->where('id','<>', '16')
            ->select()->toArray()
    ];
    include \think\facade\App::getBasePath() .'/view/' . $path . '.php';
}

function load($path = 'index')
{
    include \think\facade\App::getBasePath() .'/view/' . $path . '.php';
}


function subString($text, $length)
{
    $text = strip_tags($text);
    $text = trim($text);
//    $text = preg_replace("/^[\s\v".chr(227).chr(128)."]+/","", $text); //替换开头空字符
//    $text = preg_replace("/[\s\v".chr(227).chr(128)."]+$/","", $text); //替换结尾空字符

    if(mb_strlen($text, 'utf8') > $length) {
        return mb_substr($text, 0, $length, 'utf8').'...';
    } else {
        return $text;
    }

}

/*
 * 对图片地址统一管理
 */
function imgUrl($img = '')
{
    if (empty($img))
    {
        $img = '/static/images/nopic.jpg';
    }
    return $img;
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
function getCategoryList($parent_id = 0, $level = 0, &$nav = [], $mark = ' - - ')
{
    $categoryModel = new CategoryModel();
    $data = $categoryModel->order(['sort','id'])->select()->toArray();
    foreach ($data as $value) {
        if ($value['parent_id'] == $parent_id) {
            $value['mark'] = str_repeat($mark, $level);
//            $value['name'] = str_repeat($mark, $level) . $value['name'];
            $nav[] = $value;
            getCategoryList($value['id'], $level + 1, $nav);
        }
    }
    return $nav;
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
function getPageList($parent_id = 0, $level = 0, &$nav = [], $mark = ' - - ')
{
    $categoryModel =  Db::table('cms_content_page');
    $data = $categoryModel
        ->field('*, title as name')
        ->order(['sort','id'])
        ->select()->toArray();
    foreach ($data as $value) {
        if ($value['parent_id'] == $parent_id) {
            $value['mark'] = str_repeat($mark, $level);
            $nav[] = $value;
            getPageList($value['id'], $level + 1, $nav);
        }
    }
    return $nav;
}


function clickCount($table = 'content_product', $id = 0)
{
    Db::table($table)->where('id', $id)->inc('click_count', 1)->update();
}

function isMobile()
{
    // These lines are mandatory.
    require_once \think\facade\App::getBasePath() .'/mobileDetect.php';
    $detect = new Mobile_Detect;
    if ($detect->isMobile()) {
        return true;
    }
    return false;
}
