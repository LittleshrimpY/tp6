<?php


namespace app\api\validate;


class Count extends BaseValidate
{
    protected $rule = [
      'count' => 'isPostiveInteger|between:1,15',
    ];

    protected $message = [
      'count.isPostiveInteger' => 'count必须为正整数',
    ];
}