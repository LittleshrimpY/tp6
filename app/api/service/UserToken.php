<?php


namespace app\api\service;


use app\api\model\User as UserModel;
use app\lib\enum\Scope;
use app\lib\exception\TokenException;
use app\lib\exception\UserException;
use app\lib\exception\WeChatException;
use think\facade\Cache;
use think\Exception;

class UserToken extends Token
{
    protected $code;
    protected $appID;
    protected $appSecret;
    protected $loginUrl;

    public function __construct($code)
    {
        $this->code = $code;
        $this->appID = config('wx.app_id');
        $this->appSecret = config('wx.secret');
        $this->loginUrl = sprintf(config('wx.login_url'), $this->appID, $this->appSecret, $this->code);
    }

    public static function checkUser($uid){
        $user = UserModel::find($uid);
        if (!$user) {
            throw new UserException([
                'code' => 404,
                'message' => 'The user does not exist',
                'errorCode' => 90001
            ]);
        }
    }

    public function get()
    {
        $result = curl_post($this->loginUrl);
        $wxResult = json_decode($result, true);//true：数组  false：对象
        if (empty($wxResult)) {
            throw new Exception('获取session_key以及openID时异常，微信内部错误');
        } else {
            $loginFail = array_key_exists('errcode', $wxResult);
            if ($loginFail) {
                $this->processLoginError($wxResult);
            } else {
                $token = $this->grantToken($wxResult);
                return $token;
            }
        }
    }

    private function grantToken($wxResult)
    {
        //拿到openid
        //数据库查看一下，openid是否存在
        //如果存在，获取uid，如果不存在，进行注册并获取uid
        //生成令牌，准备缓存数据，写入缓存
        //把令牌返回客户端
        //key: 令牌
        //value:wxResult(openid、session_key、expire_in(有效期)), uid(用户id) ,scope(权限)
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);
        $uid = '';
        if (!$user) {
            //进行注册返回数据
            $uid = UserModel::registerByOpenID($openid);
        } else {
            //返回数据
            $uid = $user->id;
        }
        $key = self::generaToken();
        $cachedValue = json_encode($this->prepareCacheValue($wxResult, $uid));
        $this->toSaveCache($cachedValue, $key, config('secure.token_expire'));
        return $key;
    }

    private function toSaveCache($cachedValue, $Key, $expire)
    {
        $result = Cache::set($Key, $cachedValue, $expire);
        if (!$result){
            throw new TokenException([
               'message' => 'cache error',
               'errorCode' => 50001
            ]);
        }
    }

    private function prepareCacheValue($wxResult, $uid)
    {
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        $cachedValue['scope'] = Scope::USER;
        return $cachedValue;
    }

    private function processLoginError($wxResult)
    {
        throw new WeChatException([
                'message' => $wxResult['errmsg'],
                'errorCode' => $wxResult['errcode']
            ]
        );
    }
//    private function generaKey($openid)
//    {
//        $str = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789";
//        $ext = substr($str, mt_rand(0, strlen($str) - 1), 1);
//        $secretKey = $openid . time() . $ext;
//        $key = md5($secretKey);
//        return $key;
//    }


}