<?php


namespace app\controller\admin;


use app\BaseController;
use think\facade\Db;

class Log extends BaseController
{
    public function admin()
    {
        $per_page = input('get.per_page') > 0 ? input('get.type') : $this->pageSize;
        $data = \app\model\Log::alias('l')
            ->leftJoin('user u', 'l.user_id = u.id')
            ->field('u.username,l.*')
            ->where('u.is_admin', 1)
            ->order('l.id', 'desc')
            ->paginate($per_page);
        return success($data);
    }

    public function getSysLog()
    {
        $per_page = input('get.per_page') > 0 ? input('get.type') : $this->pageSize;
        $data = Db::table('cms_sys_log')
            ->order('id', 'desc')
            ->paginate($per_page);
        return success($data);
    }


}