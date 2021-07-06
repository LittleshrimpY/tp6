<?php


namespace app\api\validate;


class AppTokenGet extends BaseValidate
{
    protected $rule = [
        'ac' => 'require|isNotEmpty',
        'se' => 'require|isNotEmpty',
    ];

    protected $message = [
        'ac.isNotEmpty'=>"账户名不能为空",
        'se.isNotEmpty'=>"密码不能为空",
    ];
}