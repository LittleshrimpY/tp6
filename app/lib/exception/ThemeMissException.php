<?php


namespace app\lib\exception;


class ThemeMissException extends BaseException
{
    public $code = 404;
    public $message = 'Theme not fount';
    public $errorCode = 30000;
}