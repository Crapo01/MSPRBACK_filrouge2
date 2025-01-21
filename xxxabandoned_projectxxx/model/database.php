<?php
require_once('../config/global.php');


class Database {
    private $host = DB_HOST;  // Hôte de la base de données
    private $dbname = DB_NAME;  // Nom de la base de données
    private $username = DB_USER;  // Nom d'utilisateur de la base de données
    private $password = DB_PASSWORD;  // Mot de passe de la base de données
    private $db1;
    

    // Constructeur pour établir la connexion à la base de données
    public function __construct() {
        $this->connect();
    }

    // Méthode pour établir la connexion à la base de données
    private function connect() {
        //connecte a mysql DB
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname;
            $this->db1 = new PDO($dsn, $this->username, $this->password);
            $this->db1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Gérer les erreurs PDO
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }        
    }

    // Méthode pour obtenir l'instance PDO
    public function getDb1() {
        return $this->db1;
    }    
}
?>