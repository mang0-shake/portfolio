<?php
error_reporting(E_ALL & ~E_NOTICE);
require "../vendor/autoload.php";
$config = require "../config/config.php";
\MyApp\App::instance()->setConfig($config);
\MyApp\App::instance()->init();
\MyApp\App::instance()->run();