<?php


namespace app\lib\exception;


class SignException extends BaseException
{
    public $code = 400;

    public $message = "sign error";

    public $errorCode = 100002;
}