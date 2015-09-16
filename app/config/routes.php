<?php

use app\components\Router;


Router::setRoute(['' => 'app\controllers\IndexController:index']);
Router::setRoute(['index/add[/:num]' => 'app\controllers\IndexController:add']);
Router::setRoute(['index/save' => 'app\controllers\IndexController:save']);
Router::setRoute(['index/load' => 'app\controllers\IndexController:load']);
Router::setRoute(['index/show' => 'app\controllers\IndexController:show']);