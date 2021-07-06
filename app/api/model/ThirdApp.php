<?php


namespace app\api\model;


class ThirdApp extends BaseModel
{
    public static function check($ac, $se)
    {
        return self::where('app_id', '=', $ac)->where('app_secret', '=', $se)->find();
    }
}