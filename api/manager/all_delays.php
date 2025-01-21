<?php
//header( 'Content-Type: application/json' );
require_once'../../model/db_mysql.php';
$db = new Database();

$pdo = $db->getDbConnect();

if ( isset( $_GET[ 'lowDate' ] ) && isset( $_GET[ 'highDate' ] ) ) {
    $lowDate =  $_GET[ 'lowDate' ];
    $highDate =  $_GET[ 'highDate' ];

    $query = $pdo->query(
        "SELECT * FROM `contract` WHERE `returning_datetime` > SUBTIME(`loc_end_datetime`, '-1:0:0') AND `returning_datetime`> $lowDate AND `returning_datetime`< $highDate"
    );

    $result = $query->fetchAll( PDO::FETCH_ASSOC );
    if ( empty( $result ) )
    echo json_encode( [ 'message' => 'No match' ] );
    else
    echo json_encode( $result );
} else {
    echo json_encode( [ 'message' => 'A customer ID is needed' ] );
}
