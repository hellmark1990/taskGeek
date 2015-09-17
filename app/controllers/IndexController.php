<?php

namespace app\controllers;

use app\components\App;
use app\classes\SessionNumbersManager;

class IndexController extends Controller {

    /**
     * @var SessionNumbersManager
     */
    protected $sessionNumbersManager;

    public function __construct() {
        parent::_construct();

        $this->sessionNumbersManager = new SessionNumbersManager();
    }

    public function index() {
        App::create()->template
            ->setData([
                'title' => 'Home Page',
            ])
            ->render('index/home');
    }

    public function add($number = 0) {
        $number = App::create()->request->post('number') ? (int)App::create()->request->post('number') : $number;
        $this->sessionNumbersManager->setNumber($number);

        $numbersInSession = $this->sessionNumbersManager->getNumbersReadable();
        App::create()->template
            ->setData([
                'title' => "Number: '$number' has been successfully recorded into session.",
                'numbersInSession' => $numbersInSession,
            ])
            ->render('index/add');
    }

    public function show() {
        $numbersInSession = $this->sessionNumbersManager->getNumbersReadable();

        App::create()->template
            ->setData([
                'title' => 'Show session numbers',
                'numbersInSession' => $numbersInSession,
            ])
            ->render('index/show');
    }

    public function save() {
        $this->sessionNumbersManager->saveToDB();

        $numbersInSession = $this->sessionNumbersManager->getNumbersReadable();
        App::create()->template
            ->setData([
                'title' => 'Session data was successfully saved.',
                'numbersInSession' => $numbersInSession,
            ])
            ->render('index/save');
    }

    public function load() {
        $this->sessionNumbersManager->loadFromDB();

        $numbersInSession = $this->sessionNumbersManager->getNumbersReadable();
        App::create()->template
            ->setData([
                'title' => 'Home Page',
                'numbersInSession' => $numbersInSession,
            ])
            ->render('index/load');
    }


}