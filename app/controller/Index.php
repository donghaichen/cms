<?php
namespace app\controller;

use app\BaseController;
use app\model\Ad as AdModel;
use think\facade\Db;
use think\facade\Log;

class Index extends BaseController
{
    public function index()
    {

        $rs = Db::table('shiqiloucms_archive')
            ->where('catid', 2)
            ->order('aid', 'asc')
            ->find();
//        foreach ($rs as $k => $v)
//        {
//
//
//            $product[$k]['category_id'] = 1;
//            $rs = Db::table('cms_content_news')
//                ->where('title', $v['title'])->count();
//            if ($rs > 0)
//            {
//                $product[$k]['title'] = $v['title'] .  $k;
//            }else{
//                $product[$k]['title'] = $v['title'] .  $k;
//            }
//            $product[$k]['content'] = str_replace('http://www.luckfire.com', '', $v['content']);
//            $product[$k]['created_at'] = $v['adddate'];
////            $product[$k]['image'] = '/' . $v['thumb'];
////            $product[$k]['price'] = $v['attr2'];
//            $product[$k]['click_count'] = rand(100,99999);
////            $product[$k]['attribute'] = '[{"name":"规格","value":""},{"name":"材质","value":""},{"name":"产地","value":""}]';
//        }
////        var_dump($product);
////        exit();
//        foreach ($product as $k => $v)
//        {
//
////            if ($v['price'] == null)
////            {
////                $product[$k]['price'] = 0.00;
////            }
////            $product[$k]['album'] = json_encode($v['album']);
//        }
//
//        $rs = Db::table('cms_content_news')
//           ->insertAll($product);
//        var_dump($rs);
//        exit();
        $product = Db::table('cms_content_product')
            ->where('is_recommend', 1)
            ->order('id', 'desc')
            ->limit(8)
            ->select()->toArray();
        foreach ($product as $k => $v)
        {
            $product[$k]['price'] = round($v['price']);
            $product[$k]['image'] = imgUrl($v['image']);
            $product[$k]['price'] = $v['price'] == 0 ? '暂无报价' : $v['price'];
        }
        $about = Db::table('cms_content_page')
            ->where('id', 1)
            ->find();
        $about['content'] = subString($about['content'], 500);

        $news =  Db::table('cms_content_news')
            ->where('is_recommend', 1)
            ->order('id', 'desc')
            ->limit(6)
            ->select()->toArray();
        foreach ($news as $k => $v)
        {
            $news[$k]['content'] = subString($v['content'], 200);
            $news[$k]['image'] = imgUrl($v['image']);
        }

        $video =  Db::table('cms_content_video')
            ->where('is_recommend', 1)
            ->order('id', 'desc')
            ->limit(8)
            ->select()->toArray();
        foreach ($video as $k => $v)
        {
            $video[$k]['content'] = subString($v['content'], 200);
            $video[$k]['image'] = imgUrl($v['image']);
        }
        $data = compact('product', 'about', 'news', 'video');
        return view('index', $data);
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }

    public function msg()
    {
        $request = request()->param();
        $request['created_ip'] = getIp();
        $data = Db::table('cms_content_msg')
            ->save($request);
        return success($data);
    }
}
