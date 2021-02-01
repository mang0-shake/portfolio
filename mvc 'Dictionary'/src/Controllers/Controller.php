<?php

namespace MyApp\Controllers;

use MyApp\App;
use MyApp\Auth;

class Controller
{
    private $twig;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(App::instsnce()->getConfig()['templates']);
        $this->twig = new \Twig\Environment($loader);
    }

    protected function renderTemplate($name, $data = [])
    {
        echo $this->twig->render($name, $data); //(название шаблона, данные)
    }
}