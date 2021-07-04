<?php


namespace app\api\model;


use app\api\service\Token;
use app\lib\exception\AddressException;

class UserAddress extends BaseModel
{

    protected $hidden = ['delete_time', 'update_time', 'user_id'];

    public static function getAddressByUser($uid)
    {
        return self::where('user_id', '=', $uid)->select()->toArray();
    }

    public function addAddress($data)
    {
        return self::create($data);
    }

    public function upAddress($data)
    {
        $addressInfo = self::where('id', '=', $data['id'])->find();
        $addressInfo->save($data);
    }
}