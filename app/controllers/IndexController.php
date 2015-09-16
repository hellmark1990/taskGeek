<?php

namespace app\controllers;

use app\components\App;
use app\models\SessionModel;


class IndexController extends Controller {

    public function index() {
        App::create()->template
            ->setData([
                'title' => 'Home Page',
            ])
            ->render('home/index');
    }

    public function add($number = 0) {
        $numbersInSession = App::create()->session->getItem('numbers');
        $numbersInSession = $numbersInSession ? $numbersInSession : [];
        $numbersInSession[] = $number;

        App::create()->session->setData(['numbers' => $numbersInSession]);

        $numbersInSession = var_export($numbersInSession, true);
        App::create()->template
            ->setData([
                'title' => "Number: '$number' has been successfully recorded into session.",
                'numbersInSession' => $numbersInSession,
            ])
            ->render('index/add');
    }

    public function show() {
        $numbersInSession = App::create()->session->getItem('numbers');
        $numbersInSession = $numbersInSession ? $numbersInSession : [];
        $numbersInSession = var_export($numbersInSession, true);

        App::create()->template
            ->setData([
                'title' => 'Show session numbers',
                'numbersInSession' => $numbersInSession,
            ])
            ->render('index/show');
    }

    public function save() {
        $numbersInSession = App::create()->session->getItem('numbers');
        $sessionModel = (new SessionModel())
            ->setNumbers($numbersInSession)
            ->save();

        $numbersInSession = var_export($sessionModel->getNumbers(), true);
        App::create()->template
            ->setData([
                'title' => 'Session data was successfully saved.',
                'numbersInSession' => $numbersInSession,
            ])
            ->render('index/save');
    }

    public function load() {
        App::create()->template
            ->setData([
                'title' => 'Home Page',
            ])
            ->render('home/index');
    }


}