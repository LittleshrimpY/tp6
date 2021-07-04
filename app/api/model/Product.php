<?php


namespace app\api\model;


class Product extends BaseModel
{
    protected $hidden = ['update_time', 'delete_time', 'pivot', 'from', 'category_id', 'create_time'];


    //读取器（获取器）
    protected function getMainImgUrlAttr($value, $data): string
    {
        return $this->AddPrefix($value);
    }

    public function images(): \think\model\relation\HasMany
    {
        return $this->hasMany('ProductImage', 'product_id', 'id');
    }

    public function property()
    {
        return $this->hasMany('ProductProperty', 'product_id', 'id');
    }

    public static function getMostRecent($count): \think\Collection
    {
        return self::limit($count)
            ->order('create_time', 'desc')
            ->hidden(['update_time', 'delete_time', 'pivot', 'from', 'category_id', 'create_time', 'summary'])
            ->select();
    }

    public static function getByCategoryID($id): \think\Collection
    {
        return self::where('delete_time', '=', null)
            ->where('category_id', '=', $id)
            ->select();
    }

    public static function getByProductID($id)
    {
        return self::with(['property', 'images' => function ($query) {
            $query->with(['img'])
                ->order('order', 'asc');
        }])
            ->find($id);
    }

    public static function getHotProduct()
    {
        return self::where('sale', '>', '100')
            ->order('sale', 'desc')
            ->select();
    }
}