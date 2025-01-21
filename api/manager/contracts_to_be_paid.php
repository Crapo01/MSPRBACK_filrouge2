<?php
//header( 'Content-Type: application/json' );
require_once'../../model/db_mysql.php';
$db = new Database();

$pdo = $db->getDbConnect();


    $query = $pdo->query(
        "SELECT c.id, c.price, SUM(b.amount) AS total_paid
        FROM Contract c 
        LEFT JOIN Billing b ON c.id = b.contract_id
        GROUP BY c.id
        HAVING SUM(b.amount) < c.price OR SUM(b.amount) IS NULL"
    );

    $result = $query->fetchAll( PDO::FETCH_ASSOC );
    if ( empty( $result ) )
    echo json_encode( [ 'message' => 'No match or fully paid' ] );
    else
    echo json_encode( $result );
