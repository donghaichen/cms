<?php


namespace app\model;


use think\Model;

class User extends Model
{
    protected $readonly = ['created_at', 'updated_at'];
}