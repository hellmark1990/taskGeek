<?php

namespace app\models;

class UsersModel extends Model {

    protected $id;
    protected $username;
    protected $email;
    protected $password;
    protected $repeatPassword;

    private $tableName = 'users';

    public function __construct() {
        parent::__construct();
    }

    public function savedFields() {
        return [
            'username',
            'email',
            'password',
        ];
    }

    public function rules($scenario) {
        $rules = [
            'register' => [
                ['username' => 'required|min[2]|max[255]'],
                ['email' => 'required|min[4]|max[255]|email'],
                ['password' => 'required|min[6]|max[255]'],
                ['repeatPassword' => 'required|min[6]|max[255]|sameAs[password]'],
            ],
            'login' => [
                ['email' => 'required|min[4]|max[255]|email'],
                ['password' => 'required|min[6]|max[255]'],
            ]
        ];

        return $scenario ? $rules[$scenario] : $rules;
    }

    public function labels($label = null) {
        $labels = [
            'username' => 'User Name',
            'email' => 'Email',
            'password' => 'Password',
            'repeatPassword' => 'Repeat password',
        ];

        return $label ? $labels[$label] : $labels;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        return $this->id = $id ? $id : null;
    }

    public function setUsername($username) {
        $this->username = $username ? $username : '';
    }

    public function getUsername() {
        return $this->username;
    }

    public function setEmail($email) {
        $this->email = $email ? $email : '';
    }

    public function getEmail() {
        return $this->email;
    }

    public function setPassword($password) {
        $this->password = $password ? $password : '';
    }

    public function getPassword() {
        return $this->password;
    }

    public function setRepeatPassword($repeatPassword) {
        $this->repeatPassword = $repeatPassword ? $repeatPassword : '';
    }

    public function getRepeatPassword() {
        return $this->repeatPassword;
    }

    public function getTableName() {
        return $this->tableName;
    }
}