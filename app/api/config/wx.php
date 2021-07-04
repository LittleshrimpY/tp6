<?php
return [
    'app_id' => 'wxfdce73750e57e0a8', //
    'secret' => '0fd4e81b76115cefc4b60b6396d323c4', //小程序密钥
    'login_url' => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',
    'order_url' => 'https://api.mch.weixin.qq.com/pay/unifiedorder',
    'mch_id	' => '123123123', //商户号
    'key' => 'adfsadfadsf', //商户支付密钥
    "app_secret" => '123123123123', //公众帐号secert
    'sign_type' => "MD5", //签名算法
    'notify_url' => 'www.z6.cn\api\v1\pay\notify', //回调函数
    ];
