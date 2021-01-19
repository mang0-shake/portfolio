<?php

namespace MyApp;

use MyApp\Controllers\IndexController;

class App
{
    private static $instance;
    private $config;
    private $db;

    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getDb()
    {
        return $this->db;
    }

    public function init()
    {
        if(!headers_sent()) {
            session_start();
        }
        $this->db = new DB($this->config['db']);
    }

    public function run()
    {
        [$uri] = explode('?', $_SERVER['REQUEST_URI']);
//        $controllerName = '';
//        $actionName = '';
//        $param = '';
//        [$controllerName , $actionName, $param] = explode('/', trim($uri, '/'));
        $router = new Router(App::instance()->getConfig()['routing']);
        if ($router->parse($uri) === false) {
            $controllerName = "index";
            $actionName = "error";
            $params = [];
        } else {
            [$controllerName, $actionName, $params] = $router->parse($uri);
        }

        $controllerClass = 'MyApp\Controllers\\' . ucfirst($controllerName) . 'Controller';
        $actionMethod = 'action' . ucfirst($actionName);

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass;
            if (method_exists($controller, $actionMethod)) {
                if ($controller->beforeAction()) {
                    $controller->$actionMethod($params);
                };
                $controller->afterAction();
                return;
            }
        }

        (new IndexController())->actionError();
    }

    private function __construct()
    {
    }
}
