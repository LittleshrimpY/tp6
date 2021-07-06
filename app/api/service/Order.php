<?php


namespace app\api\service;


use app\api\model\UserAddress as UserAddressModel;
use app\api\model\OrderProduct as OrderProductModel;
use app\api\model\Product as ProductModel;
use app\api\model\Order as OrderModel;
use app\lib\exception\AddressException;
use app\lib\exception\OrderException;
use think\Exception;
use think\facade\Db;

class Order
{
    //用户订单商品信息
    protected $orderProducts;
    //真实的商品信息（包含库存量）
    protected $dbProducts;
    //用户id
    protected $uid;

    public function place($uid, $orderProducts)
    {
        //orderProducts和dbProudcts 做对比
        $this->orderProducts = $orderProducts;
        $this->dbProducts = $this->getProductsByOder($orderProducts);
        $this->uid = $uid;
        $status = $this->getOrderStatus();
        if ($status['pass'] == false) {
            $status['order_id'] = -1;
            return $status;
        }
        //进行下单
        $orderSnap = $this->SnapOrder($status);
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true;
        return $order;
    }

    public function checkOrderStock($orderID)
    {
        $orderProducts = OrderProductModel::where('order_id', '=', $orderID)
            ->select()
            ->toArray();
        if (!$orderProducts){
            throw new OrderException([
                'message' => '订单商品异常，发起支付失败',
                'errorCode' => 80005
            ]);
        }
        $productStatus = $this->getProductsByOder($orderProducts);
        $this->orderProducts = $orderProducts;
        $this->dbProducts = $productStatus;
        $status = $this->getOrderStatus();
        return $status;
    }

    private function createOrder($snap)
    {
        Db::startTrans();
        try {
            $orderNo = $this->makeOrderNo();
            $order = new OrderModel();
            $order->order_no = $orderNo;
            $order->user_id = $this->uid;
            $order->total_price = $snap['orderPrice'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->total_count = $snap['totalCount'];
            $order->snap_items = json_encode($snap['productStatus']);
            $order->snap_address = $snap['snapAddress'];
            $order->save();

            $orderProduct = new OrderProductModel();
            $dataArray = [];
            foreach ($snap['productStatus'] as $key => $items) {
                $dataArray[$key] = ['order_id' => $order->id, 'product_id' => $items['id'], 'count' => $items['count']];
            }
            $orderProduct->saveAll($dataArray);
            Db::commit();
            return [
                'order_no' => $orderNo,
                'order_id' => $order->id,
                'create_time' => $order->create_time
            ];
        } catch (Exception $e) {
            Db::rollback();
            throw $e;
        }
    }

    private function getOrderStatus()
    {
        $status = [
            'pass' => true,
            'orderPrice' => 0,
            'totalCount' => 0,
            'productStatusArray' => []
        ];

        foreach ($this->orderProducts as $orderProduct) {
            $productStatus = $this->getProductStatus(
                $orderProduct['product_id'], $orderProduct['count'], $this->dbProducts
            );
            if (!$productStatus['haveStock']) {
                $status['pass'] = false;
            }
            $status['orderPrice'] += $productStatus['totalPrice'];
            $status['totalCount'] += $productStatus['count'];
            array_push($status['productStatusArray'], $productStatus);
        }
        return $status;
    }

    private function getProductStatus($orderPID, $orderCount, $dbProducts)
    {
        $productIndex = -1;
        $productStatus = [
            'id' => null,
            'haveStock' => false,
            'count' => 0,
            'name' => '',
            'image' => '',
            'totalPrice' => 0
        ];
        for ($i = 0; $i < count($dbProducts); $i++) {

            if ($orderPID == $dbProducts[$i]['id']) {
                $productIndex = $i;
                break;
            }
        }
        if ($productIndex == -1) {
            throw new OrderException([
                'message' => 'id为' . $orderPID . '的商品不存在,创建订单失败',
                'errorCode' => 80001
            ]);
        }
        $product = $dbProducts[$productIndex];
        $productStatus['id'] = $dbProducts[$productIndex]['id'];
        if (($product['stock'] - $orderCount) >= 0) {
            $productStatus['haveStock'] = true;
        }
        $productStatus['count'] = $orderCount;
        $productStatus['name'] = $product['name'];
        $productStatus['image'] = $product['main_img_url'];
        $productStatus['totalPrice'] = $product['price'] * $orderCount;
        return $productStatus;
    }

    private function SnapOrder($status)
    {
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'productStatus' => [],
            'snapAddress' => null,
            'snapName' => '',
            'snapImg' => '',
        ];
        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['productStatus'] = $status['productStatusArray'];
        $snap['snapAddress'] = json_encode($this->getUserAddress($this->uid));
        $snap['snapName'] = $status['productStatusArray'][0]['name'];
        $snap['snapImg'] = $status['productStatusArray'][0]['image'];

        if (count($this->orderProducts) > 1) {
            $snap['snapName'] .= '等';
        }
        return $snap;
    }

    private function getUserAddress($uid, $aid=35)
    {
        $address = UserAddressModel::where('user_id', '=', $uid)
            ->where('delete_time', '=', null)
            ->visible(
                ['name', 'mobile', 'province', 'city', 'country', 'detail']
            )->find();
        if (!$address) {
            throw new AddressException([
                'message' => '用户未定义地址',
            ]);
        }
        return $address;
    }

    private function getProductsByOder($orderProducts)
    {
        $orderPIDs = [];
        foreach ($orderProducts as $item) {
            array_push($orderPIDs, $item['product_id']);
        }
        $products = ProductModel::select($orderPIDs)
            ->visible(['id', 'price', 'stock', 'name', 'main_img_url'])
            ->toArray();
        return $products;
    }

    public static function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2021] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }
}