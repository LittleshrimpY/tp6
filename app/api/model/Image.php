<?php
declare (strict_types = 1);

namespace app\api\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Image extends BaseModel
{
    protected $hidden = ['id','update_time','delete_time','from'];
    //
    public function getUrlAttr($value,$data){
        $finalUrl = $value;
        if ($data['from']==1) {
            return $this->AddPrefix($value);
        }
        return $finalUrl;
    }
}
