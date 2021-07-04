<?php


namespace app\api\model;


use app\api\service\Token;
use think\Model;

class Order extends Model
{
    protected $hidden = ['user_id', 'update_time', 'delete_time'];

    public function getSnapItemsAttr($value)
    {
        $snap_items = $value;
        if (!empty($value)) {
            $snap_items = json_decode($snap_items);
        }
        return $snap_items;
    }

    public function getSnapAddressAttr($value)
    {
        $snap_address = $value;
        if (!empty($value)) {
            $snap_address = json_decode($snap_address);
        }
        return $snap_address;
    }


    public static function getSummaryByUser($page, $size)
    {
        $uid = Token::getCurrentUid();
        $data = self::where('user_id', '=', $uid)
            ->order('id', 'asc')
            ->hidden(['user_id', 'update_time', 'delete_time', 'snap_items', 'snap_address', 'prepay_id'])
            ->paginate(['page' => $page, 'list_rows' => $size], true);
        if ($data->isEmpty()) {
            return [
                'data' => $data,
            ];
        }
        return [
            'data' => $data,
        ];
    }

    public static function getDetail($id)
    {
        return self::where('id', '=', $id)->find();
    }
}