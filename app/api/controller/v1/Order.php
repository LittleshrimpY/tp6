<?php


namespace app\api\controller\v1;


use app\api\validate\IDMustBePostiveInt;
use app\api\validate\PagingParameter;
use app\lib\exception\OrderException;
use app\middleware\Check;
use app\api\validate\Order as OrderValidate;
use app\api\service\Order as OrderService;
use app\api\service\Token as TokenService;
use app\api\model\Order as OrderModel;

class Order
{
    protected $middleware = [Check::class];

    public function placeOrder()
    {
        (new OrderValidate())->goCheck();
        $uid = TokenService::getCurrentUid();
        $orderProducts = input('post.products/a');
        $order = (new OrderService)->place($uid, $orderProducts);
        return json($order, 200);
    }

    public function getOrderByUser($page = 1, $size = 15)
    {
        (new PagingParameter())->goCheck();
        $data = OrderModel::getSummaryByUser($page, $size);
        return json($data,200);
    }

    public function getAllOrder($page = 1, $size = 15)
    {
        (new PagingParameter())->goCheck();
        $data = OrderModel::getSummaryByPage($page, $size);
        return json($data,200);
    }

    public function getDetail($id='')
    {
        (new IDMustBePostiveInt())->goCheck();
        $data = OrderModel::getDetail($id);
        if (!$data){
            throw new OrderException();
        }
        return json($data,200);
    }
}