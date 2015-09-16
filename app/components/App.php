<?php

namespace app\components;


use app\components\DB\DB;

/**
 * Class App
 * @package app\components
 */
class App {

    private static $instance;

    private function __construct() {
        ComponentsContainer::create()->register();
    }

    /**
     * @return App
     */
    public static function create() {
        self::$instance = self::$instance ? self::$instance : new self();
        return self::$instance;
    }

    public function run() {

    }

    public function __get($attr) {

        if (ComponentsContainer::create()->exists($attr)) {
            return ComponentsContainer::create()->getComponents($attr);
        }
    }


}