<?php


namespace MyApp\Controllers;

use MyApp\Auth;
use MyApp\Models\Catalog;
use MyApp\Models\Order;
use MyApp\Models\User;

class UserController extends Controller
{
    public const ADMIN_ROLE = 1;
    public const CONTENT_ROLE = 2;

    public function actionIndex()
    {
        //если пользовател не авторизован - редирект на авторизацию
        if (!$_SESSION['user']) {
            $this->redirect("/login");
        }
//        if ($_SESSION[self::ADMIN_ROLE]) {
//            $this->renderTemplate("admin/index.twig", [
//
//            ]);
//            exit;
//        }
        $this->renderTemplate("user/index.twig", [
            'user' => $_SESSION['user'],
        ]);

    }

    public function actionLogin()
    {
        $result = null;
        if (isset($_POST['login'])) {
            if (User::check($_POST['login'], $_POST['pass'])) {
                \MyApp\Auth::iniSessionUser($_POST['login']);
                $this->redirect("/user");
            } else {
                $result = "Логин/пароль введены неверно";
//                $this->redirect("/user/login");
            }
        }
        $this->renderTemplate("login.twig", [
            'loginError' => $result,
        ]);
    }

    public function actionAuth()
    {
        $error = null;
        if (isset($_POST['auth_email'], $_POST['auth_pass'])) {
            if (!User::loginExist($_POST['auth_email'])) {
                $hash = password_hash($_POST['auth_pass'], PASSWORD_DEFAULT);
                User::signUp($_POST['auth_email'], $hash);
                $this->redirect("/user/login");
            } else {
                $error = "Пользователь существует";
            }
        }
        $this->renderTemplate("auth.twig", [
            'error' => $error,
        ]);
    }

    public function actionLogout()
    {
        Auth::unsetSessionUser();
        $this->redirect("/login");
    }

    public function actionBasket()
    {
        $basket = Auth::getBasket();
        $ids = array_keys($basket['goods']);
        // var_dump($ids);
        // exit;
        $goods = Catalog::getGoodsByIds($ids);
        $sum = 0;
        foreach ($goods as $k => $v) {
            $goods[$k]['count'] = $basket['goods'][$v['id']];
            $sum += $goods[$k]['sum'] = $goods[$k]['count'] * $v['price'];
        }
        $this->renderTemplate("user/basket.twig", [
            'goods' => $goods,
            'sum' => $sum,
        ]);
    }

    public function actionOrder()
    {
        $user = Auth::getUser();
        if (!$user) {
            $this->redirect("/login");
        } else {
            $basket = Auth::getBasket();
            Order::createOrder($user['id'], $basket);
            Auth::clearBasket();
            $this->renderTemplate("user/success.twig");
        }

    }
}