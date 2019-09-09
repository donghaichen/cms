<?php


namespace app\controller\admin;


use app\BaseController;
use app\controller\Geetest;
use app\model\Setting;
use think\exception\ValidateException;
use think\facade\Config;
use think\facade\Db;
use think\facade\Filesystem;
use think\Request;

class Common extends BaseController
{
    public function geetest()
    {
        $config = Config::get('app');
        $GtSdk = new Geetest($config['captcha_id'], $config['private_key']);
        $data = array(
            "user_id" => "test", # 网站用户id
            "client_type" => "h5", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            "ip_address" => getIp() # 请在此处传输用户请求验证时所携带的IP
        );
        $GtSdk->pre_process($data, 'true');
        return success(json_decode($GtSdk->get_response_str(), true));
    }

    //前端存储转换过格式的配置到 localStorage 用户一些业务逻辑
    public function localConfig()
    {
        return success(\setting());
    }

    public function config()
    {
        $data = Setting::select()->toArray();
        $rs = [];
        foreach ($data as $k => $v)
        {
            $rs[$v['name']] = $v['value'];
        }

        return success($rs);
    }

    public function upload()
    {
        // 获取表单上传文件
        $files = request()->file();
        $module = request()->param()['module'];

        //fileSize	上传文件的最大字节
        //fileExt	文件后缀，多个用逗号分割或者数组
        //fileMime	文件MIME类型，多个用逗号分割或者数组
        //image	验证图像文件的尺寸和类型
        try {
            validate(
                ['image'=> "fileSize:1024*100|fileExt:jpg,png,jpeg,gif"],
                ['file' => 'fileSize:1024*1000|fileExt:rar,zip,tar.gz,exe,txt,doc,docx,xls,xlsx,ppt,pdf,mp4,rmvb,flv']
            )
                ->check($files);
            $savename = '';
            foreach($files as $file) {
                $savename = Filesystem::disk('public')->putFile( $module . '/' .date('Y-m'), $file, 'sha1');
                $url = "/upload/" . str_replace('\\', '/',$savename);
            }

            //添加附加参数 方便前端识别图片具体位置
            $request = request()->param();
            $additional = [];
            if (is_array($request))
            {
                $additional = $request;
            }

            //记录文件到数据库
            $src = $url;
            $type = $_FILES['file']['type'];
            $size = $_FILES['file']['size'];
            $name = $_FILES['file']['name'];
            $created_ip = getIp();
            $rs = compact('name','module', 'type', 'src', 'size', 'created_ip');
            Db::table('cms_file')->save($rs);
            userLog(session('user_id'), '上传文件：' . $src, $rs, 'file');
            return success(compact('url', 'additional'));
        } catch (ValidateException $e) {
            return error('上传失败', 100000, $e->getMessage());
        }
    }

    public function fileList()
    {
        $per_page = input('get.per_page') > 0 ? input('get.per_page') : $this->pageSize;
        $data = Db::table('cms_file')
            ->order('id', 'desc')
            ->paginate($per_page);
        return success($data);
    }

    public function setting()
    {
        $request = request()->param();
        $setting = New Setting();
        foreach ($request as $k => $v)
        {
            $name = $k;
            $value = $v;
            $exist = $setting->where('name', $name)->count();
            if ($exist == 1)
            {
                $rs[] = $setting->where('name', $k)->save(compact('name', 'value'));
            }else{
                $rs[] = $setting->save(compact('name', 'value'));
            }
        }
        userLog(session('user_id'), '更新设置：' , $rs, 'setting');
        return success($rs);
    }

    public function showSetting()
    {
        $setting = New Setting();
        $rs = $setting->select();
        foreach ($rs as $k => $v)
        {
            $name = $v['name'];
            if ($v['value'] == "true")
            {
                $value = true;
            }elseif($v['value'] == "false"){
                $value = false;
            }else{
                $value = $v['value'];
            }
            $data[$name] = $value;
        }
        return success($data);
    }

    public function msg()
    {
        $per_page = input('get.per_page') > 0 ? input('get.per_page') : $this->pageSize;
        $data = Db::table('cms_content_msg')
            ->order('id', 'desc')
            ->paginate($per_page);
        return success($data);
    }

    public function msgView(Request $request)
    {
        $id = $request->param('id');
        $data = Db::table('cms_content_msg')->where('id',$id)->find();
        return success($data);
    }

}
