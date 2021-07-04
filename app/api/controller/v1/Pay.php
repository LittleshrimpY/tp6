<?php


namespace app\api\controller\v1;


use app\api\validate\IDMustBePostiveInt;
use app\BaseController;
use app\middleware\Check;
use app\api\service\Pay as PayService;
class Pay extends BaseController
{
    protected $middleware = [Check::class];

    public function pay($id){
        (new IDMustBePostiveInt())->goCheck();
        $pay = new PayService($id);
        $wxOrder = $pay->pay();
        return json($wxOrder);
    }

    public function receiveNotify(){
        $notify = new PayService(1);
        $notify->receiveNotify();
    }
}