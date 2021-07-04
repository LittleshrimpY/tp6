<?php


namespace app\api\model;


class Category extends BaseModel
{
    protected $hidden=['delete_time','update_time','category'];

    public function img(){
        return $this->belongsTo('image','topic_img_id','id');
    }

}