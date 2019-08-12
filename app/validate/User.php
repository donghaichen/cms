<?php


namespace app\validate ;


use think\Validate;

class User extends Validate
{
    protected $rule =   [
        'username'  => 'require|unique:user|max:25|min:5',
        'mobile'  => 'mobile|unique:user|max:11|min:11',
        'nickname'  => 'unique:user|max:25|min:5',
        'password'  => 'max:25|min:5',
        'desc'  => 'max:255|min:5',
        'url'   => 'url',
        'email' => 'email',
    ];

    protected $message  =   [
        'username.require' => '用户名必填',
        'username.unique' => '用户名已存在',
        'username.max'     => '用户名最多不能超过 25 个字符',
        'username.min'     => '用户名最少不能 5 个字符',
        'mobile.mobile' => '手机号格式不正确',
        'mobile.unique' => '手机号已存在',
        'mobile.max'     => '手机号最多不能超过 11 个字符',
        'mobile.min'     => '手机号最少不能 11 个字符',
        'nickname.unique'     => '昵称最多不能超过 25 个字符',
        'nickname.max'     => '昵称已存在',
        'nickname.min'     => '昵称名最少不能超过 5 个字符',
        'password.max'     => '密码最多不能超过 25 个字符',
        'password.min'     => '密码最少不能 5 个字符',
        'desc.max'     => '密码最多不能超过 255 个字符',
        'desc.min'     => '密码最少不能 5 个字符',
        'url'        => '个人主页格式错误',
        'email'        => '邮箱格式错误',
    ];

}