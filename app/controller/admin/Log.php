<?php


namespace app\controller\admin;


use app\BaseController;
use think\App;
use think\facade\Db;

class Log extends BaseController
{

    public function admin()
    {
        $per_page = input('get.per_page') > 0 ? input('get.per_page') : $this->pageSize;
        $data = \app\model\Log::alias('l')
            ->leftJoin('user u', 'l.user_id = u.id')
            ->field('u.username,l.*')
            ->where('u.is_admin', 1)
            ->order('l.id', 'desc')
            ->paginate($per_page);
        return success($data);
    }

    public function getRunData()
    {
        // $type 默认获取log数据 如果需要其他类型可以自行传参 支持类型【cache  log  temp】
        $type = input('type/s', 'log');
        $array_files_log = [];
        $model_path = \think\facade\App::getRootPath() . "runtime/{$type}";
        switch ($type) {
            case 'cache':
                $array_files_log = glob($model_path . '/*/*');
                break;
            case 'log':
                $array_files_log = glob($model_path . '/*/*');
                break;
            case 'temp':
                $array_files_log = glob($model_path . '/*');
                break;
        }

        if (empty($array_files_log)) {
            return success();
        }
        $ret[$type] = $array_files_log;

        $log_file = $ret[$type][0];

        $log = file_get_contents($log_file);
        $log = explode(PHP_EOL, $log);
        //条数
        $per_page = input('get.per_page') > 0 ? input('get.per_page') : $this->pageSize;
        $current_page = input('get.page') > 0 ? input('get.page') : 0;
        $offset = ceil(round($per_page * $current_page));
        $total = count($log);
        $log = array_slice($log,$offset, $per_page);
        foreach ($log as $k => $v)
        {
            $data[$k]['content'] = $v;
        }
        return success(compact('total', 'per_page', 'current_page', 'data'));

    }


}