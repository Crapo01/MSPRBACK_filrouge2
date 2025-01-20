<?php
// Controleur qui gère l'affichage de tous les utilisateurs
require "../include/header.php";
require "/xampp/htdocs/app1/model/vehicle_entity.php";
require "/xampp/htdocs/app1/model/vehicleManager.php";


$manager = new VehicleManager();


//delete vehicle
if ($_GET){
    $id=$_GET['id'];
    echo $id;
    $result = $manager->deleteVehicle($id);
    var_dump($result);
    exit();
};


require "../include/footer.php";
