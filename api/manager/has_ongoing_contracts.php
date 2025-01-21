<?php
//header( 'Content-Type: application/json' );
require_once'../../model/db_mysql.php';
$db = new Database();

$pdo = $db->getDbConnect();

if ( isset( $_GET[ 'id' ] ) ) {
    $id =  $_GET[ 'id' ];

    $query = $pdo->query(
        "SELECT * 
              FROM Contract
              WHERE customer_id = $id AND returning_datetime IS NULL
    ");

    $result = $query->fetchAll( PDO::FETCH_ASSOC );
    if ( empty( $result ) )
    echo json_encode( [ 'message' => 'No match' ] );
    else
    echo json_encode( $result );
} else {
    echo json_encode( [ 'message' => 'A customer ID is needed' ] );
}
