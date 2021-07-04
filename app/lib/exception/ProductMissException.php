<?php


namespace app\lib\exception;


class ProductMissException extends BaseException
{
    public $code = 404;

    public $message = 'Products not found';

    public $errorCode = 40000;
}