<?php

class Database {    
    private $host;  
    private $dbname; 
    private $username;
    private $password; 
    private $dbConnect;
    

    // Constructeur pour établir la connexion à la base de données
    public function __construct() {
        $this->host = getenv('DB_HOST');
        $this->dbname = getenv('DB_DBNAME');  
        $this->username = getenv('DB_USER');  
        $this->password = getenv('DB_PASSWORD'); 
        $this->connect();
    }

    // Méthode pour établir la connexion à la base de données
    public function connect(){
        //connecte a mysql DB
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname;
            $this->dbConnect = new PDO($dsn, $this->username, $this->password);
            $this->dbConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              // Gérer les erreurs PDO
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }        
    }

    // Méthode pour obtenir l'instance PDO
    public function getDbConnect() {
        return $this->dbConnect;
    }    
}
?>