<?php


namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\CategoryMissException;
use app\lib\exception\ProductMissException;

class Category
{
    /**
     * 获取所有分类
     * @url category
     * @return \think\response\Json
     * @throws CategoryMissException
     */
    public function getAllCategories(): \think\response\Json
    {
        $categories  = CategoryModel::with(['img'])
            ->select();
        if ($categories->isEmpty()){
            throw new CategoryMissException();
        }
        return json($categories,200);
    }

}