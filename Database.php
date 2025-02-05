<?php

require 'vendor/autoload.php';

class Database
{
    private $sqlConnection;
    private $mongoConnection;

    public function __construct()
    {
        // SQL Database Connection
        $this->connectSql();

        // MongoDB Connection
        $this->connectMongo();
    }

    private function connectSql()
    {
        try {
            $host = $_ENV['DB_HOST'];
            $dbname = $_ENV['DB_DBNAME'];
            $user = $_ENV['DB_USER'];
            $password = $_ENV['DB_PASSWORD'];

            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
            $this->sqlConnection = new PDO($dsn, $user, $password);
            $this->sqlConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo "MySQL Connection failed: " . $e->getMessage() . "\n";
        }
    }

    private function connectMongo()
    {
        try {
            $mongoUri = $_ENV['MDB_URI'];
            $client = new MongoDB\Client($mongoUri);
            $this->mongoConnection = $client->selectDatabase($_ENV['MDB_DBNAME']);
            
        } catch (\Exception $e) {
            echo "MongoDB Connection failed: " . $e->getMessage() . "\n";
        }
    }

    public function getSqlConnection()
    {
        return $this->sqlConnection;
    }

    public function getMongoConnection()
    {
        return $this->mongoConnection;
    }
}
?>



