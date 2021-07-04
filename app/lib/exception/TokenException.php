<?php


namespace app\lib\exception;


class TokenException extends BaseException
{
    public $code = 401;
    public $message = 'token error';
    public $errorCode = 50000;
}