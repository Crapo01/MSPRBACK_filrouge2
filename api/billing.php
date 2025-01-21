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
    if ( isset( $_GET[ 'id' ] )) {
        $id =  $_GET[ 'id' ];

        $query = $pdo->query(
            "SELECT * 
                  FROM Billing
                  WHERE id = $id"
        );

        $bills = $query->fetchAll( PDO::FETCH_ASSOC );
        if ( empty( $bills ) )
        echo json_encode( [ 'message' => 'No match' ] );
        else
        echo json_encode( $bills );
    } else {
        $sql = 'SELECT * FROM Billing';
        $stmt = $pdo->prepare( $sql );
        $stmt->execute();
        $result = $stmt->fetchAll( PDO::FETCH_ASSOC );
        echo json_encode( $result );
    }
}

function handlePost( $pdo, $input ) {
    if ( empty( $input[ 'contract_id' ] ) || empty( $input[ 'amount' ] ) ) {
        echo json_encode( [ 'message' => 'A field is missing' ] );
        die();
    }
    try {
        $pdo->beginTransaction();
        $query = $pdo->prepare(
            "INSERT INTO billing (`contract_id`, `amount`)
          VALUES(:contract_id, :amount)"
        );

        $query->execute( [
            'contract_id' => htmlspecialchars( $input[ 'contract_id' ] ),
            'amount' => htmlspecialchars( $input[ 'amount' ] )
        ] );
        $pdo->commit();

        echo json_encode( [ 'message' => 'bill created successfully' ] );
    } catch ( \Exception $e ) {
        $pdo->rollBack();
        echo json_encode( [ 'message' => 'An error occured',$e ] );

    }

}

function handlePut( $pdo, $input ) {
    if ( empty( $input[ 'amount' ] )) {
        echo json_encode( [ 'message' => 'A field is missing' ] );
        die();
    }
    if ( isset( $_GET[ 'id' ] ) ) {
        try {
            $pdo->beginTransaction();
            $query = $pdo->prepare(
                "UPDATE Billing
        SET 
        amount = :amount
        WHERE id = :id" );
                $query->execute( [
                    'id' => $_GET[ 'id' ],
                    'amount' => htmlspecialchars( $input[ 'amount' ] )
                ] );
                $pdo->commit();

                echo json_encode( [ 'message' => 'Bill updated successfully' ] );
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
                    'DELETE FROM billing WHERE id = :id'
                );                
                $query->execute(['id' => $_GET['id']]);
                $pdo->commit();

                echo json_encode( [ 'message' => 'Bill deleted successfully' ] );
            } catch ( \Exception $e ) {
                $pdo->rollBack();
                echo json_encode( [ 'message' => 'An error occured', $e ] );
            }

        }
    }
    ?>

