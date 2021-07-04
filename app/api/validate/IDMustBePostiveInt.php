<?php


namespace app\api\validate;

class IDMustBePostiveInt extends BaseValidate
{
    protected $rule = [
      'id' => 'require|isPostiveInteger'
    ];

    protected $message = [
      'id.isPostiveInteger'=>'id必须为正整数',
    ];
}