<?php


namespace app\validate;



use think\Validate;

class Ad extends Validate
{
    protected $rule =   [
        'type'  => 'require|max:25|min:5',
    ];

    protected $message  =   [
        'type.require' => '类型必填',
        'type.min'     => '类型最少不能 5 个字符',
        'type.max'     => '类型最多不能超过 25 个字符',
    ];
}