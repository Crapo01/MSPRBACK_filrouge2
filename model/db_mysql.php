<?php

class Database {
    private $host;

    private $dbname;

    private $username;
    private $password;

    private $dbConnect;

    public function __construct() {
        $this->host = getenv( 'DB_HOST' );
        $this->dbname = getenv( 'DB_DBNAME' );

        $this->username = getenv( 'DB_USER' );

        $this->password = getenv( 'DB_PASSWORD' );

        $this->connect();
    }

    public function connect() {
        try {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
            $this->dbConnect = new PDO( $dsn, $this->username, $this->password );
            $this->dbConnect->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        } catch ( PDOException $e ) {
            die( 'Erreur de connexion à la base de données : ' . $e->getMessage() );
        }

    }

    public function getDbConnect() {
        return $this->dbConnect;
    }

    public function createCustomerTable() {
        $query = "
        CREATE TABLE IF NOT EXISTS customer (
            id INT AUTO_INCREMENT PRIMARY KEY,  
            first_name CHAR(255),               
            second_name CHAR(255),              
            address CHAR(255),                  
            permit_number CHAR(255) 
        )";
        $this->dbConnect->exec( $query );
    }

    public function createContractTable() {
        $query = "
        CREATE TABLE IF NOT EXISTS contract (
            id INT AUTO_INCREMENT PRIMARY KEY,               
            vehicle_id int NOT NULL,                    
            customer_id int NOT NULL,                   
            sign_datetime DATETIME NOT NULL,                  
            loc_begin_datetime DATETIME NOT NULL,             
            loc_end_datetime DATETIME NOT NULL,               
            returning_datetime DATETIME,                      
            price FLOAT NOT NULL,                             
            FOREIGN KEY (vehicle_id) REFERENCES Vehicle(id), 
            FOREIGN KEY (customer_id) REFERENCES Customer(id)
        )";
        $this->dbConnect->exec( $query );
    }

    public function createBillingTable() {
        $query = "
        CREATE TABLE IF NOT EXISTS billing (
            ID INT AUTO_INCREMENT PRIMARY KEY,           
            Contract_id INT NOT NULL,                     
            Amount FLOAT NOT NULL,                        
            FOREIGN KEY (Contract_id) REFERENCES Contract(id) 
        )";
        $this->dbConnect->exec( $query );
    }

    public function createVehicleTable() {
        $query = "
        CREATE TABLE IF NOT EXISTS vehicle (
            id INT AUTO_INCREMENT PRIMARY KEY,      
            licence_plate CHAR(255) NOT NULL,       
            informations TEXT,                      
            km INT NOT NULL   
        )";
        $this->dbConnect->exec( $query );
    }
}

?>