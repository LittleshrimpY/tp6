<?php


namespace app\api\controller\v1;

use app\api\service\UserToken;
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
        return json(['token'=>$result], 200);
    }

    public function verifyToken($token = '')
    {
        (new TokenVerify())->goCheck();
        $isValid = TokenService::verifyToken($token);
        return json(['isValid'=>$isValid]);
    }
}