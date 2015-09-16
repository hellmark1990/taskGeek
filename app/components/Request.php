<?php

namespace app\components;


class Request {

    public function __construct() {
        $this->proccessData();
    }

    public function get($key = null) {
        if ($key === null) {
            return $_GET;
        }

        return $_GET[$key] ? $_GET[$key] : null;
    }

    public function post($key = null) {
        if ($key === null) {
            return $_POST;
        }

        return $_POST[$key] ? $_POST[$key] : null;
    }

    protected function proccessData() {
        $this->processPost();
        $this->processGet();
    }

    protected function processPost() {
        if (is_array($_POST) AND count($_POST) > 0) {
            foreach ($_POST as $key => $value) {

                if (!preg_match("/^[a-z0-9:_\/-]+$/i", $key)) {
                    unset($_POST[$key]);
                    continue;
                }

                $_POST[$key] = $this->xss_clean($value);
            }
        }
    }

    protected function processGet() {
        if (is_array($_GET) AND count($_GET) > 0) {
            foreach ($_GET as $key => $value) {

                if (!preg_match("/^[a-z0-9:_\/-]+$/i", $key)) {
                    unset($_GET[$key]);
                    continue;
                }

                $_GET[$key] = $this->xss_clean($value);
            }
        }
    }

    protected function xss_clean($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $this->xss_clean($value);
            }
        }

        $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do {
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        } while ($old_data !== $data);

        return $data;
    }

}