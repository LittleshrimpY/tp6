<?php


namespace app\lib\exception;


class CategoryMissException extends BaseException
{
    public $code = 404;
    public $message = 'Categories not found';
    public $errorCode = 40000;
}