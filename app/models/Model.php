<?php

namespace app\models;

use app\components\App;
use app\components\Validation;

class Model {
    public function __construct() {

    }

    public function fromArray($data) {
        foreach ($data as $attrName => $attrValue) {
            $attrName = ucfirst($attrName);
            $setter = "set$attrName";

            $this->$setter($attrValue);
        }

        return $this;
    }

    public function toArray() {
        $modelArray = [];
        foreach ($this as $fieldName => $fieldValue) {
            $modelArray[$fieldName] = $fieldValue;
        }

        return $modelArray;
    }

    public function validate($scenario) {
        return (new Validation($this, $scenario))->run();
    }

    public function save() {
        $modelData = $this->prepareSavedData();

        if ($this->getId()) {
            $this->update($modelData);
        } else {
            $this->insert($modelData);
        }
        return $this;
    }

    protected function insert($modelData) {
        return App::create()->db->insert($this->getTableName(), $modelData);
    }

    protected function update($modelData) {
        return App::create()->db->update($this->getTableName(), ['id' => '=' . $this->getId()], $modelData);
    }

    protected function prepareSavedData() {
        $savedFields = $this->savedFields();
        $modelData = $this->toArray();
        foreach ($modelData as $fieldName => $fieldValue) {
            if (!in_array($fieldName, $savedFields)) {
                unset($modelData[$fieldName]);
            }
        }
        return $modelData;
    }

    public function findOne($whereData) {
        $item = App::create()->db->select($this->getTableName(), $whereData)->findOne();
        return $this->fromArray($item);

    }
}