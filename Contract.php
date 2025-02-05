<?php

require_once 'Database.php';

class Contract
{
    private $db;

    // Constructor to get the SQL connection from the Database class
    public function __construct(Database $database)
    {
        $this->db = $database->getSqlConnection();
    }

    // Validate contract input data
    private function validateContractData($vehicleUid, $customerUid, $price, $signDate, $locBeginDate, $locEndDate)
    {
        if (empty($vehicleUid) || empty($customerUid)) {
            throw new InvalidArgumentException("Vehicle UID and Customer UID are required.");
        }

        if (!is_numeric($price) || $price <= 0) {
            throw new InvalidArgumentException("Price must be a positive number.");
        }

        if (empty($signDate) || empty($locBeginDate) || empty($locEndDate)) {
            throw new InvalidArgumentException("Sign, location begin, and location end dates are required.");
        }

        // Check if dates are valid
        $signDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $signDate);
        $locBeginDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $locBeginDate);
        $locEndDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $locEndDate);

        if (!$signDateTime || !$locBeginDateTime || !$locEndDateTime) {
            throw new InvalidArgumentException("Invalid date format. Expected format: Y-m-d H:i:s.");
        }

        // Ensure loc_end_date is after loc_begin_date
        if ($locEndDateTime <= $locBeginDateTime) {
            throw new InvalidArgumentException("Location end date must be later than location begin date.");
        }
    }

    // Create the Contract table if it doesn't exist
    public function createTable()
    {
        try {
            $sql = "CREATE TABLE IF NOT EXISTS Contract (
                id INT PRIMARY KEY AUTO_INCREMENT,
                vehicle_uid CHAR(255),
                customer_uid CHAR(255),
                sign_datetime DATETIME,
                loc_begin_datetime DATETIME,
                loc_end_datetime DATETIME,
                returning_datetime DATETIME,
                price FLOAT
            )";
            $this->db->exec($sql);
        } catch (Exception $e) {
            echo "Error creating table: " . $e->getMessage();
        }
    }

    // Create a new contract
    public function createContract($vehicleUid, $customerUid, $price, $signDate, $locBeginDate, $locEndDate)
    {
        try {
            // Validate the input data
            $this->validateContractData($vehicleUid, $customerUid, $price, $signDate, $locBeginDate, $locEndDate);

            $sql = "INSERT INTO Contract (vehicle_uid, customer_uid, sign_datetime, loc_begin_datetime, loc_end_datetime, price)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$vehicleUid, $customerUid, $signDate, $locBeginDate, $locEndDate, $price]);
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error creating contract: " . $e->getMessage();
        }
    }

    // Get a contract by its ID
    public function getContractById($contractId)
    {
        try {
            if (empty($contractId) || !is_numeric($contractId)) {
                throw new InvalidArgumentException("Invalid contract ID.");
            }

            $sql = "SELECT * FROM Contract WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$contractId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error fetching contract: " . $e->getMessage();
        }
    }

    // Update an existing contract
    public function updateContract($contractId, $vehicleUid, $customerUid, $price, $signDate, $locBeginDate, $locEndDate, $returningDate)
    {
        try {
            if (empty($contractId) || !is_numeric($contractId)) {
                throw new InvalidArgumentException("Invalid contract ID.");
            }

            // Validate the input data
            $this->validateContractData($vehicleUid, $customerUid, $price, $signDate, $locBeginDate, $locEndDate);

            $sql = "UPDATE Contract SET 
                        vehicle_uid = ?, 
                        customer_uid = ?, 
                        sign_datetime = ?, 
                        loc_begin_datetime = ?, 
                        loc_end_datetime = ?, 
                        returning_datetime = ?, 
                        price = ? 
                    WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$vehicleUid, $customerUid, $signDate, $locBeginDate, $locEndDate, $returningDate, $price, $contractId]);
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error updating contract: " . $e->getMessage();
        }
    }

    // Delete a contract by its ID
    public function deleteContract($contractId)
    {
        try {
            if (empty($contractId) || !is_numeric($contractId)) {
                throw new InvalidArgumentException("Invalid contract ID.");
            }

            $sql = "DELETE FROM Contract WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$contractId]);
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error deleting contract: " . $e->getMessage();
        }
    }

    // List all contracts associated with a specific customer UID
    public function getContractsByCustomerUid($customerUid)
    {
        try {
            if (empty($customerUid)) {
                throw new InvalidArgumentException("Customer UID is required.");
            }

            $sql = "SELECT * FROM Contract WHERE customer_uid = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$customerUid]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error fetching contracts by customer UID: " . $e->getMessage();
        }
    }
    

    // Check if a contract is fully paid
    public function isContractFullyPaid($contractId)
    {
        try {
            if (empty($contractId) || !is_numeric($contractId)) {
                throw new InvalidArgumentException("Invalid contract ID.");
            }

            $sql = "SELECT SUM(amount) AS total_paid FROM Billing WHERE contract_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$contractId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $totalPaid = $result['total_paid'] ?? 0;
            $contract = $this->getContractById($contractId);
            $totalPrice = $contract['price'];

            return $totalPaid >= $totalPrice;
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error checking if contract is fully paid: " . $e->getMessage();
        }
    }
}
?>
