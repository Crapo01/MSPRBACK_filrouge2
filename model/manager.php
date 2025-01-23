<?php

require_once 'db_mysql.php';

class Manager {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getDbConnect();
    }

    public function getContractsByVehicleId($vehicleId) {
        try {
            $query = $this->pdo->prepare(
                "SELECT * FROM `contract` WHERE `vehicle_id` = :vehicleId"
            );
            $query->execute(['vehicleId' => $vehicleId]);

            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                echo json_encode(['message' => 'No match']);
            } else {
                echo json_encode($result);
            }
        } catch (Exception $e) {
            echo json_encode(['message' => 'An error occurred', 'error' => $e->getMessage()]);
        }
    }

    public function getAvgDelayByVehicleId($vehicleId) {
        try {
            $query = $this->pdo->prepare(
                "SELECT c.vehicle_id, AVG(TIMESTAMPDIFF(MINUTE, c.loc_end_datetime, c.returning_datetime)) AS avg_delay_minutes
                FROM `contract` AS c
                WHERE c.vehicle_id = :vehicleId"
            );
            $query->execute(['vehicleId' => $vehicleId]);

            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                echo json_encode(['message' => 'No match']);
            } else {
                echo json_encode($result);
            }
        } catch (Exception $e) {
            echo json_encode(['message' => 'An error occurred', 'error' => $e->getMessage()]);
        }
    }

    public function showBillsByContractId($contractId) {
        try {
            $query = $this->pdo->prepare(
                "SELECT amount FROM `billing` WHERE contract_id = :contractId"
            );
            $query->execute(['contractId' => $contractId]);

            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                echo json_encode(['message' => 'No match']);
            } else {
                echo json_encode($result);
            }
        } catch (Exception $e) {
            echo json_encode(['message' => 'An error occurred', 'error' => $e->getMessage()]);
        }
    }


    public function getPaidBill($contractId) {
        try {
            $query = $this->pdo->prepare(
                "SELECT c.id, c.price, SUM(b.amount) AS total_paid
                 FROM Contract c
                 LEFT JOIN Billing b ON c.id = b.contract_id
                 HAVING SUM(b.amount) >= c.price AND c.id = :contractId"
            );
            $query->execute(['contractId' => $contractId]);

            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                echo json_encode(['message' => 'No match or not fully paid']);
            } else {
                echo json_encode(['message' => 'Bill is fully paid']);
            }
        } catch (Exception $e) {
            echo json_encode(['message' => 'An error occurred', 'error' => $e->getMessage()]);
        }
    }

    public function getOngoingContracts($customerId) {
        try {
            $query = $this->pdo->prepare(
                "SELECT * 
                 FROM Contract
                 WHERE customer_id = :customerId AND returning_datetime IS NULL"
            );
            $query->execute(['customerId' => $customerId]);

            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                echo json_encode(['message' => 'No match']);
            } else {
                echo json_encode($result);
            }
        } catch (Exception $e) {
            echo json_encode(['message' => 'An error occurred', 'error' => $e->getMessage()]);
        }
    }

    public function getContractWithDelays() {
        try {
            $query = $this->pdo->query(
                "SELECT * 
                 FROM `contract` 
                 WHERE `returning_datetime` > SUBTIME(`loc_end_datetime`, '-1:0:0')"
            );

            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                echo json_encode(['message' => 'No match']);
            } else {
                echo json_encode($result);
            }
        } catch (Exception $e) {
            echo json_encode(['message' => 'An error occurred', 'error' => $e->getMessage()]);
        }
    }

    public function getContractsByCustomerId($customerId) {
        try {
            $query = $this->pdo->prepare(
                "SELECT * 
                 FROM Contract
                 WHERE customer_id = :customerId"
            );
            $query->execute(['customerId' => $customerId]);

            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                echo json_encode(['message' => 'No match']);
            } else {
                echo json_encode($result);
            }
        } catch (Exception $e) {
            echo json_encode(['message' => 'An error occurred', 'error' => $e->getMessage()]);
        }
    }


    public function getAverageDelayByCustomer() {
        try {
            $query = $this->pdo->query(
                "SELECT 
                    c.customer_id,
                    AVG(TIMESTAMPDIFF(MINUTE, c.loc_end_datetime, c.returning_datetime)) AS avg_delay_minutes
                FROM 
                    Contract c
                WHERE 
                    c.returning_datetime > SUBTIME(`loc_end_datetime`, '-1:0:0')
                GROUP BY 
                    c.customer_id"
            );

            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                echo json_encode(['message' => 'No match']);
            } else {
                echo json_encode($result);
            }
        } catch (Exception $e) {
            echo json_encode(['message' => 'An error occurred', 'error' => $e->getMessage()]);
        }
    }

    public function getUnpaidContracts() {
        try {
            $query = $this->pdo->query(
                "SELECT c.id, c.price, SUM(b.amount) AS total_paid
                FROM Contract c 
                LEFT JOIN Billing b ON c.id = b.contract_id
                GROUP BY c.id
                HAVING SUM(b.amount) < c.price OR SUM(b.amount) IS NULL"
            );

            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                echo json_encode(['message' => 'No match or fully paid']);
            } else {
                echo json_encode($result);
            }
        } catch (Exception $e) {
            echo json_encode(['message' => 'An error occurred', 'error' => $e->getMessage()]);
        }
    }

    public function getDelaysByDateRange($lowDate, $highDate) {
        if (!isset($lowDate) || !isset($highDate)) {
            echo json_encode(['message' => 'A date range is needed']);
            return;
        }

        if (!$this->validateDate($lowDate) || !$this->validateDate($highDate)) {
            echo json_encode(['message' => 'Invalid date format']);
            return;
        }

        try {
            $query = $this->pdo->prepare(
                "SELECT * FROM `contract` 
                WHERE `returning_datetime` > SUBTIME(`loc_end_datetime`, '-1:0:0') 
                AND `returning_datetime` > :lowDate 
                AND `returning_datetime` < :highDate"
            );

            $query->execute(['lowDate' => $lowDate, 'highDate' => $highDate]);

            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                echo json_encode(['message' => 'No match']);
            } else {
                echo json_encode($result);
            }
        } catch (Exception $e) {
            echo json_encode(['message' => 'An error occurred', 'error' => $e->getMessage()]);
        }
    }

    private function validateDate($date) {
        $d = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        return $d && $d->format('Y-m-d H:i:s') === $date;
    }
}


