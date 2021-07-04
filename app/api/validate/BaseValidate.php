<?php


namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\facade\Request;
use think\Validate;

class BaseValidate extends Validate
{
    /**
     * 检测数据是否正确
     * @throws Exception
     */
    public function goCheck()
    {
        //获取参数
        $params = Request::param();

        //进行检测
        $result = $this->check($params);
        if (!$result) {
            throw new ParameterException([
                'message' => $this->error,
            ]);
        } else {
            return true;
        }
    }

    public function getDataByRule($arrays){
        if (array_key_exists('user_id',$arrays)|
        array_key_exists('uid',$arrays)){
            throw new ParameterException([
                'message' => '参数中包含非法参数user_id或uid',
            ]);
        }
        $newArrays = [];
        foreach($this->rule as $key => $value){
            $newArrays[$key] = $arrays[$key];
        }
        return $newArrays;
    }

    /**
     * 判断参数是否为正整数
     * @param $value
     * @param string $rule
     * @param string $data
     * @param string $field
     * @return bool|string
     */
    public function isPostiveInteger($value,$rule='',$data='',$field=''){
        if (is_numeric($value)&&is_int($value+0)&&($value+0)>0){
            return true;
        }else{
            return false;
        }
    }

    public function isNotEmpty($value){
        if (empty($value)){
            return false;
        }
        return true;
    }
}