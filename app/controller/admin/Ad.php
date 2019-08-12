<?php


namespace app\controller\admin;


use app\BaseController;
use app\model\Ad as AdModel;
use app\Request;

class Ad extends BaseController
{
    protected $type = [
        'banner',
        'h5Banner',
        'link',
    ];

    public function index()
    {
        $type = input('get.type');
        $data = AdModel::where('type',$type)
            ->order('sort','asc')
            ->order('id','desc')
            ->select()->toArray();
        foreach ($data as $k => $v)
        {
            $data[$k]['index'] = $k + 1;
            $data[$k]['status'] = $v['status'] == 1 ? true : false;
        }
        return success($data);
    }

    public function save(Request $request)
    {
        $ad  = new AdModel();
        $request = json_decode($request->param()['data'], true);
        foreach ($request as $k => $v)
        {
            unset($v['index']);
            $v['type'] = input('get.type');
            if (isset($v['id']) && $v['id'] > 0) {
                $id = $v['id'];
                unset($v['id']);
                $rs[] = $ad->where('id', $id)->save($v);
            }else{
                $rs[] = $ad->save($v);
            }
        }
        userLog(session('user_id'), '广告编辑：' , $rs, 'ad');
        if ($rs)
        {
            return success();
        }else{
            return error('添加失败');
        }
    }
}