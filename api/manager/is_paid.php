<?php
//header( 'Content-Type: application/json' );
require_once'../../model/db_mysql.php';
$db = new Database();

$pdo = $db->getDbConnect();

if ( isset( $_GET[ 'id' ] ) ) {
    $id =  $_GET[ 'id' ];

    $query = $pdo->query(
        "SELECT c.id, c.price, SUM(b.amount) AS total_paid
        FROM Contract c
        LEFT JOIN Billing b ON c.id = b.contract_id
        HAVING SUM(b.amount) >= c.price and c.id = $id"
    );

    $result = $query->fetchAll( PDO::FETCH_ASSOC );
    if ( empty( $result ) )
    echo json_encode( [ 'message' => 'No match or not fully paid' ] );
    else
    echo json_encode( [ 'message' => 'Bill is fully paid' ] );
} else {
    echo json_encode( [ 'message' => 'A customer ID is needed' ] );
}
