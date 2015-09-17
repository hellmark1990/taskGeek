<?php

namespace app\classes;

use app\models\SessionModel;
use app\components\App;

class SessionNumbersManager {

    public function __construct() {

    }

    public function getNumbers() {
        $numbersInSession = App::create()->session->getItem('numbers');
        return $numbersInSession ? $numbersInSession : [];
    }

    public function setNumber($number = 0) {
        $numbersInSession = $this->getNumbers();
        $numbersInSession[] = $number;
        $this->setNumbers($numbersInSession);
    }

    public function saveToDB() {
        $numbersInSession = $this->getNumbers();

        return (new SessionModel())
            ->setNumbers($numbersInSession)
            ->save();
    }

    public function loadFromDB() {
        $sessionModel = new SessionModel();
        $sessionModel = $sessionModel->findOne(['id' => '=' . $sessionModel->getId()]);
        $this->setNumbers($sessionModel->getNumbers());
    }

    public function getNumbersReadable() {
        return var_export($this->getNumbers(), true);
    }

    protected function setNumbers($numbers) {
        $numbers = $numbers ? $numbers : [];
        App::create()->session->setData(['numbers' => $numbers]);
        return $this;
    }
}