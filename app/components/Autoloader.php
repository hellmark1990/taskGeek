<?php

namespace app\components;


class Autoloader
{
    public static function run($class)
    {
        $classPath = str_replace("\\", "/", $class);
        $classPath = "$classPath.php";

        if (file_exists($classPath)) {
            require_once($classPath);
        }
    }
}