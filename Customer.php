<?php

require_once 'Database.php';

class Customer
{
    private $db;

    public function __construct(Database $database)
    {
        // Get the MongoDB collection for customers
        $this->db = $database->getMongoConnection()->customers;
    }

    // Validate customer input data
    private function validateCustomerData($uid, $firstName, $secondName, $address, $permitNumber)
    {
        if (empty($uid) || empty($firstName) || empty($secondName) || empty($address) || empty($permitNumber)) {
            throw new InvalidArgumentException("All fields (UID, first name, second name, address, permit number) are required.");
        }

        if (!is_string($uid) || !is_string($firstName) || !is_string($secondName) || !is_string($address) || !is_string($permitNumber)) {
            throw new InvalidArgumentException("All input fields must be strings.");
        }

        // Optional: Additional validation for specific fields (e.g., permit number format)
        if (!preg_match("/^[A-Za-z0-9]+$/", $permitNumber)) {
            throw new InvalidArgumentException("Permit number must be alphanumeric.");
        }
    }

    // Create or update a customer document in MongoDB
    public function createOrUpdateCustomer($uid, $firstName, $secondName, $address, $permitNumber)
    {
        try {
            // Validate the input
            $this->validateCustomerData($uid, $firstName, $secondName, $address, $permitNumber);

            // Customer data
            $customer = [
                'uid' => $uid,
                'first_name' => $firstName,
                'second_name' => $secondName,
                'address' => $address,
                'permit_number' => $permitNumber
            ];

            // Update the customer if it exists, otherwise create a new document (upsert)
            $result = $this->db->updateOne(
                ['uid' => $uid], // Search for a customer with the given UID
                ['$set' => $customer], // Set the customer data (overwrite if exists)
                ['upsert' => true] // Insert if it doesn't exist
            );

            if ($result->getModifiedCount() == 0 && $result->getUpsertedCount() == 0) {
                throw new Exception("Customer update or insert failed.");
            }
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Get a customer by their full name (first_name and second_name)
    public function getCustomerByName($firstName, $secondName)
    {
        try {
            if (empty($firstName) || empty($secondName)) {
                throw new InvalidArgumentException("Both first name and second name are required.");
            }

            return $this->db->findOne(['first_name' => $firstName, 'second_name' => $secondName]);
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Get a customer by their UID
    public function getCustomerByUid($uid)
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

    // Delete a customer by their UID
    public function deleteCustomerByUid($uid)
    {
        try {
            if (empty($uid)) {
                throw new InvalidArgumentException("UID is required.");
            }

            $result = $this->db->deleteOne(['uid' => $uid]);

            if ($result->getDeletedCount() == 0) {
                throw new Exception("Customer deletion failed, no customer found with UID: $uid.");
            }
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Count the number of customers based on certain criteria (e.g., by address)
    public function countCustomersByAddress($address)
    {
        try {
            if (empty($address)) {
                throw new InvalidArgumentException("Address is required.");
            }

            return $this->db->countDocuments(['address' => $address]);
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Count the number of customers with a specific permit number
    public function countCustomersByPermitNumber($permitNumber)
    {
        try {
            if (empty($permitNumber)) {
                throw new InvalidArgumentException("Permit number is required.");
            }

            return $this->db->countDocuments(['permit_number' => $permitNumber]);
        } catch (InvalidArgumentException $e) {
            echo "Input validation error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

