<?php


namespace app\lib\exception;


use think\exception\Handle;
use think\exception\HttpException;
use think\exception\ValidateException;
use think\facade\Env;
use think\facade\Log;
use think\facade\Request;
use think\Response;
use Throwable;

class ExceptionHandle extends Handle
{
    private $code;

    private $message;

    private $errorCode;

    //需要返回客户端当前请求的URL路径;
    public function render($request, Throwable $e): Response
    {
        if ($e instanceof BaseException) {
            //如果是自定义的异常
            //关闭tp6自动写入日志
            Log::close();
            //信息初始化
            $this->initInfo($e);
            //获取结果信息
            return json($this->getResult(), $this->code);
        } else {
            //是否开启tp6 error debug page
            if (Env::get('APP_DEBUG')) {
                return parent::render($request, $e);
            }else{
                //信息初始化
                $this->initInfo($e);
                //手动写入错误日志
                $this->recordErrorLog($e);
                //获取结果信息
                return json($this->getResult(), $this->code);
            }
        }
    }

    //信息初始化
    private function initInfo(Throwable $e)
    {
        $this->code = empty($e->code)?500:$e->code;
        $this->message = empty($e->message)?'unknown error':$e->message;
        $this->errorCode = empty($e->errorCode)?999:$e->errorCode;
    }

    //获取结果
    private function getResult(): array
    {
        return [
            'msg' => $this->message,
            'error_code' => $this->errorCode,
            'request_url' => Request::url()
        ];
    }

    //日志写入
    public function recordErrorLog(Throwable $e)
    {
        Log::record(json_encode(['msg'=>$e->getMessage(),'url'=>Request::url()]), 'error');
    }

}