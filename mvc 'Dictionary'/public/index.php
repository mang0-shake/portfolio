<?php
error_reporting(E_ALL & ~E_NOTICE);
require "../vendor/autoload.php";
$config = require "../config/config.php";
\MyApp\App::instsnce()->setConfig($config);
\MyApp\App::instsnce()->run();
