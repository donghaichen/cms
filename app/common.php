<?php

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
    $content = Db::table($table)->getLastSql();
    Db::table('cms_sys_log')->save(compact('content', 'type', 'time'));

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