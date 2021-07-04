<?php


namespace app\lib\exception;


class WxPayException extends BaseException
{
    public $code = 401;
    public $message = '支付发生错误';
    public $errorCode = 1001;
}