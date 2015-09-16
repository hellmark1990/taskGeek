<?php

function dd() {
    $args = func_get_args();
    $args = count($args) > 1 ? $args : $args[0];
    echo '<pre>';
    var_dump($args);
    echo '</pre>';
}
use app\components\App;

error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT ^ E_WARNING);
ini_set('display_errors', 'on');

require_once 'app/components/Autoloader.php';
spl_autoload_register('app\components\Autoloader::run');


App::create()->run();
App::create()->router->run();