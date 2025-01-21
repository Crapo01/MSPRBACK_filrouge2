<?php
// Controleur qui gère l'affichage de tous les utilisateurs
require "../include/header.php";
require "/xampp/htdocs/app1/model/vehicle_entity.php";
require "/xampp/htdocs/app1/model/vehicleManager.php";


$manager = new VehicleManager();


//add vehicle
if (isset($_POST['licence_plate'])){
    $result = $manager->addVehicle(new Vehicle(null,$_POST['licence_plate'],$_POST['informations'],$_POST['km']));
    var_dump($result);
    exit();
};


require "../include/footer.php";
