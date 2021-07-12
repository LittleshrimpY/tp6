<?php


namespace app\api\controller\v1;


use app\api\service\Token as TokenService;
use app\api\validate\AddressNew;
use app\api\validate\AddressUp;
use app\lib\exception\AddressException;
use app\lib\exception\SuccessMessage;
use app\api\model\UserAddress as UserAddressModel;
use app\middleware\Check;
use app\middleware\CheckApiSign;

class Address
{
    protected $middleware = [CheckApiSign::class,Check::class];

    public function addAddress()
    {
        $uid = TokenService::getCurrentUid();
        $addressNew = new AddressNew();
        $addressNew->goCheck();
        $arrays = input('post.');
        $data = $addressNew->getDataByRule($arrays);
        $data['user_id'] = $uid;

        $res = (new UserAddressModel())->addAddress($data);

        if ($res->isEmpty()) {
            throw new AddressException();
        }

        throw new SuccessMessage([
            'code' => 201,
        ]);
    }

    public function upAddress()
    {
        $addressUp = new AddressUp();
        $addressUp->goCheck();
        $arrays = input('post.');
        $data = $addressUp->getDataByRule($arrays);

        (new UserAddressModel())->upAddress($data);

        throw new SuccessMessage([
            'code' => 201,
        ]);
    }

    public function getAddress()
    {
        $uid = TokenService::getCurrentUid();
        $data = UserAddressModel::getAddressByUser($uid);
        if (!$data) {
            throw new AddressException([
                'code' => 404,
                'message' => 'Address is null',
                'errorCode' => 60001,
            ]);
        }
        return json($data, 200);
    }
}