<?php
declare (strict_types = 1);

namespace app\api\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class BannerItem extends BaseModel
{
    protected $hidden=['id','img_id','banner_id','update_time','delete_time'];

    public function img(){
        return $this->belongsTo('Image','img_id','id');
    }
}