<?php
// Controleur qui gère l'affichage de tous les utilisateurs
require "../include/header.php";
require "/xampp/htdocs/app1/model/customer_entity.php";
require "/xampp/htdocs/app1/model/customerManager.php";


$manager = new CustomerManager();

// get all customers
// if (!isset($_GET['fname'])){
//     $users = $manager->getCustomers();
//     foreach($users as $u){
//         echo '<pre>';    
//         var_dump($u);
//         echo '</pre>';
//     }
// }

// find customer
if (isset($_GET['fname'])){
    $result = $manager->getCustomersByNames($_GET['fname'],$_GET['sname']);
    foreach($result as $r){
       echo '<pre>';
        var_dump($r);
        echo '<pre>'; 
        ?>
        <a class="btn btn-primary" href="customer_delete.php?id=<?= $r['id']?>">suppr</a>
        <?php
    }
    
    exit();
};



require "../include/footer.php";
?>
