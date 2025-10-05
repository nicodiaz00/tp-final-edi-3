<?php

require_once 'Person.php';

class User extends Person {
    private $email;
    private $password;
    private $rol;

    public function __construct($id, $name, $surname, $idCard, $email, $password, $rol) {
        parent::__construct($id, $name, $surname, $idCard);
        $this->email = $email;
        $this->password = $password;
        $this->rol = $rol;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }
    public function getRol() {
        return $this->rol;
    }
    public function setRol($rol) {
        $this->rol = $rol;
    }

    public function __toString(): string {
        return parent::__toString() . ' [Email: ' . $this->email . ', Role: ' . $this->rol . ']';
    }

}   