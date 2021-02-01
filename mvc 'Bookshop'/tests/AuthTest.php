<?php

class AuthTest extends \PHPUnit\Framework\TestCase
{
    public function testGetUser()
    {
        $_SESSION['user'] = "testGetUser";
        $this->assertEquals("testGetUser",\MyApp\Auth::getUser());
    }

    /**
     * @dataProvider initBasketProvider
     * @param $force
     * @param $basket
     * @param $expected
     */
    public function testInitBasket($force, $basket, $expected)
    {
        $_SESSION['basket'] = $basket;
        $actual = \MyApp\Auth::getBasket($force);
        $this->assertEquals($actual, $expected);
    }
    public function initBasketProvider(){
        return [
          [false, null, ['count' => 0,'goods' => []]],
          [true, null, ['count' => 0,'goods' => []]],
          [false,['count' => 1,'goods' => ['id' => 1]],['count' => 1,'goods' => ['id' => 1]]]
        ];
    }
}