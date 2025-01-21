<?php
//header( 'Content-Type: application/json' );
require_once'../../model/db_mysql.php';
$db = new Database();

$pdo = $db->getDbConnect();


    $query = $pdo->query(" 
    SELECT * FROM `contract` order by `customer_id` ASC"
    );

    $result = $query->fetchAll( PDO::FETCH_ASSOC );
    if ( empty( $result ) )
    echo json_encode( [ 'message' => 'No match' ] );
    else
    echo json_encode( $result );

