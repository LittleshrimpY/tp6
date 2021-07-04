<?php


namespace app\api\validate;


class PagingParameter extends BaseValidate
{
    protected $rule = [
        'page' => 'require|isPostiveInteger',
        'size' => 'require|isPostiveInteger',
    ];

    protected $message = [
        'page.isPositiveInteger' => 'page必须为正整数',
        'size.isPositiveInteger' => 'size必须为正整数',
    ];
}