<?php


namespace app\api\model;


class Theme extends BaseModel
{
    protected $hidden = ['delete_time', 'update_time', 'topic_img_id', 'head_img_id'];

    public function topicImg()
    {
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    public function headImg()
    {
        return $this->belongsTo('Image', 'head_img_id', 'id');
    }

    public function product()
    {
        return $this->belongsToMany('Product', 'theme_product', 'product_id', 'theme_id');
    }

    public static function getThemeByIDs($ids): \think\Collection
    {
        $ids = explode(',', $ids);
        return self::with(['topicImg', 'headImg'])->select($ids);
    }

    public static function getThemeWithProductByID($id)
    {
        return self::with(['product','topicImg','headImg'])->find($id);
    }
}