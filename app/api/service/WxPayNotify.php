<?php


namespace app\api\service;

use app\api\model\Order as OrderModel;
use app\api\model\Product;
use app\lib\enum\Status;
use think\Exception;
use think\facade\Db;
use think\facade\Log;

class WxPayNotify extends \WxPayNotify
{
    public function NotifyProcess($objData, $config, &$msg)
    {
        if ($objData['result_code'] === "SUCCESS") {
            $orderON = $objData['out_trade_no'];
            Db::startTrans();
            try {
                $order = OrderModel::where("order_on", '=', $orderON)
                    ->lock(true)
                    ->find();
                if ($order['status'] == 1) {
                    $stockStatus = (new Order())->checkOrderStock($order['id']);
                    if ($stockStatus['pass']) {
                        $this->updateOrderStatus($order);
                        $this->reduceStock($stockStatus['productStatusArray']);
                    } else {
                        $this->updateOrderStatus($order, false);
                    }
                }
                Db::commit();
                return true;
            } catch
            (Exception $e) {
                Db::rollback();
                Log::error($e);
                return false;
            }
        } else {
            return true;
        }
        //TODO 用户基础该类之后需要重写该方法，成功的时候返回true，失败返回false
    }

    private function updateOrderStatus(&$order, $tab = true)
    {
        $order->status = $tab ? Status::PAID
            : Status::PAIDNOTSTOCK;
        $order->save();
    }

    private function reduceStock($products)
    {
        foreach ($products as $item) {
            $product = Product::where('id', '=', $item['id'])->find();
            $product->stock = ($products->stock) - $item['count'];
            $product->save();
        }
    }
}