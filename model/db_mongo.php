<?php
// Autoload de Composer
require 'vendor/autoload.php'; 

class MongoDatabase {
    private $host;  
    private $dbname; 
    private $username;
    private $password; 
    private $customers;
    private $vehicles;
    
    // Constructeur pour se connecter à MongoDB et sélectionner la base de données et la collection
    public function __construct() {
        $this->host = getenv('MDB_HOST');
        $this->dbname = getenv('MDB_DBNAME');  
        $this->username = getenv('MDB_USER');  
        $this->password = getenv('MDB_PASSWORD'); 
        $this->connect();
    }

    private function connect(){
        $dsn = "mongodb://" . $this->host;        
        $client = new MongoDB\Client($dsn);
        $dbn= $this->dbname;
        $this->customers = $client->$dbn->Customers; 
        $this->vehicles = $client->$dbn->Vehicles; 
    }

    public function getCustomers() {
        return $this->customers;
    }
    
    public function getVehicles() {
        return $this->vehicles;
    }  
}
?>
