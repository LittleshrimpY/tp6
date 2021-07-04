<?php


namespace app\api\controller\v1;


use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\BannerMissException;
use app\api\model\Banner as BannerModel;

class Banner
{
    /**
     * 获取指定id的banner
     * @url banner/:id
     * @http get
     * @id banner的id号
     * @param $id
     * @return \think\response\Json
     */
    public function getBanner($id)
    {
        //AOP面向切面编程
        (new IDMustBePostiveInt())->goCheck();

        $banner = BannerModel::getBannerByID($id);

        if (!$banner){
            throw new BannerMissException();
        }else{
            return json($banner,200);
        }
    }

    public function getInfo(){
        phpinfo();
    }
}