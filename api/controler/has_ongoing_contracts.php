<?php
require_once'../../model/manager.php';
if (isset($_GET['id'])) {
    $manager = new Manager();
    $manager->getOngoingContracts($_GET['id']);
} else {
    echo json_encode(['message' => 'A customer ID is needed']);
}
