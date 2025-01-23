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

function handleGet($pdo) {
    if (isset($_GET['plate'])) {        
        $plate = $_GET['plate'];        
        if (!preg_match('/^[A-Za-z0-9]+$/', $plate)) {
            echo json_encode(['message' => 'Invalid plate format']);
            return;
        }
        $sql = "SELECT * FROM Vehicle WHERE LOWER(licence_plate) LIKE LOWER(:plate)";
        $stmt = $pdo->prepare($sql);
        $likePlate = '%' . $plate . '%';
        $stmt->bindParam(':plate', $likePlate, PDO::PARAM_STR);
        $stmt->execute();

        $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($vehicles)) {
            echo json_encode(['message' => 'No match']);
        } else {
            echo json_encode($vehicles);
        }
    } else {        
        $sql = 'SELECT * FROM vehicle';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    }
}


function handlePost($pdo, $input) {
    
    if (empty($input['1']) || empty($input['2']) || empty($input['3'])) {
        echo json_encode(['message' => 'A field is missing']);
        die();
    }
    
    if (!preg_match('/^[A-Z0-9]{1,10}$/', $input['1'])) {
        echo json_encode(['message' => 'Invalid licence plate format']);
        die();
    }
    
    if (!preg_match('/^[a-zA-Z0-9 ]*$/', $input['2'])) {
        echo json_encode(['message' => 'Information field contains special characters']);
        die();
    }
    
    if (strlen($input['2']) > 255) {
        echo json_encode(['message' => 'Information field is too long']);
        die();
    }
    
    if (!is_numeric($input['3']) || (int)$input['3'] < 0) {
        echo json_encode(['message' => 'Kilometer value must be a positive number']);
        die();
    }

    try {
        
        $pdo->beginTransaction();
        
        $query = $pdo->prepare(
            "INSERT INTO vehicle (`licence_plate`, `informations`, `km`)
            VALUES (:a, :b, :c)"
        );
        
        $query->execute([
            'a' => $input['1'],
            'b' => $input['2'],
            'c' => $input['3']
        ]);
        
        $pdo->commit();

        echo json_encode(['message' => 'Vehicle created successfully']);
    } catch (\Exception $e) {
        
        $pdo->rollBack();
        echo json_encode(['message' => 'An error occurred', 'error' => $e->getMessage()]);
    }
}


function handlePut($pdo, $input) {    
    if (empty($input['1']) || empty($input['2']) || empty($input['3'])) {
        echo json_encode(['message' => 'A field is missing']);
        die();
    }
    
    if (!preg_match('/^[A-Z0-9]{1,10}$/', $input['1'])) {
        echo json_encode(['message' => 'Invalid licence plate format']);
        die();
    }

    if (!preg_match('/^[a-zA-Z0-9 ]*$/', $input['2'])) {
        echo json_encode(['message' => 'Information field contains special characters']);
        die();
    }

    if (strlen($input['2']) > 255) {
        echo json_encode(['message' => 'Information field is too long']);
        die();
    }

    if (!is_numeric($input['3']) || (int)$input['3'] < 0) {
        echo json_encode(['message' => 'Kilometer value must be a positive number']);
        die();
    }

    if (!isset($_GET['id'])) {
        echo json_encode(['message' => 'ID is missing in the request']);
        die();
    }

    try {
        $pdo->beginTransaction();

        $query = $pdo->prepare(
            "UPDATE Vehicle
            SET licence_plate = :a, informations = :b, km = :c
            WHERE id = :id"
        );

        $query->execute([
            'id' => $_GET['id'],
            'a' => $input['1'],
            'b' => $input['2'],
            'c' => $input['3']
        ]);

        $pdo->commit();

        echo json_encode(['message' => 'Vehicle updated successfully']);
    } catch (\Exception $e) {
        $pdo->rollBack();
        echo json_encode(['message' => 'An error occurred', 'error' => $e->getMessage()]);
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

