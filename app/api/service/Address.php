<?php


namespace app\api\service;


use app\api\model\Address as AddressModel;
use app\api\model\AddressDetail as AddressDetailModel;
use app\api\service\Token as TokenService;
use app\lib\exception\AddressException;

class Address
{
    public function addAddress($data)
    {
        $uid = TokenService::getCurrentUid();
        UserToken::checkUser($uid);
        $userAddress = AddressModel::where('user_id', '=', $uid)->find();
        if (!$userAddress) {
            $data['user_id'] = $uid;
            $result = AddressModel::create($data);
            if ($result->isEmpty()) {
                throw new AddressException([
                    'errorCode' => 60001
                ]);
            }
            $data['address_id'] = $result->id;
            $userAddressDetail = AddressDetailModel::create($data);
            if ($userAddressDetail->isEmpty()) {
                throw new AddressException([
                    'errorCode' => 60002,
                ]);
            }
        } else {
            $data['address_id'] = $userAddress->id;
            $userAddressDetail = AddressDetailModel::create($data);
            if ($userAddressDetail->isEmpty()) {
                throw new AddressException([
                    'errorCode' => 60002,
                ]);
            }
        }
    }

    public function updataAddress($data)
    {
        $uid = TokenService::getCurrentUid();
        UserToken::checkUser($uid);
        $userAddressDetail = AddressDetailModel::where('id', '=', $data['id'])->find();
        if ($userAddressDetail->isEmpty()) {
            throw new AddressException([
                'errorCode' => 60003,
            ]);
        }
        $userAddressDetail->save($data);
    }
}