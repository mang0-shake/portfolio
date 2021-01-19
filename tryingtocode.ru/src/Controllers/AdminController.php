<?php

namespace MyApp\Controllers;

use MyApp\Auth;
use MyApp\Models\Admin;
use MyApp\Models\User;

class AdminController extends Controller
{
    public function actionIndex()
    {
        $loginId = Auth::getUser()['id'];
        if(User::isAdmin($loginId)){ // Проверка является ли данный пользователь админом
            $orders = Admin::getOrders();
            $this->renderTemplate("admin/index.twig", [
                'orders' => $orders,
        ]);
        } else {
            $this->redirect("/error");
        }
//        User::isAdmin($loginId);
//        $this->renderTemplate("admin/index.twig", [
//
//        ]);
    }
}