<?php


namespace app\api\controller\v1;


use app\api\validate\Count;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\ProductMissException;
use app\api\model\Product as ProductModel;
class Product
{
    /**
     * 获取最新商品列表
     * @url product?count=1~15
     * @param int $count
     * @return \think\response\Json
     * @throws ProductMissException
     */
    public function getRecent($count = 15): \think\response\Json
    {
        (new Count())->goCheck();
        $products = ProductModel::getMostRecent($count);
        if ($products->isEmpty()){
            throw new ProductMissException();
        }
        return json($products,200);
    }

    /**
     * 通过分类id获取商品列表
     * @url product/by_category/:id
     * @param string $id
     * @return \think\response\Json
     * @throws ProductMissException
     */
    public function getAllByCategory($id = ''): \think\response\Json
    {
        (new IDMustBePostiveInt())->goCheck();

        $products = ProductModel::getByCategoryID($id);

        if ($products->isEmpty()){
            throw new ProductMissException();
        }

        return json($products,200);
    }

    /**
     * 获取商品详情
     * @url product/:id
     * @param $id
     * @return \think\response\Json
     * @throws ProductMissException
     */
    public function getProductDetail($id){
        (new IDMustBePostiveInt())->goCheck();

        $product = ProductModel::getByProductID($id);
        if (!$product){
            throw new ProductMissException();
        }
        return json($product,200);
    }

    public function getHotToProduct(){
        $product = ProductModel::getHotProduct();
        if ($product->isEmpty()){
            throw new ProductMissException();
        }
        $product = $product->toArray();
        return json($product,200);
    }
}