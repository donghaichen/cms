<?php


namespace app\controller;


use app\BaseController;
use app\model\Ad as AdModel;
use app\model\Setting;
use think\facade\Db;

class test extends BaseController
{

    public function home()
    {
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
             [
                 'title' > '关于我们',
                 'sub' =>
                     [
                        getPageList(1),
                         ]
            ],

            [
                'title' > '新闻中心',
                'sub' =>
                    [
                        getCategoryList(1),
                    ]
            ],
            [
                'title' > '产品中心',
                'sub' =>
                    [
                        getCategoryList(2),
                    ]
            ],
            [
                'title' > '服务规则',
                'sub' =>
                    [
                        getPageList(16),
                    ]
            ],

            Db::table('cms_content_page')
                ->where('parent_id', '0')
                ->where('id','>', '1')
                ->where('id','<>', '16')
                ->select()->toArray()
        ];

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

        $news =  Db::table('cms_content_news')
            ->where('is_recommend', 1)
            ->order('id', 'desc')
            ->limit(5)
            ->select()->toArray();
        foreach ($news as $k => $v)
        {
            $news[$k]['content'] = subString($v['content'], 200);
            $news[$k]['image'] = imgUrl($v['image']);
        }
        $data = compact('setting', 'nav', 'ads');
        return success($data);
    }

    public function product()
    {

    }

    public function news()
    {

    }

    public function view()
    {

    }
}
