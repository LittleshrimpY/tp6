<?php


namespace app\api\validate;


class IDCollection extends BaseValidate
{
    protected $rule=[
      'ids' => 'require|checkIDs',
    ];

    protected $message = [
        'ids.checkIDs' => 'ids必须为全部为正整数',
    ];
    protected function checkIDs($value){
        $values = explode(',',$value);
        if (empty($values)){
            return false;
        }
        foreach ($values as $id){
            if (!$this->isPostiveInteger($id)){
                return false;
            };
        }
        return true;
    }
}