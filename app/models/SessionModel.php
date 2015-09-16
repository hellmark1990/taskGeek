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

    public function setNumbers(array $numbers) {
        $this->numbers = $numbers ? serialize($numbers) : null;
        return $this;
    }

    public function getNumbers() {
        return unserialize($this->numbers);
    }

    public function getTableName() {
        return $this->tableName;
    }
}