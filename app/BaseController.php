<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app;

use app\model\Log;
use think\App;
use think\exception\ValidateException;
use think\facade\Db;
use think\Validate;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /*
     * 默认分页
     * @var float
     */
    protected $pageSize = 0;

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;
        $this->pageSize = config('app.pageSize');
        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {}

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                list($validate, $scene) = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }
//
//    //新增响应成功失败
//    public function success($data = [], $code = 0, $msg = '')
//    {
//        $data = compact('code', 'msg', 'data');
//        return json($data);
//    }
//
//    public function error($msg = [], $code = 100000, $data = [])
//    {
//        $data = compact('code', 'msg', 'data');
//        return json($data);
//    }
//
//    protected function getIp()
//    {
//        $onlineip='';
//        if(getenv('HTTP_CLIENT_IP')&&strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')){
//            $onlineip=getenv('HTTP_CLIENT_IP');
//        } elseif(getenv('HTTP_X_FORWARDED_FOR')&&strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')){
//            $onlineip=getenv('HTTP_X_FORWARDED_FOR');
//        } elseif(getenv('REMOTE_ADDR')&&strcasecmp(getenv('REMOTE_ADDR'),'unknown')){
//            $onlineip=getenv('REMOTE_ADDR');
//        } elseif(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],'unknown')){
//            $onlineip=$_SERVER['REMOTE_ADDR'];
//        }
//        return $onlineip;
//    }
//
//    /**
//     * 对象 转 数组
//     *
//     * @param object $obj 对象
//     * @return array
//     */
//
//    protected function objectToArray($obj) {
//        $obj = (array)$obj;
//        foreach ($obj as $k => $v) {
//            if (gettype($v) == 'resource') {
//                return;
//            }
//            if (gettype($v) == 'object' || gettype($v) == 'array') {
//                $obj[$k] = (array)$this->objectToArray($v);
//            }
//        }
//
//        return $obj;
//    }
//
//    protected function ipInfo($ip = '127.0.0.1')
//    {
//        $url = 'http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip;
//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_URL, $url);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($curl, CURLOPT_TIMEOUT,60);
//        $output = curl_exec($curl);
//        curl_close($curl);
//        $output = json_decode($output, true)['data'];
//        $data = $output['country'] . '--' . $output['city'] . '--' . $output['isp'];
//        return $data;
//    }
//
//    //记录用户操作
//    protected function userLog($user_id, $info = '', $response= [])
//    {
//
//        $response = json_encode($response);
//        $method = \request()->method(true);
//        $action = \request()->action(true);
//        $controller = \request()->controller(true);
//        $ua = $_SERVER['HTTP_USER_AGENT'];
//        $url = \request()->url(true);
//        $created_ip = $this->getIp();
//        $request = request()->param();
//        if (isset($request['password']))
//        {
//            unset($request['password']);
//        }
//        $request = json_encode($request);
//        $user_id = 1;
//        $log = compact('user_id','url', 'info','method', 'controller', 'action', 'request', 'ua', 'response', 'created_ip');
//        foreach ($log as $k => $v)
//        {
//            if (is_array($v))
//            {
//                $log[$k] = json_encode($v);
//            }
//        }
//        $logModel = new Log();
//        $logModel->save($log);
//    }
//
//    //记录系统数据
//    protected function sysLog($content = '', $type = 'sql', $time = '0.0001')
//    {
//
//        Db::table('cms_sys_log')->save(compact('content', 'type', 'time'));
//    }
}
