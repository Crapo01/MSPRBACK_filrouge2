<?php
//header( 'Content-Type: application/json' );
require_once'../../model/db_mysql.php';
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
    if ( isset( $_GET['first_name'] ) && isset( $_GET['second_name'] ) ) {
        $f = htmlspecialchars( $_GET['first_name'], 'UTF-8' );
        $s = htmlspecialchars( $_GET['second_name'], 'UTF-8' );

        $query = $pdo->prepare(
            "SELECT * 
             FROM Customer
             WHERE LOWER(first_name) LIKE LOWER(:first_name)
             AND LOWER(second_name) LIKE LOWER(:second_name)"
        );
        $query->execute([
            'first_name' => '%' . $f . '%',
            'second_name' => '%' . $s . '%'
        ]);
        $customers = $query->fetchAll( PDO::FETCH_ASSOC );
        if ( empty( $customers ) ) {
            echo json_encode([ 'message' => 'No match' ]);
        } else {
            echo json_encode( $customers );
        }
    } else {
        $sql = 'SELECT * FROM Customer';
        $stmt = $pdo->prepare( $sql );
        $stmt->execute();
        $result = $stmt->fetchAll( PDO::FETCH_ASSOC );
        echo json_encode( $result );
    }
}


function handlePost( $pdo, $input ) {
    if ( empty( $input['fname'] ) || empty( $input['sname'] ) || empty( $input['address'] ) || empty( $input['permit'] ) ) {
        echo json_encode([ 'message' => 'A field is missing' ]);
        die();
    }

    if ( !preg_match( "/^[a-zA-Z ]*$/", $input['fname'] ) || !preg_match( "/^[a-zA-Z ]*$/", $input['sname'] ) ) {
        echo json_encode([ 'message' => 'Invalid name format' ]);
        die();
    }

    if ( !preg_match( "/^[a-zA-Z0-9, ]*$/", $input['address'] ) ) {
        echo json_encode([ 'message' => 'Invalid address format' ]);
        die();
    }

    if ( !preg_match( "/^[0-9]+$/", $input['permit'] ) ) {
        echo json_encode([ 'message' => 'Invalid permit number format' ]);
        die();
    }

    try {
        $pdo->beginTransaction();
        $query = $pdo->prepare(
            "INSERT INTO customer(`first_name`, `second_name`, `address`, `permit_number`)
            VALUES(:fname, :sname, :address, :permit)"
        );

        $query->execute([
            'fname' => htmlspecialchars( $input['fname'] ),
            'sname' => htmlspecialchars( $input['sname'] ),
            'address' => htmlspecialchars( $input['address'] ),
            'permit' => htmlspecialchars( $input['permit'] )
        ]);
        $pdo->commit();

        echo json_encode([ 'message' => 'Customer created successfully' ]);
    } catch ( \Exception $e ) {
        $pdo->rollBack();
        echo json_encode([ 'message' => 'An error occurred' ]);
    }
}


function handlePut( $pdo, $input ) {
    if ( empty( $input['fname'] ) || empty( $input['sname'] ) || empty( $input['address'] ) || empty( $input['permit'] ) ) {
        echo json_encode([ 'message' => 'A field is missing' ]);
        die();
    }

    if ( !preg_match( "/^[a-zA-Z ]*$/", $input['fname'] ) || !preg_match( "/^[a-zA-Z ]*$/", $input['sname'] ) ) {
        echo json_encode([ 'message' => 'Invalid name format' ]);
        die();
    }

    if ( !preg_match( "/^[a-zA-Z0-9, ]*$/", $input['address'] ) ) {
        echo json_encode([ 'message' => 'Invalid address format' ]);
        die();
    }

    if ( !preg_match( "/^[0-9]+$/", $input['permit'] ) ) {
        echo json_encode([ 'message' => 'Invalid permit number format' ]);
        die();
    }

    if ( isset( $_GET['id'] ) && is_numeric( $_GET['id'] ) ) {
        try {
            $pdo->beginTransaction();
            $query = $pdo->prepare(
                "UPDATE Customer
                SET 
                first_name = :fname,
                second_name = :sname,
                address = :address,
                permit_number = :permit
                WHERE id = :id"
            );
            $query->execute([
                'id' => $_GET['id'],
                'fname' => htmlspecialchars( $input['fname'] ),
                'sname' => htmlspecialchars( $input['sname'] ),
                'address' => htmlspecialchars( $input['address'] ),
                'permit' => htmlspecialchars( $input['permit'] )
            ]);
            $pdo->commit();

            echo json_encode([ 'message' => 'Customer updated successfully' ]);
        } catch ( \Exception $e ) {
            $pdo->rollBack();
            echo json_encode([ 'message' => 'An error occurred', 'error' => $e->getMessage() ]);
        }
    } else {
        echo json_encode([ 'message' => 'Invalid or missing ID' ]);
        die();
    }
}


function handleDelete( $pdo ) {
    if ( isset( $_GET['id'] ) && is_numeric( $_GET['id'] ) ) {            
        try {
            $pdo->beginTransaction();
            $query = $pdo->prepare(
                'DELETE FROM customer WHERE id = :id'
            );                
            $query->execute(['id' => $_GET['id']]);
            $pdo->commit();

            echo json_encode([ 'message' => 'Customer deleted successfully' ]);
        } catch ( \Exception $e ) {
            $pdo->rollBack();
            echo json_encode([ 'message' => 'An error occurred', 'error' => $e->getMessage() ]);
        }

    } else {
        echo json_encode([ 'message' => 'Invalid or missing ID' ]);
        die();
    }
}

    ?>

