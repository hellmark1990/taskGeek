<?php

namespace app\components;


class Session {

    protected static $instance;
    protected $sessionData = [];

    private function __construct(array $config) {
        $this->startSession();
    }

    /**
     * @param array $config
     * @return mixed
     */
    public static function create(array $config = []) {
        self::$instance = self::$instance ? self::$instance : new self($config);
        return self::$instance;
    }

    protected function startSession() {
        @session_start();
    }

    public function setData(array $data) {
        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    public function getItem($item) {
        return $_SESSION[$item] ? $_SESSION[$item] : null;
    }

    public function destroySession() {
        unset($_SESSION);
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }
        session_destroy();
    }

}