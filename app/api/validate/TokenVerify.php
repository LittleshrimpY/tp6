<?php


namespace app\api\validate;


class TokenVerify extends BaseValidate
{
    protected $rule = [
        'token' => 'require',
    ];

    protected $message = [
        'token.require' => 'token必须传递',
    ];
}