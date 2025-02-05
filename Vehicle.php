<?php

require_once 'Database.php';

class Vehicle
{
    private $db;

    public function __construct(Database $database)
    {
        // Get the MongoDB collection for vehicles
        $this->db = $database->getMongoConnection()->vehicles;
    }

    // Validate vehicle input data
    private function validateVehicleData($uid, $licensePlate, $info, $km)
    {
        if (empty($uid) || empty($licensePlate) || empty($info)) {
            throw new InvalidArgumentException("UID, license plate, and information are required.");
        }

        if (!is_string($uid) || !is_string($licensePlate) || !is_string($info)) {
            throw new InvalidArgumentException("UID, license plate, and information must be strings.");
        }

        if (!is_numeric($km)) {
            throw new InvalidArgumentException("Mileage (km) must be a number.");
        }
    }

    // Create or update a vehicle document in MongoDB
    public function createOrUpdateVehicle($uid, $licensePlate, $info, $km)
    {
        try {
            // Validate the input
            $this->validateVehicleData($uid, $licensePlate, $info, $km);

            // Vehicle data
            $vehicle = [
                'uid' => $uid,
                'license_plate' => $licensePlate,
                'informations' => $info,
                'km' => $km
            ];

            // Update the vehicle if it exists, otherwise create a new document (upsert)
            $result = $this->db->updateOne(
                ['uid' => $uid], // Search for a vehicle with the given UID
                ['$set' => $vehicle], // Set the vehicle data (overwrite if exists)
                ['upsert' => true] // Insert if it doesn't exist
            );

            if ($result->getModifiedCount() == 0 && $result->getUpsertedCount() == 0) {
                throw new Exception("Vehicle update or insert failed.");
            }
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Get a vehicle by its license plate
    public function getVehicleByLicensePlate($licensePlate)
    {
        try {
            if (empty($licensePlate)) {
                throw new InvalidArgumentException("License plate is required.");
            }

            return $this->db->findOne(['license_plate' => $licensePlate]);
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Get a vehicle by its UID
    public function getVehicleByUid($uid)
    {
        try {
            if (empty($uid)) {
                throw new InvalidArgumentException("UID is required.");
            }

            return $this->db->findOne(['uid' => $uid]);
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Count vehicles with mileage greater than a specific value
    public function countVehiclesWithKmGreaterThan($km)
    {
        try {
            if (!is_numeric($km)) {
                throw new InvalidArgumentException("Mileage (km) must be a number.");
            }

            return $this->db->countDocuments(['km' => ['$gt' => $km]]);
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Count vehicles with mileage less than a specific value
    public function countVehiclesWithKmLessThan($km)
    {
        try {
            if (!is_numeric($km)) {
                throw new InvalidArgumentException("Mileage (km) must be a number.");
            }

            return $this->db->countDocuments(['km' => ['$lt' => $km]]);
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Update vehicle information (e.g., when a vehicle's mileage is updated)
    public function updateVehicle($uid, $newInfo)
    {
        try {
            if (empty($uid)) {
                throw new InvalidArgumentException("UID is required.");
            }

            $result = $this->db->updateOne(
                ['uid' => $uid],
                ['$set' => $newInfo]
            );

            if ($result->getModifiedCount() == 0) {
                throw new Exception("Vehicle update failed.");
            }
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Delete a vehicle by its UID
    public function deleteVehicleByUid($uid)
    {
        try {
            if (empty($uid)) {
                throw new InvalidArgumentException("UID is required.");
            }

            $result = $this->db->deleteOne(['uid' => $uid]);

            if ($result->getDeletedCount() == 0) {
                throw new Exception("Vehicle deletion failed, no vehicle found with UID: $uid.");
            }
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

