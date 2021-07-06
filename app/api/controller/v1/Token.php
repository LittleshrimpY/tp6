<?php


namespace app\api\controller\v1;

use app\api\service\AppToken;
use app\api\service\UserToken;
use app\api\validate\AppTokenGet;
use app\api\validate\TokenGet as TokenGetModel;
use app\api\validate\TokenVerify;
use app\api\service\Token as TokenService;

class Token
{
    public function getToken($code = '')
    {
        (new TokenGetModel())->goCheck();
        $userToken = new UserToken($code);
        $result = $userToken->get();
        return json(['token' => $result], 200);
    }

    public function verifyToken($token = '')
    {
        (new TokenVerify())->goCheck();
        $isValid = TokenService::verifyToken($token);
        return json(['isValid' => $isValid]);
    }

    public function getAppToken($ac = '', $se = '')
    {
        (new AppTokenGet())->goCheck();
        $app = new AppToken();
        $token = $app->get($ac, $se);
        return json([
            'token' => $token,
        ]);
    }
}