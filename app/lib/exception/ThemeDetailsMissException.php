<?php


namespace app\lib\exception;


class ThemeDetailsMissException extends BaseException
{
    public $code = 404;

    public $message = 'Theme details not found';

    public $errorCode = 30001;
}