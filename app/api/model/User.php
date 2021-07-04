<?php


namespace app\api\model;


class User extends BaseModel
{
    protected $hidden = ['delete_time', 'update_time', 'create_time'];

    public function address(){
        return $this->hasOne('Address','user_id','id');
    }

    public static function getByOpenID($openid)
    {
        return self::where('openid', '=', $openid)->find();
    }

    public static function registerByOpenID($openid)
    {
        $data = [
            'openid' => $openid,
        ];
        return self::create($data);
    }
}