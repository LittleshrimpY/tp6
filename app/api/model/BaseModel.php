<?php
declare (strict_types = 1);

namespace app\api\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class BaseModel extends Model
{
    protected function AddPrefix($value){
        return config('setting.img_prefix').$value;
    }
}
