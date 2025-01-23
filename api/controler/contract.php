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
    if ( isset( $_GET['id'] ) && is_numeric( $_GET['id'] ) ) {
        $id = $_GET['id'];

        $query = $pdo->prepare(
            "SELECT * 
             FROM Contract
             WHERE id = :id"
        );
        $query->execute(['id' => $id]);

        $contracts = $query->fetchAll( PDO::FETCH_ASSOC );
        if ( empty( $contracts ) ) {
            echo json_encode([ 'message' => 'No match' ]);
        } else {
            echo json_encode( $contracts );
        }
    } else {
        $sql = 'SELECT * FROM Contract';
        $stmt = $pdo->prepare( $sql );
        $stmt->execute();
        $result = $stmt->fetchAll( PDO::FETCH_ASSOC );
        echo json_encode( $result );
    }
}


function handlePost( $pdo, $input ) {
    if ( empty( $input['1'] ) || empty( $input['2'] ) || empty( $input['3'] ) || empty( $input['4'] ) || empty( $input['5'] ) || empty( $input['6'] ) ) {
        echo json_encode([ 'message' => 'A field is missing' ]);
        die();
    }

    if ( !is_numeric( $input['1'] ) || !is_numeric( $input['2'] ) ) {
        echo json_encode([ 'message' => 'Invalid vehicle_id or customer_id' ]);
        die();
    }

    if ( !strtotime( $input['3'] ) || !strtotime( $input['4'] ) || !strtotime( $input['5'] ) ) {
        echo json_encode([ 'message' => 'Invalid datetime format' ]);
        die();
    }

    if ( !is_numeric( $input['6'] ) || floatval( $input['6'] ) <= 0 ) {
        echo json_encode([ 'message' => 'Invalid price format' ]);
        die();
    }

    try {
        $pdo->beginTransaction();
        $query = $pdo->prepare(
            "INSERT INTO contract (`vehicle_id`, `customer_id`, `sign_datetime`, `loc_begin_datetime`, `loc_end_datetime`, `price`)
            VALUES(:a, :b, :c, :d, :e, :f)"
        );

        $query->execute([
            'a' => $input['1'],
            'b' => $input['2'],
            'c' => $input['3'],
            'd' => $input['4'],
            'e' => $input['5'],
            'f' => $input['6']
        ]);
        $pdo->commit();

        echo json_encode([ 'message' => 'Contract created successfully' ]);
    } catch ( \Exception $e ) {
        $pdo->rollBack();
        echo json_encode([ 'message' => 'An error occurred', 'error' => $e->getMessage() ]);
    }
}


function handlePut( $pdo, $input ) {
    if ( empty( $input['1'] ) || empty( $input['2'] ) || empty( $input['3'] ) || empty( $input['4'] ) || empty( $input['5'] ) || empty( $input['6'] ) || empty( $input['7'] ) ) {
        echo json_encode([ 'message' => 'A field is missing' ]);
        die();
    }

    if ( !is_numeric( $input['1'] ) || !is_numeric( $input['2'] ) ) {
        echo json_encode([ 'message' => 'Invalid vehicle_id or customer_id' ]);
        die();
    }

    if ( !strtotime( $input['3'] ) || !strtotime( $input['4'] ) || !strtotime( $input['5'] ) ) {
        echo json_encode([ 'message' => 'Invalid datetime format' ]);
        die();
    }

    if ( !is_numeric( $input['6'] ) || floatval( $input['6'] ) <= 0 ) {
        echo json_encode([ 'message' => 'Invalid price format' ]);
        die();
    }

    if ( !empty( $input['7'] ) && !strtotime( $input['7'] ) ) {
        echo json_encode([ 'message' => 'Invalid returning_datetime format' ]);
        die();
    }

    if ( isset( $_GET['id'] ) && is_numeric( $_GET['id'] ) ) {
        try {
            $pdo->beginTransaction();
            $query = $pdo->prepare(
                "UPDATE Contract
                SET 
                vehicle_id = :a,
                customer_id = :b,
                sign_datetime = :c,
                loc_begin_datetime = :d,
                loc_end_datetime = :e,
                price = :f,
                returning_datetime = :g
                WHERE id = :id"
            );
            $query->execute([
                'id' => $_GET['id'],
                'a' => $input['1'],
                'b' => $input['2'],
                'c' => $input['3'],
                'd' => $input['4'],
                'e' => $input['5'],
                'f' => $input['6'],
                'g' => $input['7']
            ]);
            $pdo->commit();

            echo json_encode([ 'message' => 'Contract updated successfully' ]);
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
                'DELETE FROM Contract WHERE id = :id'
            );                
            $query->execute(['id' => $_GET['id']]);
            $pdo->commit();

            echo json_encode([ 'message' => 'Contract deleted successfully' ]);
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

