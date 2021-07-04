<?php


namespace app\api\validate;


use app\lib\exception\ParameterException;

class Order extends BaseValidate
{
    protected $rule = [
        "products" => 'require|checkProducts',
    ];

    protected $singleRule = [
        "product_id" => 'require|isPostiveInteger',
        "count" => 'require|isPostiveInteger'
    ];

    protected $singleMessage = [
        "product_id.isPostiveInteger" => 'product_id必须为正整数',
        "count.isPostiveInteger" => 'count必须为正整数',

    ];

    public function checkProducts($products)
    {
        if (empty($products)) {
            throw new ParameterException([
                'message' => '参数不能为空',
                'errorCode' => 10001,
            ]);
        }
        if (!is_array($products)) {
            throw new ParameterException([
                'message' => '参数格式必须为数组',
                'errorCode' => 10002,
            ]);
        }

        foreach ($products as $values){
            $this->checkSingleProduct($values);
        }
        return true;
    }

    public function checkSingleProduct($values){
        $baseValidate = new BaseValidate();
        $baseValidate->rule = $this->singleRule;
        $baseValidate->message = $this->singleMessage;
        $result = $baseValidate->check($values);
        if (!$result) {
            throw new ParameterException([
                'message' => $baseValidate->error,
            ]);
        }
    }
}