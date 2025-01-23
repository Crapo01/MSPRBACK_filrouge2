<?php
require_once'../../model/manager.php';

if (isset($_GET['lowDate']) && isset($_GET['highDate'])) {
    $lowDate = $_GET['lowDate'];
    $highDate = $_GET['highDate'];
    

    $manager = new Manager();
    $manager->getDelaysByDateRange($lowDate, $highDate);
} else {
    echo json_encode(['message' => 'A date range is needed']);
}
die();
?>

