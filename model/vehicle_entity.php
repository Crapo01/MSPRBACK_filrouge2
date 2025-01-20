<?php
class Vehicle {
    private $id;
    private $licence_plate;
    private $informations;
    private $km;
    

    // Constructeur pour initialiser les propriétés du véhicule
    public function __construct($id = null, $licence_plate = null, $informations = null, $km = null) {
        
        $this->id = $id;
        $this->licence_plate = $licence_plate;
        $this->informations = $informations;
        $this->km = $km;
    }

    // Méthodes pour récupérer et définir les propriétés
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getLicencePlate() {
        return $this->licence_plate;
    }

    public function setLicencePlate($licence_plate) {
        $this->licence_plate = $licence_plate;
    }

    public function getInformations() {
        return $this->informations;
    }

    public function setInformations($informations) {
        $this->informations = $informations;
    }

    public function getKm() {
        return $this->km;
    }

    public function setKm($km) {
        $this->km = $km;
    }
}