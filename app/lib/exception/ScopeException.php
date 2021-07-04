<?php


namespace app\lib\exception;


class ScopeException extends BaseException
{
    public $code = 401;

    public $message = '权限不足';

    public $errorCode = 10001;
}