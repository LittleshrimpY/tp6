<?php


namespace app\lib\exception;


class BannerMissException extends BaseException
{
    public $code = 404;
    public $message = 'Banner not fount';
    public $errorCode = 40000;

}