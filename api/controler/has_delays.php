<?php
require_once'../../model/manager.php';
if (isset($_GET['id'])) {
    $manager = new Manager();
    $manager->getContractWithDelays();
} else {
    echo json_encode(['message' => 'A customer ID is needed']);
}
