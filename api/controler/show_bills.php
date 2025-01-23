<?php
require_once'../../model/manager.php';
if (isset($_GET['id'])) {
    $manager = new Manager();
    $manager->showBillsByContractId($_GET['id']);
} else {
    echo json_encode(['message' => 'A contract ID is needed']);
}