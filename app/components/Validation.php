<?php

namespace app\components;


class Validation {

    protected $object;
    protected $rules;
    protected $errorMessages = [];


    public function __construct($validatedObject, $scenario) {
        $this->setObject($validatedObject);
        $this->setRules($scenario);
    }

    protected function setObject($object) {
        if (!(is_object($object) && method_exists($object, 'rules'))) {
            throw new \Exception('Not valid object to validate.');
        }

        $this->object = $object;
    }

    protected function setRules($scenario) {
        $this->rules = $this->object->rules($scenario);

        foreach ($this->rules as $ruleKey => $rule) {
            foreach ($rule as $field => $rulesString) {
                $validators = explode('|', $rulesString);

                $rulesValidators = [];
                foreach ($validators as $validator) {
                    if (preg_match('/(\w+)\[(\w+)\]/', $validator, $matches)) {
                        $rulesValidators[] = [
                            'method' => $matches[1],
                            'args' => $matches[2],
                        ];
                    } else {
                        $rulesValidators[] = [
                            'method' => $validator,
                        ];
                    }
                }
                $this->rules[$ruleKey][$field] = $rulesValidators;
            }
        }
    }

    public function run() {
        foreach ($this->rules as $field) {
            $this->fieldValidate($field);
        }

        return $this;
    }

    protected function fieldValidate($field) {
        $fieldName = key($field);
        foreach ($field[$fieldName] as $fieldRule) {
            $validatorMethod = $fieldRule['method'];
            $validatorArgs = $fieldRule['args'] ? $fieldRule['args'] : null;
            if (!method_exists($this, $validatorMethod)) {
                throw new \Exception("Validator method: $validatorMethod not exists.");
            }

            $this->$validatorMethod($fieldName, $validatorArgs);
        }
    }

    protected function getFieldValue($field) {
        $modelFieldGetter = "get$field";

        if (!method_exists($this->object, $modelFieldGetter)) {
            $modelClass = get_class($this->object);
            throw new \Exception("Model $modelClass has not method: $modelFieldGetter().");
        }

        return $this->object->$modelFieldGetter();
    }

    protected function getFieldLabel($field) {
        if (!method_exists($this->object, 'labels')) {
            return '';
        }

        return $this->object->labels($field);
    }

    protected function required($fieldName){
        $fieldValue = $this->getFieldValue($fieldName);
        if ($fieldValue) {
            return TRUE;
        }

        $this->setError($fieldName, "Field |fieldLabel| value can not be empty.");
        return FALSE;
    }

    protected function min($fieldName, $value) {
        if (!$value) {
            $value = 0;
        }

        $fieldValue = $this->getFieldValue($fieldName);
        if (mb_strlen($fieldValue) >= $value) {
            return TRUE;
        }

        $this->setError($fieldName, "Field |fieldLabel| length must be more than $value.");
        return FALSE;
    }

    protected function max($fieldName, $value) {
        if (!$value) {
            return TRUE;
        }

        $fieldValue = $this->getFieldValue($fieldName);
        if (mb_strlen($fieldValue) <= $value) {
            return TRUE;
        }

        $this->setError($fieldName, "Field |fieldLabel| length can not be more than $value.");
        return FALSE;
    }

    protected function email($fieldName) {
        $fieldValue = $this->getFieldValue($fieldName);
        if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $fieldValue)) {
            $fieldValue = $fieldValue ? ": $fieldValue" : '';
            $this->setError($fieldName, "Field |fieldLabel| contains not valid email$fieldValue.");
        }

        return TRUE;
    }

    protected function sameAs($fieldName, $value) {
        if (!$value) {
            return TRUE;
        }

        $fieldValue = $this->getFieldValue($fieldName);
        $fieldSameValue = $this->getFieldValue($value);
        $fieldSameLabel = $this->getFieldLabel($value);

        if ($fieldValue !== $fieldSameValue) {
            $this->setError($fieldName, "Field |fieldLabel| value must be the same with field $fieldSameLabel.");
        }
        return TRUE;
    }

    protected function setError($fieldName, $message = '') {
        $message = $this->setMessageFieldLabel($fieldName, $message);
        $this->errorMessages[$fieldName][] = [
            'message' => $message
        ];
    }

    protected function setMessageFieldLabel($field, $message = '') {
        if ($message) {
            return str_replace('|fieldLabel|', $this->getFieldLabel($field), $message);
        }

        return '';
    }

    public function getErrors() {
        return $this->errorMessages;
    }

}