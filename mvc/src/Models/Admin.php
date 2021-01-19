<?php

namespace MyApp\Models;

use MyApp\App;

class Admin
{
    public static function group_by($key, $data)
    {
        $result = array();
        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[""][] = $val;
            }
        }
        return $result;
    }

    public static function getOrders()
    {
        $orders = App::instance()->getDb()->getLink()->query(
            "SELECT users.login, orders_goods.order_id, goods.book_name, orders_goods.price, orders_goods.count, orders.status, orders.date FROM orders
INNER JOIN orders_goods ON orders.id = order_id
INNER JOIN goods ON good_id = goods.id 
INNER JOIN users ON users.id = orders.user_id ORDER BY `date` DESC")->fetchAll(\PDO::FETCH_ASSOC);


        $new = [];
        foreach ($orders as $product) {
            $id = $product['order_id'];
            $new[$id]["order_id"] = $product['order_id'];
            $new[$id]["status"] = $product['status'];
            $new[$id]["date"] = $product['date'];
            $new[$id]["login"] = $product['login'];
            $new[$id]['goods'][] = [
                'name' => $product['book_name'],
                'price' => $product['price'],
                'count' => $product['count'],
                'sum' => $product['price']*$product['count'],
            ];
        }
        return $new;
//        return self::group_by("order_id",$orders);
    }
}