<?php

namespace app\components\DB;

use app\components\App;

class DB {

    protected static $instance;
    protected $config = [];
    public $driver;

    private function __construct(array $config) {
        $this->setConfig($config);
        $this->setDriver();
    }

    /**
     * @param array $config
     * @return mixed
     */
    public static function create(array $config = []) {
        self::$instance = self::$instance ? self::$instance : new self($config);
        return self::$instance->driver;
    }

    protected function setConfig(array $config = []) {
        if (!$config) {
            throw new \Exception('DB config can not be empty');
        }

        $this->config = $config;
    }

    public function getConfig() {
        return $this->config;
    }

    public function getDriver() {
        return $this->driver;
    }

    protected function setDriver() {
        $config = $this->getConfig();
        $driver = $config['driver'];

        if (class_exists($driver)) {
            $this->driver = new $driver($config);
        }
    }


}