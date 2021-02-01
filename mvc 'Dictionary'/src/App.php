<?php


namespace MyApp;


class App
{
    private static $instance;
    private $config;
    private $db;

    public static function instsnce()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function setConfig($config): void
    {
        $this->config = $config;
    }

    public function getDB()
    {
        return $this->db;
    }

    public function run()
    {
        $this->db = new DB($this->config['db']);
        [$uri] = explode('?', $_SERVER['REQUEST_URI']);
        [$controllerName, $actionName, $param] = explode('/', trim($uri, '/'));

        if (empty($controllerName)) {
            $controllerName = 'index';
        }
        if (empty($actionName)) {
            $actionName = 'index';
        }
        $controllerClass = 'MyApp\Controllers\\' . ucfirst($controllerName) . 'Controller';
        $actionMethod = 'action' . ucfirst($actionName);


        if (class_exists($controllerClass)) {
            $controller = new $controllerClass;
            if (method_exists($controller, $actionMethod)) {
                $controller->$actionMethod($param);
                return;
            }
        }
    }
}