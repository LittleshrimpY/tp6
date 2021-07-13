<?php


namespace app\api\validate;


class AddressUp extends BaseValidate
{
    protected $rule = [
        'name' => 'require|isNotEmpty',
        'mobile' => 'require|mobile',
        'province' => 'require|isNotEmpty',
        'city' => 'require|isNotEmpty',
        'country' => 'require|isNotEmpty',
        'detail' => 'require|isNotEmpty',
    ];

    protected $message = [
        'id.isPostiveInteger'=>'id必须为正整数',
        'name.isNotEmpty'=>'name不能为空',
        'mobile.isNotEmpty'=>'mobile不能为空',
        'province.isNotEmpty'=>'province不能为空',
        'city.isNotEmpty'=>'city不能为空',
        'country.isNotEmpty'=>'country不能为空',
        'detail.isNotEmpty'=>'detail不能为空',
    ];
}