<?php


namespace app\api\model;


class ProductImage extends BaseModel
{
    protected $hidden = [
        'delete_time',
        'create_time',
        'update_time',
        'from',
        'product_id',
        'img_id'
    ];

    public function img(){
        return $this->belongsTo('image','img_id','id');
    }
}