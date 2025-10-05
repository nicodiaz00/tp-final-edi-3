<?php

class Person {
    private $id;
    private $name;
    private $surname;
    private $idCard;

    public function __construct($id, $name, $surname, $idCard) {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->idCard = $idCard;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }
    public function setName($name) {
        $this->name = $name;
    }
    public function setSurname($surname) {
        $this->surname = $surname;
    }
    public function setIdCard($idCard) {
        $this->idCard = $idCard;
    }

    public function getSurname() {
        return $this->surname;
    }

    public function getIdCard() {
        return $this->idCard;
    }

    public function __toString(): string {
        return $this->id . ' ' . $this->name . ' ' . $this->surname . ' (DNI: ' . $this->idCard . ')';
    }
}
