<?php


namespace app\controller\admin;


use app\BaseController;
use think\facade\Db;

class Api extends BaseController
{
    public function bing()
    {
        $bing = curl('https://www.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1');
        $img = 'https://cn.bing.com' . json_decode($bing, true)['images'][0]['url'];
        return redirect($img);
    }
    protected function count(){
        $data['newsCount'] = Db::table('cms_content_news')->count();
        $data['newsTime'] = date('Y-m-d H:i:s');
        $data['productCount'] = Db::table('cms_content_product')->count();
        $data['productTime'] = date('Y-m-d H:i:s');
        return $data;
    }

    protected function notice()
    {
        $notice = curl('https://forum.mengniang.tv/api/v1/notice');
        $notice = json_decode($notice, true);
        return $notice['data'];
    }
    public function dashboard()
    {
        $dashboard = [
            'php_version' => PHP_VERSION, //获取PHP服务器版本
            'pdo_mysql' => extension_loaded('pdo_mysql') ? "是" : "否", //获取PHP服务器版本
            'curl' => extension_loaded('curl') ? "是" : "否", //获取PHP服务器版本
            'ctype' => extension_loaded('ctype') ? "是" : "否", //获取PHP服务器版本
            'openSSL' => extension_loaded('openSSL') ? "是" : "否",
            'sockets' => extension_loaded('sockets') ? "是" : "否",
            'web' => $_SERVER["SERVER_SOFTWARE"], //获取服务器标识的字串
            'os' => php_uname(), //获取系统类型及版本号
            'ip' =>  $_SERVER['SERVER_ADDR'], //服务器IP地址
            'time' => date("Y-m-d H:i:s", time()), //获取服务器时间
            'max_upload_size' => ini_get("file_uploads") ? ini_get("upload_max_filesize") : "Disabled", //最大上传
            'max_execution_time' => ini_get("max_execution_time") . "秒", //脚本最大执行时间
            'host' => $_SERVER["HTTP_HOST"],
            'sapi_name' => php_sapi_name(),
//            'curl' => function_exists('curl_init') ? "是" : "否",
            'cpu' => isset($_SERVER['PROCESSOR_IDENTIFIER']) ? $_SERVER['PROCESSOR_IDENTIFIER'] : '没有权限获取',
            'disk_free_space' => round((disk_free_space(".") / (1024 * 1024 * 1024)),2) . 'G',
            'disk_total_space' => round((disk_total_space(".") / (1024 * 1024 * 1024)),2) . 'G',
            'disk' => 100 - round(disk_free_space(".") /disk_total_space(".") * 100,0) ,
        ];
        $count = $this->count();
        $admin_log = \app\model\Log::alias('l')
            ->leftJoin('user u', 'l.user_id = u.id')
            ->field('u.username,l.*')
            ->where('u.is_admin', 1)
            ->order('l.id', 'desc')
            ->limit(4)
            ->select();
        $notice = $this->notice();
        $data = compact('dashboard', 'count', 'admin_log', 'notice');
        return success($data);
    }

    public function router($arr)
    {
        foreach ($arr as $k => $v)
        {
            if (isset($v['children']))
            {
                $name = $v['path'];
                foreach ($v['children'] as $kk => $vv)
                {
                    $newArr[]['name'] = $name . '/'. $vv['name'];
                }
            }else{
                $newArr[]['name'] = $v['path'];

            }
        }
        unset($newArr[0]);
        foreach ($newArr as $k => $v)
        {
            $str = explode('/', str_replace('//', '', $v['name']));
            if (isset($str[1]))
            {
                $name = $str[1];
                $folder = $str[0];
            }else{
                $name = $str[0];
                $folder = $str[0];
            }
            $array[$k]['path'] = $v['name'];
            $array[$k]['name'] = $name;
            $array[$k]['file'] = "$folder/" . ucfirst($name) . ".vue";
            $array[$k]['component'] = " () => import('./views/$folder/" . ucfirst($name) . ".vue')";
//        component: () => import('./views/Login.vue')
        }
        return $array;
    }

    public function createFile($nav)
    {
        foreach (router($nav) as $k => $v)
        {
            $component = $v['file'];
            $path = explode('/', $component)[0];
            $dir = 'C:/Users/Administrator/Desktop/antpro/admin/src/views/';
            $path = $dir . $path;
            if (is_dir($path)){
                echo "对不起！目录 " . $path . " 已经存在！";
            }else{
                $res=mkdir($path,0777,true);
                if ($res){
                    echo "目录 $path 创建成功";
                }else{
                    echo "目录 $path 创建失败";
                }
            }
            $data = "<template>
    <div class=\"content\">
    <h1>This is an $component page</h1>
  </div>
</template>

<script>
  export default {
    
  }
</script>

<style scoped>

</style>";
            file_put_contents($dir . $component, $data);
        }
    }

}