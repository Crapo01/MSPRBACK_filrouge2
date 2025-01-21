<?php
//header( 'Content-Type: application/json' );
require_once'../../model/db_mysql.php';
$db = new Database();

$pdo = $db->getDbConnect();

if ( isset( $_GET[ 'id' ] ) ) {
    $id =  $_GET[ 'id' ];

    $query = $pdo->query(
        "SELECT amount FROM `billing` WHERE contract_id = $id"
    );

    $result = $query->fetchAll( PDO::FETCH_ASSOC );
    if ( empty( $result ) )
    echo json_encode( [ 'message' => 'No match' ] );
    else
    echo json_encode( $result );
} else {
    echo json_encode( [ 'message' => 'A customer ID is needed' ] );
}
