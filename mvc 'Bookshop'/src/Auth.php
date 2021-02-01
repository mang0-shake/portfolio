<?php


namespace MyApp;


use MyApp\Models\User;

class Auth
{
    public static function iniSessionUser($login)
    {
        $_SESSION['user'] = User::loginExist($login);
    }

    public static function unsetSessionUser()
    {
        $_SESSION['user'] = null;
        self::clearBasket();
    }

    public static function getUser()
    {
            return $_SESSION['user'];
    }


    //basket
    public static function getBasket()
       {
        self::initBasket();
        return $_SESSION['basket'];
    }

    public static function addToBasket($id)
    {
        self::initBasket();
        $_SESSION['basket']['count']++;
        $_SESSION['basket']['goods'][$id]++;
    }
    public static function removeFromBasket($id)
    {
        self::initBasket();
        $_SESSION['basket']['count']--;
        $_SESSION['basket']['goods'][$id]--;
        if($_SESSION['basket']['goods'][$id] == 0){
            unset($_SESSION['basket']['goods'][$id]);
        }
    }

    public static function clearBasket()
    {
        self::initBasket(true);
    }

    public static function initBasket($force = false)
    {
        if ($force || empty($_SESSION['basket'])) {
            $_SESSION['basket'] = [
                'count' => 0,
                'goods' => [],
            ];
        }
    }
//    public static function iniSessionAdmin($userRole)
//    {
//        $_SESSION['admin'] = $userRole;
//    }
//    public static function unsetSessionAdmin()
//    {
//        $_SESSION['admin'] = null;
//    }
}
