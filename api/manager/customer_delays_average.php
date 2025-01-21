<?php
//header( 'Content-Type: application/json' );
require_once'../../model/db_mysql.php';
$db = new Database();

$pdo = $db->getDbConnect();


    $query = $pdo->query("       
        
    SELECT 
        c.customer_id,
        AVG(TIMESTAMPDIFF(MINUTE, c.loc_end_datetime, c.returning_datetime)) AS avg_delay_minutes
    FROM 
        Contract c
    WHERE 
        c.returning_datetime > SUBTIME(`loc_end_datetime`, '-1:0:0')
    GROUP BY 
        c.customer_id"
    );

    $result = $query->fetchAll( PDO::FETCH_ASSOC );
    if ( empty( $result ) )
    echo json_encode( [ 'message' => 'No match' ] );
    else
    echo json_encode( $result );

