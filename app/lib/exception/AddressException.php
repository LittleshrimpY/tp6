<?php


namespace app\lib\exception;


class AddressException extends BaseException
{
    public $code = 400;

    public $message = 'Address error';

    public $errorCode = 60000;
}