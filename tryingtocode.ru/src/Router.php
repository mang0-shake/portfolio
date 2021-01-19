<?php

namespace MyApp;

class Router
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function parse($url)
    {
        $url = self::filterUrl($url);

        foreach ($this->config as $k => $v) {
            $pattern = "/" . $k . "/u";
            if (!preg_match($pattern, $url, $matched)) {
                continue;
            }
            [$controller, $action] = explode("/", $v);
            array_shift($matched);
            if ($controller === '<controller>') {
                $controller = array_shift($matched);
            }
            if ($action === '<action>') {
                $action = array_shift($matched);
            }
            return ([$controller, $action, $matched]);
//            return [$url,$pattern, $matched, $v];
        }
    }

    public static function filterUrl($url)
    {
        $urlParts = explode("/", $url);
        foreach ($urlParts as $k => $v) {
            if ($v === "") {
                unset($urlParts[$k]);
            }
        }
        return implode("/", $urlParts);
    }
}