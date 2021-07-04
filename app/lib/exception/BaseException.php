<?php


namespace app\lib\exception;


use think\Exception;
use Throwable;

class BaseException extends Exception
{
    //HTTP 代码例如200、404、401、402、500等等等
    public $code = 400;
    //错误具体信息
    public $message = 'parameter error';
    //自定义错误代码
    public $errorCode = 10000;

    public function __construct($params = [])
    {
        if (!is_array($params)){
            return ;
        }
        if (array_key_exists('code',$params)){
            $this->code = $params['code'];
        }
        if (array_key_exists('message',$params)){
            $this->message = $params['message'];
        }
        if (array_key_exists('errorCode',$params)){
            $this->errorCode = $params['errorCode'];
        }
    }

}