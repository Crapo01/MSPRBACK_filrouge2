<?php
// Controleur qui gère l'affichage de tous les utilisateurs
require "../include/header.php";
require "/xampp/htdocs/app1/model/vehicle_entity.php";
require "/xampp/htdocs/app1/model/vehicleManager.php";


$manager = new VehicleManager();




// find vehicle
if (isset($_GET['licence_plate'])){
    $result = $manager->getVehicleByPlates($_GET['licence_plate']);
    foreach($result as $r){
       echo '<pre>';
        var_dump($r);
        echo '<pre>'; 
        ?>
        <a class="btn btn-primary" href="vehicle_delete.php?id=<?= $r['id']?>">suppr</a>
        <?php
    }
    
    exit();
};



require "../include/footer.php";
?>
