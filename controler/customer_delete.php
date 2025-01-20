<?php
// Controleur qui gère l'affichage de tous les utilisateurs
require "../include/header.php";
require "/xampp/htdocs/app1/model/customer_entity.php";
require "/xampp/htdocs/app1/model/customerManager.php";


$manager = new CustomerManager();


//delete customer
if ($_GET){
    $id=$_GET['id'];
    echo $id;
    $result = $manager->deleteCustomer($id);
    var_dump($result);
    exit();
};


require "../include/footer.php";
