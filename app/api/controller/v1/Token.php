<?php


namespace app\api\controller\v1;

use app\api\service\UserToken;
use app\api\validate\TokenGet as TokenGetModel;

class Token
{
    public function getToken($code=''){
        (new TokenGetModel())->goCheck();
        $userToken = new UserToken($code);
        $result = $userToken->get();
        return json($result,200);
    }
}