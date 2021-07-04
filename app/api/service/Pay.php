<?php


namespace app\api\service;


use app\lib\enum\Status;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use app\api\model\Order as OrderModel;
use app\lib\exception\WxPayException;
use think\Exception;
use think\facade\Log;

require_once "../extend/wxpay/WxPay.Api.php";

class Pay
{
    private $orderID;
    private $orderNO;

    function __construct($orderID)
    {
        if (!$orderID) {
            throw new Exception('订单ID不能为空');
        }
        $this->orderID = $orderID;
    }

    private function checkOrder($orderID)
    {
        $order = OrderModel::where('id', '=', $orderID)
            ->find();
        if (!$order) {
            throw new OrderException([
                'message' => '订单ID不存在,发起支付失败',
            ]);
        }
        Token::checkOrderValid($order->user_id);
        $this->checkOrderStatus($order->status);
        $this->orderNO = $order->order_no;
    }

    private function checkOrderStatus($status)
    {
        switch ($status) {
            case Status::PAID:
                throw new OrderException([
                    'message' => '订单已支付,请勿重复支付',
                    'errorCode' => 80003,
                ]);
                break;
            case Status::DELIVER:
                throw new OrderException([
                    'message' => '订单已发货,请勿重复支付',
                    'errorCode' => 80004,
                ]);
                break;
            case Status::PAIDNOTSTOCK:
                throw new OrderException([
                    'message' => '订单商品库存不足,请勿重复支付',
                    'errorCode' => 80005,
                ]);
                break;
        }
    }

    /**
     * 发起支付
     * @throws OrderException
     * @throws TokenException
     */
    public function pay()
    {
        $this->checkOrder($this->orderID);
        $orderService = new Order();
        $status = $orderService->checkOrderStock($this->orderID);
        if (!$status['pass']) {
            throw new OrderException([
                'message' => '库存不足,发起支付失败',
                'errorCode' => 80002
            ]);
        }
        $wxOrder = $this->createPay($status['orderPrice']);
        $wxPayJSAPI = [];
        $wxPayJSAPI['nonceStr'] = getRandChar(32);
        $wxPayJSAPI['package'] = 'prepay_id=' . $wxOrder['prepay_id'];
        $wxPayJSAPI['paySign'] = $wxOrder['sign'];
        $wxPayJSAPI['timeStamp'] = (string)time();
        return $wxPayJSAPI;
    }

    public function receiveNotify()
    {
        $wxPayConfig = new WxPayConfig();
        $wxNotify = new WxPayNotify();
        $wxNotify->Handle($wxPayConfig);
    }

    private function createPay($totalPrice)
    {
        //获取openid
        $openID = Token::getCurrentTokenVar('openid');
        if (!$openID) {
            throw new TokenException();
        }
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNO);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice * 100);
        $wxOrderData->SetBody("果果超市");
        $wxOrderData->SetOpenid($openID);
        $wxOrder = $this->getPaySignature($wxOrderData);
        return $wxOrder;
    }

    private function getPaySignature($wxOrderData)
    {
        $wxPayConfig = new WxPayConfig();
        $wxOrder = \WxPayApi::unifiedOrder($wxPayConfig, $wxOrderData);
        if ($wxOrder['return_code'] != 'SUCCESS' ||
            $wxOrder['result_code'] != 'SUCCESS') {
            Log::record($wxOrder, 'error');
            Log::record('获取预支付订单失败!', 'error');
            throw new WxPayException();
        }
        $this->recodePerOrder($wxOrder['prepay_id']);
        return $wxOrder;
    }

    private function recodePerOrder($prepayID)
    {
        $order = OrderModel::where('id', '=', $this->orderID)->find();
        $order->prepay_id = $prepayID;
        $order->save();
    }
}