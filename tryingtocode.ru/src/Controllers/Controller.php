<?php

namespace MyApp\Controllers;

use MyApp\App;
use MyApp\Auth;

abstract class Controller
{
    private $twig;
    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(App::instance()->getConfig()['templates']);
        $this->twig = new \Twig\Environment($loader);
    }
    public function beforeAction(){
        return true;
    }
    public function afterAction(){

    }
    protected function renderTemplate($name, $data = []){
        $data['_sessionUser'] = Auth::getUser();
        $data['_basket'] = Auth::getBasket();
       echo $this->twig->render($name, $data); //(название шаблона, данные)
    }
    public function redirect($page){
        header("Location:$page");
    }
}