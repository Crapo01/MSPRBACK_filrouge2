<?php
// Controleur qui gère l'affichage de tous les utilisateurs
require "../include/header.php";
require "/xampp/htdocs/app1/model/customer_entity.php";
require "/xampp/htdocs/app1/model/customerManager.php";


$manager = new CustomerManager();


//add customer
if (isset($_POST['fname'])&&!isset($_GET['edit'])){
    $result = $manager->addCustomer(new Customer(null,$_POST['fname'],$_POST['sname'],$_POST['address'],$_POST['permit']));
    var_dump($result);
    exit();
};


require "../include/footer.php";
