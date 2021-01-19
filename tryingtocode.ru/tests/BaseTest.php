<?php

abstract class BaseTest extends \PHPUnit\Framework\TestCase
{
    public static function setUpBeforeClass(): void
    {
        $config = require __DIR__ . "/../config/config.php";
        \MyApp\App::instance()->setConfig($config);
        \MyApp\App::instance()->init();
        \MyApp\App::instance()->run();
    }
}