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
    if ( isset( $_GET[ 'first_name' ] ) && isset( $_GET[ 'second_name' ] ) ) {
        $f =  $_GET[ 'first_name' ];
        $s =  $_GET[ 'second_name' ];

        $query = $pdo->query(
            "SELECT * 
                  FROM Customer
                  WHERE LOWER(first_name) LIKE LOWER($f)
                  AND LOWER(second_name) LIKE LOWER($s)"
        );

        $customers = $query->fetchAll( PDO::FETCH_ASSOC );
        if ( empty( $customers ) )
        echo json_encode( [ 'message' => 'No match' ] );
        else
        echo json_encode( $customers );
    } else {
        $sql = 'SELECT * FROM Customer';
        $stmt = $pdo->prepare( $sql );
        $stmt->execute();
        $result = $stmt->fetchAll( PDO::FETCH_ASSOC );
        echo json_encode( $result );
    }
}

function handlePost( $pdo, $input ) {
    if ( empty( $input[ 'fname' ] ) || empty( $input[ 'sname' ] ) || empty( $input[ 'address' ] ) || empty( $input[ 'permit' ] ) ) {
        echo json_encode( [ 'message' => 'A field is missing' ] );
        die();
    }
    try {
        $pdo->beginTransaction();
        $query = $pdo->prepare(
            "INSERT INTO customer(`first_name`, `second_name`, `address`, `permit_number`)
          VALUES(:fname, :sname, :address, :permit)"
        );

        $query->execute( [
            'fname' => htmlspecialchars( $input[ 'fname' ] ),
            'sname' => htmlspecialchars( $input[ 'sname' ] ),
            'address' => htmlspecialchars( $input[ 'address' ] ),
            'permit' => htmlspecialchars( $input[ 'permit' ] )
        ] );
        $pdo->commit();

        echo json_encode( [ 'message' => 'User created successfully' ] );
    } catch ( \Exception $e ) {
        $pdo->rollBack();
        echo json_encode( [ 'message' => 'An error occured' ] );

    }

}

function handlePut( $pdo, $input ) {
    if ( empty( $input[ 'fname' ] ) || empty( $input[ 'sname' ] ) || empty( $input[ 'address' ] ) || empty( $input[ 'permit' ] ) ) {
        echo json_encode( [ 'message' => 'A field is missing' ] );
        die();
    }
    if ( isset( $_GET[ 'id' ] ) ) {
        try {
            $pdo->beginTransaction();
            $query = $pdo->prepare(
                "UPDATE Customer
        SET 
        first_name = :fname,
        second_name = :sname,
        address = :address,
        permit_number = :permit
        WHERE id = :id" );
                $query->execute( [
                    'id' => $_GET[ 'id' ],
                    'fname' => htmlspecialchars( $input[ 'fname' ] ),
                    'sname' => htmlspecialchars( $input[ 'sname' ] ),
                    'address' => htmlspecialchars( $input[ 'address' ] ),
                    'permit' => htmlspecialchars( $input[ 'permit' ] )
                ] );
                $pdo->commit();

                echo json_encode( [ 'message' => 'User updated successfully' ] );
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
                    'DELETE FROM customer WHERE id = :id'
                );                
                $query->execute(['id' => $_GET['id']]);
                $pdo->commit();

                echo json_encode( [ 'message' => 'User deleted successfully' ] );
            } catch ( \Exception $e ) {
                $pdo->rollBack();
                echo json_encode( [ 'message' => 'An error occured', $e ] );
            }

        }
    }
    ?>

