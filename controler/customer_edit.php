<?php
// Controleur qui gère l'affichage de tous les utilisateurs
require "../include/header.php";
require "/xampp/htdocs/app1/model/customer_entity.php";
require "/xampp/htdocs/app1/model/customerManager.php";


$manager = new CustomerManager();


if (isset($_POST['fname'])){
    $result = $manager->editCustomer(new Customer($_POST['fname'],$_POST['fname'],$_POST['sname'],$_POST['address'],$_POST['permit']),$_POST['id']);
    var_dump($result);
    exit();
};

require "../include/footer.php";
?>
