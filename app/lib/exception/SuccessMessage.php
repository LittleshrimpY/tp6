<?php


namespace app\lib\exception;


class SuccessMessage extends BaseException
{
    public $code = 200;

    public $message = 'Success';

    public $errorCode = 100;
}