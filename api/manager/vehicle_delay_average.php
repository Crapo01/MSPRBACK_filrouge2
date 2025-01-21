<?php
//header( 'Content-Type: application/json' );
require_once'../../model/db_mysql.php';
$db = new Database();

$pdo = $db->getDbConnect();

if ( isset( $_GET[ 'id' ] ) ) {
    $id =  $_GET[ 'id' ];

    $query = $pdo->query(
        "SELECT c.vehicle_id, AVG(TIMESTAMPDIFF(MINUTE, c.loc_end_datetime, c.returning_datetime)) AS avg_delay_minutes FROM `contract` as c WHERE `vehicle_id`=$id"
    );

    $result = $query->fetchAll( PDO::FETCH_ASSOC );
    if ( empty( $result ) )
    echo json_encode( [ 'message' => 'No match' ] );
    else
    echo json_encode( $result );
} else {
    echo json_encode( [ 'message' => 'A customer ID is needed' ] );
}
