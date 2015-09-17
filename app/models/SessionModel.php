<?php

namespace app\models;

class SessionModel extends Model {

    protected $id;
    protected $numbers;

    private $tableName = 'session';

    public function __construct() {
        parent::__construct();
    }

    public function savedFields() {
        return [
            'numbers',
        ];
    }

    public function rules($scenario) {
        return [];
    }

    public function labels($label = null) {
        $labels = [
            'numbers' => 'Numbers',
        ];

        return $label ? $labels[$label] : $labels;
    }

    public function getId() {
        return 1;
    }

    public function setId($id) {
    }

    public function setNumbers($numbers) {
        if (@unserialize($numbers) !== false) {
            $this->numbers = $numbers;
        } else {
            $this->numbers = is_array($numbers) && count($numbers) > 0 ? serialize($numbers) : null;
        }
        return $this;
    }

    public function getNumbers() {
        return @unserialize($this->numbers) ? @unserialize($this->numbers) : [];
    }

    public function getTableName() {
        return $this->tableName;
    }
}