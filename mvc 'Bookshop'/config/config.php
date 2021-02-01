<?php
return [
    "db" => [
        'dsn' => 'mysql:dbname=u1023099_my_shop;host=127.0.0.1;charset=utf8',
        'user' => 'u1023099_root',
        'pwd' => '545286yfheR',
    ],
    "templates" => __DIR__."/../templates",
    "routing" => [
        "login" => "user/login",
        "auth" => "user/auth",
        "order" => "user/order",
        "logout" => "user/logout",
        "basket" => "user/basket",
        "catalog\/([0-9])+\/([0-9]+)" => "catalog/good",
        "catalog\/([0-9]+)$" => "catalog/category",
        "catalog" => "catalog/index",
        "pages\/(.*)" => "pages/index",
        "(\w+)\/(\w+)" => "<controller>/<action>",
        "(\w+)" => "<controller>/index",
        "^$" => "index/index",
        "(.*)" => "index/error",
    ]
];
