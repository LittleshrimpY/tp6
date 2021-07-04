<?php


namespace app\lib\exception;


class UserException extends BaseException
{
    public  $code = 400;

    public  $message = 'User error';

    public  $errorCode = 90000;
}