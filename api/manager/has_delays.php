<?php
//header( 'Content-Type: application/json' );
require_once'../../model/db_mysql.php';
$db = new Database();

$pdo = $db->getDbConnect();

if ( isset( $_GET[ 'id' ] ) ) {
    $id =  $_GET[ 'id' ];

    $query = $pdo->query(
        "SELECT * FROM `contract` WHERE `returning_datetime` > SUBTIME(`loc_end_datetime`, '-1:0:0')"
    );

    $result = $query->fetchAll( PDO::FETCH_ASSOC );
    if ( empty( $result ) )
    echo json_encode( [ 'message' => 'No match' ] );
    else
    echo json_encode( $result );
} else {
    echo json_encode( [ 'message' => 'A customer ID is needed' ] );
}
