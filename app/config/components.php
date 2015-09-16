<?php

use app\components\ComponentsContainer;

ComponentsContainer::create()
    ->setComponent(['uri' => 'app\components\URI'])
    ->setComponent(['db' => app\components\DB\DB::create(
        [
            'hostname' => 'localhost',
            'username' => 'root',
            'password' => '',
            'database' => 'taskGeek',
            'driver' => 'app\components\DB\Drivers\MySql',
        ])
    ])
    ->setComponent(['session' => app\components\Session::create(
        [

        ])
    ])
    ->setComponent(['request' => 'app\components\Request'])
    ->setComponent(['template' => 'app\components\Template'])
    ->setComponent(['router' => 'app\components\Router']);

//echo '<pre>';
//var_dump(\app\components\App::create()->db->select('users', ['username' => "='qqqq'", 'AND' , 'email' =>  "='eeee'"], ['username', 'email'])->findOne()
//var_dump(\app\components\App::create()->db->update('users', ['username' => "='333'"], ['username' => '666', 'email' => '444'])
//var_dump(\app\components\App::create()->db->delete('users', ['username' => "='666'"])
//);