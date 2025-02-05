<?php
require_once __DIR__ . '/vendor/autoload.php'; 
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


require_once 'Database.php';
require_once 'Contract.php';

// Initialize the Database connection
$database = new Database();

// Instantiate the Contract class
$contract = new Contract($database);

// Example 1: Create the Contract table if it doesn't exist
echo "<pre>--- Creating Contract Table ---\n";
$contract->createTable();
echo "Contract table created (if not already existing).\n</pre>";

// Example 2: Create a new contract
echo "<pre>--- Creating a New Contract ---\n";
$contract->createContract(
    'vehicle123', 
    'customer123', 
    500.00, 
    '2025-02-01 10:00:00', 
    '2025-02-01 12:00:00', 
    '2025-02-02 12:00:00'
);
echo "New contract created for Vehicle UID 'vehicle123' and Customer UID 'customer123'.\n</pre>";

// Example 3: Get contract details by contract ID
echo "<pre>--- Fetching Contract Details for Contract ID 1 ---\n";
$contractDetails = $contract->getContractById(1);
echo "Contract Details:\n";
print_r($contractDetails);
echo "</pre>";

// Example 4: Update an existing contract
echo "<pre>--- Updating Contract with ID 1 ---\n";
$contract->updateContract(
    1, 
    'vehicle456', 
    'customer456', 
    600.00, 
    '2025-02-01 10:00:00', 
    '2025-02-01 12:00:00', 
    '2025-02-02 12:00:00', 
    '2025-02-02 14:00:00'
);
echo "Contract with ID 1 has been updated.\n</pre>";

// Example 5: Delete a contract by its ID
echo "<pre>--- Deleting Contract with ID 2 ---\n";
$contract->deleteContract(2);
echo "Contract with ID 2 has been deleted.\n</pre>";

// Example 6: List all contracts associated with a specific customer UID
echo "<pre>--- Fetching All Contracts for Customer UID 'customer123' ---\n";
$contractsForCustomer = $contract->getContractsByCustomerUid('customer123');
echo "Contracts for Customer UID 'customer123':\n";
print_r($contractsForCustomer);
echo "</pre>";



////////////////////////////////////////////////////////////////////////////////////////


require_once 'Billing.php';


// Instantiate the Billing class
$billing = new Billing($database);

// Example 1: Create the Billing table if it doesn't exist
echo "<pre>--- Creating Billing Table ---\n";
$billing->createTable();
echo "Billing table created (if not already existing).\n</pre>";

// Example 2: Add a payment entry to the Billing table
echo "<pre>--- Adding a Payment ---\n";
$billing->addPayment(1, 200.00);  // Contract ID 1, Amount 200.00
echo "Added a payment of 200.00 for Contract ID 1.\n</pre>";

// Example 3: Get payment details for a specific payment ID
echo "<pre>--- Fetching Payment Details for Payment ID 1 ---\n";
$paymentDetails = $billing->getPaymentById(1);
echo "Payment Details for Payment ID 1:\n";
print_r($paymentDetails);
echo "</pre>";

// Example 4: Get all payments associated with a specific contract ID
echo "<pre>--- Fetching All Payments for Contract ID 1 ---\n";
$paymentsForContract = $billing->getPaymentsByContractId(1);
echo "Payments for Contract ID 1:\n";
print_r($paymentsForContract);
echo "</pre>";

// Example 5: Update the amount paid for a specific payment
echo "<pre>--- Updating Payment with ID 1 ---\n";
$billing->updatePayment(1, 250.00);  // Payment ID 1, New Amount 250.00
echo "Payment with ID 1 has been updated to Amount 250.00.\n</pre>";

// Example 6: Delete a payment by its ID
echo "<pre>--- Deleting Payment with ID 2 ---\n";
$billing->deletePayment(2);
echo "Payment with ID 2 has been deleted.\n</pre>";

// Example 7: Check if a contract is fully paid
echo "<pre>--- Checking if Contract ID 1 is Fully Paid ---\n";
$isFullyPaid = $billing->isContractFullyPaid(1, 500.00);  // Contract ID 1, Contract Price 500.00
echo "Contract ID 1 is fully paid: " . ($isFullyPaid ? "Yes" : "No") . "\n</pre>";

// Example 8: Get all unpaid contracts (those with less than the full price paid)
echo "<pre>--- Fetching Unpaid Contracts ---\n";
$unpaidContracts = $billing->getUnpaidContracts();
echo "Unpaid Contracts:\n";
print_r($unpaidContracts);
echo "</pre>";

////////////////////////////////////////////////////////////////////////////////


require_once 'Customer.php';



// Instantiate the Customer class
$customer = new Customer($database);

// Example 1: Create or update a customer document in MongoDB
echo "<pre>--- Creating or Updating Customer ---\n";
$customer->createOrUpdateCustomer('12345', 'John', 'Doe', '123 Main St', 'A12345');
echo "Customer with UID '12345' has been created or updated.\n</pre>";

// Example 2: Get a customer by their full name
echo "<pre>--- Fetching Customer by Name (John Doe) ---\n";
$customerDetails = $customer->getCustomerByName('John', 'Doe');
echo "Customer details for John Doe:\n";
print_r($customerDetails);
echo "</pre>";

// Example 3: Get a customer by their UID
echo "<pre>--- Fetching Customer by UID (12345) ---\n";
$customerByUid = $customer->getCustomerByUid('12345');
echo "Customer details for UID '12345':\n";
print_r($customerByUid);
echo "</pre>";

// Example 4: Delete a customer by their UID
echo "<pre>--- Deleting Customer with UID 88888 ---\n";
$customer->deleteCustomerByUid('88888');
echo "Customer with UID '88888' has been deleted.\n</pre>";

// Example 5: Count the number of customers by address
echo "<pre>--- Counting Customers by Address (123 Main St) ---\n";
$customerCountByAddress = $customer->countCustomersByAddress('123 Main St');
echo "Number of customers at address '123 Main St': " . $customerCountByAddress . "\n</pre>";

// Example 6: Count the number of customers with a specific permit number
echo "<pre>--- Counting Customers by Permit Number (A12345) ---\n";
$customerCountByPermit = $customer->countCustomersByPermitNumber('A12345');
echo "Number of customers with permit number 'A12345': " . $customerCountByPermit . "\n</pre>";

///////////////////////////////////////////////////////////////////////////


require_once 'Vehicle.php';



// Instantiate the Vehicle class
$vehicle = new Vehicle($database);

// Example 1: Create or update a vehicle document in MongoDB
echo "<pre>--- Creating or Updating Vehicle ---\n";
$vehicle->createOrUpdateVehicle('V12345', 'XYZ 1234', 'Sedan, Blue, 4 doors', 50000);
echo "Vehicle with UID 'V12345' has been created or updated.\n</pre>";

// Example 2: Get a vehicle by its license plate
echo "<pre>--- Fetching Vehicle by License Plate (XYZ 1234) ---\n";
$vehicleDetails = $vehicle->getVehicleByLicensePlate('XYZ 1234');
echo "Vehicle details for license plate 'XYZ 1234':\n";
print_r($vehicleDetails);
echo "</pre>";

// Example 3: Get a vehicle by its UID
echo "<pre>--- Fetching Vehicle by UID (V12345) ---\n";
$vehicleByUid = $vehicle->getVehicleByUid('V12345');
echo "Vehicle details for UID 'V12345':\n";
print_r($vehicleByUid);
echo "</pre>";

// Example 4: Count vehicles with mileage greater than a specific value (e.g., 60000 km)
echo "<pre>--- Counting Vehicles with Mileage Greater Than 60000 km ---\n";
$vehicleCountGreaterThanKm = $vehicle->countVehiclesWithKmGreaterThan(60000);
echo "Number of vehicles with mileage greater than 60000 km: " . $vehicleCountGreaterThanKm . "\n</pre>";

// Example 5: Count vehicles with mileage less than a specific value (e.g., 20000 km)
echo "<pre>--- Counting Vehicles with Mileage Less Than 20000 km ---\n";
$vehicleCountLessThanKm = $vehicle->countVehiclesWithKmLessThan(20000);
echo "Number of vehicles with mileage less than 20000 km: " . $vehicleCountLessThanKm . "\n</pre>";

// Example 6: Update vehicle information (e.g., update the mileage)
echo "<pre>--- Updating Vehicle Mileage for UID V12345 ---\n";
$vehicle->updateVehicle('V12345', ['km' => 55000]);
echo "Vehicle mileage for UID 'V12345' has been updated.\n</pre>";

// Example 7: Delete a vehicle by its UID
echo "<pre>--- Deleting Vehicle with UID V88888 ---\n";
$vehicle->deleteVehicleByUid('V88888');
echo "Vehicle with UID 'V88888' has been deleted.\n</pre>";

?>



