<?php
//header( 'Content-Type: application/json' );
require_once'../model/db_mysql.php';
$db = new Database();

$pdo = $db->getDbConnect();

$method = $_SERVER[ 'REQUEST_METHOD' ];
$input = json_decode( file_get_contents( 'php://input' ), true );

switch ( $method ) {
    case 'GET':
    handleGet( $pdo );
    break;
    case 'POST':
    handlePost( $pdo, $input );
    break;
    case 'PUT':
    handlePut( $pdo, $input );
    break;
    case 'DELETE':
    handleDelete( $pdo );
    break;
    default:
    echo json_encode( [ 'message' => 'Invalid request method' ] );
    break;
}

function handleGet( $pdo ) {
    if ( isset( $_GET[ 'plate' ] )) {
        $plate =  $_GET[ 'plate' ];

        $query = $pdo->query(
            "SELECT * 
                  FROM Vehicle
                  WHERE licence_plate = $plate"
        );

        $vehicles = $query->fetchAll( PDO::FETCH_ASSOC );
        if ( empty( $vehicles ) )
        echo json_encode( [ 'message' => 'No match' ] );
        else
        echo json_encode( $vehicles );
    } else {
        $sql = 'SELECT * FROM vehicle';
        $stmt = $pdo->prepare( $sql );
        $stmt->execute();
        $result = $stmt->fetchAll( PDO::FETCH_ASSOC );
        echo json_encode( $result );
    }
}

function handlePost( $pdo, $input ) {
    if ( empty( $input['1'] ) || empty( $input['2'] ) || empty( $input['3'] )) {
        echo json_encode( [ 'message' => 'A field is missing' ] );
        die();
     }
    try {
        $pdo->beginTransaction();
        $query = $pdo->prepare(
            "INSERT INTO vehicle (`licence_plate`,`informations`,`km`)
          VALUES(:a,:b,:c)"
        );

        $query->execute( [
            'a' => $input['1'],
            'b' => $input['2'],
            'c'=> $input['3']            
        ] );
        $pdo->commit();

        echo json_encode( [ 'message' => 'vehicle created successfully' ] );
    } catch ( \Exception $e ) {
        $pdo->rollBack();
        echo json_encode( [ 'message' => 'An error occured',$e ] );

    }

}

function handlePut( $pdo, $input ) {
    if ( empty( $input['1'] ) || empty( $input['2'] ) || empty( $input['3'] )) {
        echo json_encode( [ 'message' => 'A field is missing' ] );
        die();
     }
    if ( isset( $_GET[ 'id' ] ) ) {
        try {
            $pdo->beginTransaction();
            $query = $pdo->prepare(
                "UPDATE Vehicle
        SET 
        licence_plate = :a,
        informations = :b,
        km = :c

        WHERE id = :id" );
                $query->execute( [
                    'id' => $_GET[ 'id' ],
                    'a' => $input['1'],
                    'b' => $input['2'],
                    'c'=> $input['3']
                ] );
                $pdo->commit();

                echo json_encode( [ 'message' => 'Vehicle updated successfully' ] );
            } catch ( \Exception $e ) {
                $pdo->rollBack();
                echo json_encode( [ 'message' => 'An error occured', $e ] );

            }
        }

    }

    function handleDelete( $pdo ) {
        if ( isset( $_GET['id'] ) ) {            
            try {
                $pdo->beginTransaction();
                $query = $pdo->prepare(
                    'DELETE FROM Vehicle WHERE id = :id'
                );                
                $query->execute(['id' => $_GET['id']]);
                $pdo->commit();

                echo json_encode( [ 'message' => 'Vehicle deleted successfully' ] );
            } catch ( \Exception $e ) {
                $pdo->rollBack();
                echo json_encode( [ 'message' => 'An error occured', $e ] );
            }

        }
    }
    ?>

