<?php


namespace app\lib\exception;


class ParameterException extends BaseException
{
    public $code = 400;
    public $message = 'Parameter error';
    public $errorCode = 10000;
}