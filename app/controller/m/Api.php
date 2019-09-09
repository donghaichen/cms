<?php


namespace app\controller\m;


use app\BaseController;
use app\model\Ad as AdModel;
use app\model\Setting;
use app\Request;
use think\facade\Db;

class Api extends BaseController
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
        $page = Db::table('cms_content_page')
            ->field('id, title')
            ->where('parent_id', '0')
            ->where('id','>', '1')
            ->where('id','<>', '16')
            ->select()->toArray();
        $nav = [
            1 =>[
                'id' => 1,
                'title' => 1
            ],
            2 =>[
                'id' => 2,
                'title' => 2
            ],
            3 =>[
                'id' => 33,
                'title' => 33
            ]

        ];
//        array_push($nav, $page);

//        $nav = [
////            [
////                'title' > '关于我们',
////                'children' =>
////                    [
////                        getPageList(1),
////                    ]
////            ],
////
////            [
////                'title' > '新闻中心',
////                'children' =>
////                    [
////                        getCategoryList(1),
////                    ]
////            ],
////            [
////                'title' > '产品中心',
////                'children' =>
////                    [
////                        getCategoryList(2),
////                    ]
////            ],
////            [
////                'title' > '服务规则',
////                'children' =>
////                    [
////                        getPageList(16),
////                    ]
////            ],
//
//        ];


        $product = Db::table('cms_content_product')
            ->where('is_recommend', 1)
            ->order('id', 'desc')
            ->limit(4)
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
        $data = compact('setting', 'nav', 'ads', 'product', 'news');
        return success($data);
    }

    public function product(Request $request)
    {
        $category = getCategoryList(2);
        $request = $request->param();
        $category_id = $request["category_id"];
        if ($category_id == 2)
        {
            $where = [];
        }else{
            $where = [
                ['category_id', '=', $category_id]
            ];
        }
        $page = isset($request['page']) ? $request['page'] : 10;
        $list = Db::table('cms_content_product')
            ->field('p.*, c.name as category_name')
            ->alias('p')
            ->leftJoin('cms_category c', 'c.id = p.category_id')
            ->where($where)
            ->order('p.id', 'desc')
            ->paginate([
                'list_rows'=> 10,
                'page' => $page,
            ]);
        $list = $list->toArray();
        $data = compact('list', 'category');
        return success($data);
    }

    public function news(Request $request)
    {
        $category = getCategoryList(1);
        $request = $request->param();
        $category_id = $request["category_id"];
        if ($category_id == 1)
        {
            $where = [];
        }else{
            $where = [
                ['category_id', '=', $category_id]
            ];
        }
        $page = isset($request['page']) ? $request['page'] : 10;
        $list = Db::table('cms_content_news')
            ->field('p.*, c.name as category_name')
            ->alias('p')
            ->leftJoin('cms_category c', 'c.id = p.category_id')
            ->where($where)
            ->order('p.id', 'desc')
            ->paginate([
                'list_rows'=> 10,
                'page' => $page,
            ]);
//        var_dump(Db::table('cms_content_news')->getLastSql());
        $list = $list->toArray();
        $data = compact('list', 'category');
        return success($data);
    }

    public function view(Request $request)
    {
        $request = $request->param();
        $id = $request['id'];
        $type = $request['type'];
        $table = 'cms_content_' . $type;
        if ($type != 'page')
        {
            clickCount($table, $id);
        }

        $data = Db::table($table)
            ->where('id', $id)
            ->find();
        if (isset($data['image']) && !empty($data['image']))
        {
            $data['image'] = imgUrl($data['image']);
        }
        if (isset($data['price'])) $data['price'] = $data['price'] == 0 ? '暂无报价' : $data['price'];
        $data['content'] = str_replace('<img src="', '<img src="/', $data['content']);
        return success($data);
    }
}