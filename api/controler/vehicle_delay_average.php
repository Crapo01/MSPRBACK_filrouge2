<?php
require_once'../../model/manager.php';
if (isset($_GET['id'])) {
    $manager = new Manager();
    $manager->getAvgDelayByVehicleId($_GET['id']);
} else {
    echo json_encode(['message' => 'A vehicle ID is needed']);
}