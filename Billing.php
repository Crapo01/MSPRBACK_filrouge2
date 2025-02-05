<?php

require_once 'Database.php';

class Billing
{
    private $db;

    public function __construct(Database $database)
    {
        // Get the SQL connection from the Database
        $this->db = $database->getSqlConnection();
    }

    // Validate input for billing data
    private function validatePaymentData($contractId, $amount)
    {
        if (empty($contractId) || !is_numeric($contractId)) {
            throw new InvalidArgumentException("Invalid contract ID provided.");
        }

        if (empty($amount) || !is_numeric($amount) || $amount <= 0) {
            throw new InvalidArgumentException("Amount must be a positive number.");
        }
    }

    // Create the Billing table if it doesn't exist
    public function createTable()
    {
        try {
            $query = "
                CREATE TABLE IF NOT EXISTS Billing (
                    ID INT AUTO_INCREMENT PRIMARY KEY,
                    Contract_id INT NOT NULL,
                    Amount DECIMAL(10, 2) NOT NULL,
                    FOREIGN KEY (Contract_id) REFERENCES Contract(id)
                );
            ";
            $this->db->exec($query);
        } catch (Exception $e) {
            echo "Error creating table: " . $e->getMessage();
        }
    }

    // Add a payment entry to the Billing table
    public function addPayment($contractId, $amount)
    {
        try {
            // Validate the input data
            $this->validatePaymentData($contractId, $amount);

            $query = "INSERT INTO Billing (Contract_id, Amount) VALUES (:contractId, :amount)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':contractId', $contractId, PDO::PARAM_INT);
            $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
            $stmt->execute();
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error adding payment: " . $e->getMessage();
        }
    }

    // Get payment details for a specific payment ID
    public function getPaymentById($paymentId)
    {
        try {
            if (empty($paymentId) || !is_numeric($paymentId)) {
                throw new InvalidArgumentException("Invalid payment ID.");
            }

            $query = "SELECT * FROM Billing WHERE ID = :paymentId";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':paymentId', $paymentId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error fetching payment: " . $e->getMessage();
        }
    }

    // Get all payments associated with a specific contract ID
    public function getPaymentsByContractId($contractId)
    {
        try {
            if (empty($contractId) || !is_numeric($contractId)) {
                throw new InvalidArgumentException("Invalid contract ID.");
            }

            $query = "SELECT * FROM Billing WHERE Contract_id = :contractId";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':contractId', $contractId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error fetching payments: " . $e->getMessage();
        }
    }

    // Update the amount paid for a specific payment
    public function updatePayment($paymentId, $newAmount)
    {
        try {
            if (empty($paymentId) || !is_numeric($paymentId)) {
                throw new InvalidArgumentException("Invalid payment ID.");
            }

            if (empty($newAmount) || !is_numeric($newAmount) || $newAmount <= 0) {
                throw new InvalidArgumentException("New amount must be a positive number.");
            }

            $query = "UPDATE Billing SET Amount = :newAmount WHERE ID = :paymentId";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':newAmount', $newAmount, PDO::PARAM_STR);
            $stmt->bindParam(':paymentId', $paymentId, PDO::PARAM_INT);
            $stmt->execute();
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error updating payment: " . $e->getMessage();
        }
    }

    // Delete a payment entry from the Billing table
    public function deletePayment($paymentId)
    {
        try {
            if (empty($paymentId) || !is_numeric($paymentId)) {
                throw new InvalidArgumentException("Invalid payment ID.");
            }

            $query = "DELETE FROM Billing WHERE ID = :paymentId";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':paymentId', $paymentId, PDO::PARAM_INT);
            $stmt->execute();
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error deleting payment: " . $e->getMessage();
        }
    }

    // Check if a contract has been fully paid
    public function isContractFullyPaid($contractId, $contractPrice)
    {
        try {
            if (empty($contractId) || !is_numeric($contractId)) {
                throw new InvalidArgumentException("Invalid contract ID.");
            }

            if (empty($contractPrice) || !is_numeric($contractPrice)) {
                throw new InvalidArgumentException("Invalid contract price.");
            }

            $query = "SELECT SUM(Amount) AS totalPaid FROM Billing WHERE Contract_id = :contractId";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':contractId', $contractId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // If the total paid equals the contract price, the contract is fully paid
            return $result['totalPaid'] >= $contractPrice;
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error checking if contract is fully paid: " . $e->getMessage();
        }
    }

    // Get all unpaid contracts
    public function getUnpaidContracts()
    {
        try {
            $query = "
                SELECT Contract_id, SUM(Amount) AS totalPaid
                FROM Billing
                GROUP BY Contract_id
                HAVING totalPaid < (SELECT price FROM Contract WHERE id = Contract_id)
            ";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error fetching unpaid contracts: " . $e->getMessage();
        }
    }
}
?>
