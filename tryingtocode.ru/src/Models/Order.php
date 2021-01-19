<?php

namespace MyApp\Models;

use MyApp\App;
use MyApp\Auth;

class Order
{
    public const STATUS_NEW = 1;
    public const STATUS_PROGRESS = 2;
    public const STATUS_REJECTED = 3;
    public const STATUS_DONE = 4;

    public const TABLE_ORDERS = 'orders';
    public const TABLE_ORDERS_GOODS = 'orders_goods';

    public static function createOrder($userId, $basket)
    {
        $goodsIds = $basket['goods'];
        if (empty($goodsIds)) {
            return;
        }
        App::instance()->getDb()->getLink()->exec("INSERT INTO " . self::TABLE_ORDERS
            . " SET user_id = " . (int)$userId
            . ", status = " . self::STATUS_NEW);
        $order_id = App::instance()->getDb()->getLink()->lastInsertId();
        $goods = Catalog::getGoodsByIds(array_keys($goodsIds));
        foreach ($goods as $good) {
            App::instance()->getDb()->getLink()->exec("INSERT INTO " . self::TABLE_ORDERS_GOODS
                . " SET order_id = " . $order_id
                . ", good_id = " . $good['id']
                . ", price = " . $good['price']
                . ", `count` = " . $goodsIds[$good['id']]);
        }

    }
}