<?php
class Customer {
    // Définir les propriétés privées
    private $id;
    private $first_name;
    private $second_name;
    private $address;
    private $permit_number;

    // Constructeur pour initialiser un customer
    public function __construct($id, $first_name, $second_name, $address, $permit_number) {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->second_name = $second_name;
        $this->address = $address;
        $this->permit_number = $permit_number;
    }

    // Méthodes d'accès (Getters et Setters)

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getFirstName() {
        return $this->first_name;
    }

    public function getSecondName() {
        return $this->second_name;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getPermitNumber() {
        return $this->permit_number;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setFirstName($first_name) {
        $this->first_name = $first_name;
    }

    public function setSecondName($second_name) {
        $this->second_name = $second_name;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function setPermitNumber($permit_number) {
        $this->permit_number = $permit_number;
    }
    
}
?>
