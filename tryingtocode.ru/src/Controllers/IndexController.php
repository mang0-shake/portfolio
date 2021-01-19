<?php
namespace MyApp\Controllers;

use MyApp\App;
use MyApp\Router;

class IndexController extends Controller
{
    public function actionIndex()
    {
//        $tests = [
//            '/sgsfsg//',
//            'vdvd/dvd',
//            'catalog//97',
//            'catalog/45/4333',
//            '2323',
//            'login',
//            '<sveta>/<action>'
//
//        ];
//        $res = [];
//        $router = new Router(App::instance()->getConfig()['routing']);
//        foreach ($tests as $test) {
//            $res[$test] = $router->check($test);
//        }
//        print_r($res);
//        exit();
        $this->renderTemplate('index.twig');
    }

    public function actionError()
    {
        $this->renderTemplate('error.twig');
    }
}