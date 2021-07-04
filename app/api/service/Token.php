<?php


namespace app\api\service;


use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use think\facade\Cache;
use think\facade\Request;

class Token
{
    public static function generaToken()
    {
        //32个字符组成一组随机字符串
        $randChar = getRandChar(32);
        //请求时间
        $requestTime = $_SERVER['REQUEST_TIME'];
        //盐 salt
        //返回由3个字符串拼接的md5
        return md5($randChar . $requestTime . config('secure.token_salt'));
    }

    /**
     * 获取指定key的cache值
     * @param $key
     * @return mixed
     * @throws TokenException
     */
    public static function getCurrentTokenVar($key)
    {
        $token = Request::header('token');
        if (empty($token)) {
            throw new TokenException([
                'message' => 'token不存在',
                'errorCode' => 50001
            ]);
        }
        $vars = Cache::get($token);
        if (!$vars) {
            throw new TokenException([
                'message' => 'token已失效',
                'errorCode' => 50002
            ]);
        } else {

            if (!is_array($vars)) {
                $vars = json_decode($vars, true);
            }
            if (array_key_exists($key, $vars)) {
                return $vars[$key];
            } else {
                throw new Exception('尝试获取的Token变量并不存在');
            }
        }
    }

    public static function checkOrderValid($user_id)
    {
        $uid = self::getCurrentUid();
        if ($user_id != $uid) {
            throw new OrderException([
                'message' => '订单号违法，支付失败',
                'errorCode' => '80002'
            ]);
        }
    }

    public static function getCurrentUid()
    {
        return self::getCurrentTokenVar('uid');
    }
}