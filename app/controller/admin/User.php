<?php


namespace app\controller\admin;


use app\BaseController;
use app\model\Setting;
use think\exception\ValidateException;
use think\Request;
use app\validate\User as Validate;
use app\model\User as UserModel;

class User extends BaseController
{

    protected function isValidate($data)
    {
        try {
            $result = validate(Validate::class)->batch(true)->check($data);
            if (true == $result) {
                return true;
            }
        } catch (ValidateException $e) {
           return $e->getError();
        }
    }

    public function login(\app\Request $request)
    {
        $request = $request->param();
        $username = $request['username'];
        $password = $request['password'];
        $user = UserModel::where('username', $username)
            ->where('is_admin', 1)
            ->where('status', 1)
            ->find();
        if (is_null($user))
        {
            return error('用户名或者密码错误');
        }

        $user = $user->toArray();
        if ($user['id'] > 0 && password_verify($password, $user['password']))
        {
            $user_id = $user['id'];

            //删除密码
            unset($user['password']);

            //登录成功保存配置到本地
            $user['config'] = \setting();
            $user['menu'] = $this->menu();
            $user['userInfo'] = $user;

            //记录token
           if (empty($user['remember_token']))
           {
               UserModel::where('id', $user_id)->save([
                   'remember_token' => password_hash($password, PASSWORD_DEFAULT)
               ]);
           }

           //记录日志
            userLog($user_id, '登录后台：' . $user_id, $user);

            return success($user);
        }else{
            return error('用户名或者密码错误');
        }
    }

    public function index()
    {
        $per_page = input('get.per_page') > 0 ? input('get.type') : $this->pageSize;
        $data = UserModel::where('is_admin','<>',1)
            ->order('id','desc')
            ->paginate($per_page);
        return success($data);
    }

    public function adminList()
    {
        $per_page = input('get.per_page') > 0 ? input('get.type') : $this->pageSize;
        $data = UserModel::where('is_admin',1)
            ->order('id','desc')
            ->paginate($per_page);
        return success($data);
    }
    //添加编辑展示三合一
    public function view()
    {
        header('Access-Control-Allow-Origin:*');
        return success($_REQUEST);
    }

    public function read(Request $request)
    {
        $id = $request->param('id');
        $data = UserModel::where('id',$id)->find();
        unset($data['password']);
        $data['status'] = (string) $data['status'];
        return success($data);
    }

    public function save(Request $request)
    {
        $user  = new UserModel();
        $request = $request->param();
        $request['created_ip'] = getIp();
        unset($request['updated_at']);
        unset($request['created_at']);

        $validate = $this->isValidate($request);
        $password = isset($request['password']) ? $request['password'] : '';
        if (!empty($request['password']))
        {
            $request['password'] = password_hash($request['password'], PASSWORD_DEFAULT);
        }
        if (is_array($validate))
        {
            foreach ($validate as $k => $v)
            {
                return error($v, 100000);
            }
        }

        if (isset($request['id']) && $request['id'] > 0) {
            $id = $request['id'];
            unset($request['id']);
            if (empty($request['password']))
            {
                unset($request['password']);
            }
            $rs = $user->where('id', $id)->save($request);
            userLog(session('user_id'), '编辑用户：' . $request['username'], $rs, 'user');
        }else{
            if (empty($password))
            {
                return error('密码不能为空');
            }
            $rs = $user->save($request);
            userLog(session('user_id'), '添加用户：' . $request['username'], $rs, 'user');
        }

        if ($rs)
        {
            return success();
        }else{
            return error('添加失败');
        }
    }

    private function menu()
    {
        $nav =  [
            [
                'path' =>  '/', // 要跳转的路由名称 不是路径
                'name' =>  'home', // 要跳转的路由名称 不是路径
                'size' => 18, // icon大小
                'type' =>  'md-home', // icon类型
                'text' =>  '主页', // 文本内容

            ],
            [
                'path' =>  'setting', // icon类型
                'type' =>  'md-settings', // icon类型
                'text' =>  '系统管理', // 文本内容
                'children' => [
                    [
                        'path' =>  'setting', // icon类型
                        'type' =>  'ios-settings',
                        'name' =>  'setting',
                        'text' =>  '系统设置'
                    ],
                    [
                        'path' =>  'adminUser', // icon类型
                        'type' =>  'ios-people',
                        'name' =>  'adminUser',
                        'text' =>  '后台用户'
                    ],
//                    [
//                        'path' =>  'adminRole', // icon类型
//                        'type' =>  'ios-contact',
//                        'name' =>  'adminRole',
//                        'text' =>  '部门管理'
//                    ],
//                    [
//                        'type' =>  'ios-apps',
//                        'path' =>  'adminPermission',
//                        'name' =>  'adminPermission',
//                        'text' =>  '权限管理'
//                    ],
                    [
                        'type' =>  'ios-book',
                        'name' =>  'log',
                        'path' =>  'log',
                        'text' =>  '日志管理'
                    ]
                ]
            ],
            [
                'name' =>  'nav', // 要跳转的路由名称 不是路径
                'path' =>  'nav', // 要跳转的路由名称 不是路径
                'size' => 18, // icon大小
                'type' =>  'md-menu', // icon类型
                'text' =>  '导航管理', // 文本内容
                'component' =>  'Nav' // 文本内容
            ],
            [
                'type' =>  'md-folder', // icon类型
                'text' =>  '栏目管理', // 文本内容
                'path' =>  'category', // 文本内容
                'children' => [
                    [
                        'type' =>  'ios-folder',
                        'name' =>  'category',
                        'path' =>  'category', // 文本内容
                        'text' =>  '栏目列表'
                    ],
//                    [
//                        'type' =>  'logo-buffer',
//                        'name' =>  'model',
//                        'path' =>  'model',
//                        'text' =>  '模型管理'
//                    ]
                ]
            ],
            [
                'type' =>  'md-bookmarks', // icon类型
                'path' =>  'content', // icon类型
                'text' =>  '内容管理', // 文本内容
                'children' => [
                    [
                        'type' =>  'ios-document',
                        'name' =>  'file',
                        'path' =>  'file',
                        'text' =>  '文件管理'
                    ],
                    [
                        'type' =>  'ios-document',
                        'name' =>  'page',
                        'path' =>  'page',
                        'text' =>  '单页管理'
                    ],
                    [
                        'type' =>  'logo-hackernews',
                        'name' =>  'news',
                        'path' =>  'news',
                        'text' =>  '新闻管理'
                    ],
                    [
                        'type' =>  'ios-cart',
                        'name' =>  'product',
                        'path' =>  'product',
                        'text' =>  '产品管理'
                    ],
                    [
                        'type' =>  'ios-videocam',
                        'name' =>  'video',
                        'path' =>  'video',
                        'text' =>  '视频管理'
                    ],
                    [
                        'type' =>  'ios-download',
                        'name' =>  'download',
                        'path' =>  'download',
                        'text' =>  '下载管理'
                    ],
                    [
                        'type' =>  'ios-easel',
                        'name' =>  'case',
                        'path' =>  'case',
                        'text' =>  '案例管理'
                    ],
                    [
                        'type' =>  'ios-contacts',
                        'name' =>  'job',
                        'path' =>  'job',
                        'text' =>  '招聘管理'
                    ]
                ]
            ],
            [
                'type' =>  'logo-usd', // icon类型
                'text' =>  '营销管理', // 文本内容
                'path' =>  'marketing', // 文本内容
                'children' => [
                    [
                        'type' =>  'ios-easel',
                        'name' =>  'banner',
                        'path' =>  'banner', // 文本内容
                        'text' =>  '首页幻灯片'
                    ],
                    [
                        'type' =>  'ios-link',
                        'name' =>  'link',
                        'path' =>  'link',
                        'text' =>  '友情链接'
                    ],
//                    [
//                        'type' =>  'ios-paw',
//                        'name' =>  'ad',
//                        'path' =>  'ad',
//                        'text' =>  '广告管理'
//                    ]
                ]
            ],
            [
                'type' =>  'md-people', // icon类型
                'text' =>  '会员管理', // 文本内容
                'path' =>  'user', // 文本内容
                'children' => [
                    [
                        'type' =>  'ios-people',
                        'name' =>  'user',
                        'path' =>  'user',
                        'text' =>  '会员管理'
                    ],
//                    [
//                        'type' =>  'ios-apps',
//                        'name' =>  'userGroup',
//                        'path' =>  'userGroup',
//                        'text' =>  '会员组管理'
//                    ],
//                    [
//                        'type' =>  'ios-settings',
//                        'name' =>  'userSetting',
//                        'path' =>  'userSetting',
//                        'text' =>  '会员设置'
//                    ]
                ]
            ],
            [
                'name' =>  'mobile', // 要跳转的路由名称 不是路径
                'path' =>  'mobile', // 要跳转的路由名称 不是路径
                'size' => 18, // icon大小
                'type' =>  'md-phone-portrait', // icon类型
                'text' =>  '手机版管理' // 文本内容
            ],
            [
                'type' =>  'md-code-working', // icon类型
                'text' =>  '开发者中心', // 文本内容
                'path' =>  'developer', // 文本内容
                'children' => [
//                    [
//                        'type' =>  'ios-medical',
//                        'name' =>  'bak',
//                        'path' =>  'bak',
//                        'text' =>  '数据备份'
//                    ],
                    [
                        'type' =>  'ios-locate',
                        'name' =>  'runningLog',
                        'path' =>  'runningLog',
                        'text' =>  '运行日志'
                    ],
//                    [
//                        'type' =>  'ios-cloud',
//                        'name' =>  'cloud',
//                        'path' =>  'cloud',
//                        'text' =>  '云账户'
//                    ]
                ]

            ]
        ];
        return $nav;
    }
}
