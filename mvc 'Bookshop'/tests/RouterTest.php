<?php

final class RouterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider filterProvider
     * @param $url
     * @param $expected
     */
    public function testFilter($url, $expected)
    {
        $actual = \MyApp\Router::filterUrl($url);
        $this->assertEquals($expected, $actual);
    }

    public function filterProvider()
    {
        return [
            ["/vdvd/dvdvd//", "vdvd/dvdvd"],
            ["/", ""],
            ["///cwcw//", "cwcw"],
            ["", ""]
        ];

    }

    /**
     * @dataProvider parseProvider
     * @param $url
     * @param $expected
     */
    public function testParse($url, $expected)
    {
        $router = new \MyApp\Router(["login" => "user/login",
            "auth" => "user/auth",
            "order" => "user/order",
            "logout" => "user/logout",
            "basket" => "user/basket",
//        "admin" => "user/admin",
            "catalog\/([0-9])+\/([0-9]+)" => "catalog/good",
            "catalog\/([0-9]+)$" => "catalog/category",
            "catalog" => "catalog/index",
            "pages\/(.*)" => "pages/index",
            "(\w+)\/(\w+)" => "<controller>/<action>",
            "(\w+)" => "<controller>/index",
            "^$" => "index/index",
            "(.*)" => "index/error",]);
        $actual = $router->parse($url);
        $this->assertEquals($expected, $actual);

    }

    public function parseProvider()
    {
        return [
            ["catalog/1/2", ['catalog', 'good', [1, 2]]],
            ["catalog", ["catalog", "index", []]],
            ["pages/vved", ["pages", "index", ['vved']]],
            ["", ["index" , "index", []]],
            ["", ["index", "index", []]],
        ];
    }
}
