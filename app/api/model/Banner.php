<?php


namespace app\api\model;



class Banner extends BaseModel
{
    protected $hidden=['update_time','delete_time'];
    //获取关联模型
    public function items()
    {
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }

    public static function getBannerByID($id)
    {
        return self::with(['items', 'items.img'])->find($id);
    }
}