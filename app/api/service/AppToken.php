<?php


namespace app\api\service;


use app\api\model\ThirdApp;
use app\lib\exception\TokenException;
use think\Console;
use think\facade\Cache;

class AppToken extends Token
{
    public function get($ac = '', $se = '')
    {
        $app = ThirdApp::check($ac, $se);
        if (!$app) {
            throw new TokenException([
                'message' => '授权失败!',
                'errorCode' => 10004,
            ]);
        } else {
            $scope = $app->scope;
            $uid = $app->id;
            $values = [
                'scope' => $scope,
                'uid' => $uid
            ];
            $token = $this->saveToCache($values);
            return $token;
        }
    }

    private function saveToCache($values)
    {
        $token = self::generaToken();
        $expire_id = config('secure.token_expire');
        $result = Cache::set($token, $values, $expire_id);
        if (!$result) {
            throw new TokenException([
                'message' => '服务器缓存异常',
                'errorCode' => 10005,
            ]);
        }
        return $token;
    }
}