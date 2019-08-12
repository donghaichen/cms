<?php


namespace app\validate;

use think\Validate;

class Category extends Validate
{
    /*
     * @todo 验证问题检查
     */
    protected $rule =   [
        'name'  => 'require|max:100|min:2',
        'sug'  => 'unique:sug|max:100|min:2',
    ];

    protected $message  =   [
        'name.require' => '栏目标题必填',
        'name.max'     => '栏目标题最多不能超过 100 个字符',
        'name.min'     => '栏目标题最少不能 2 个字符',
//        'sug.unique' => '栏目别名已存在',
//        'sug.max'     => '栏目别名最多不能超过 100 个字符',
//        'sug.min'     => '栏目别名最少不能 2 个字符',
    ];
}