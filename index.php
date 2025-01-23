
<?php
header( 'Content-Type: application/json' );
require_once("model/db_mysql.php");
require_once("model/db_mongo.php");
$db = new Database;
var_dump($db);

$db->getDbConnect();
$db->createCustomerTable();
$db->createVehicleTable();
$db->createContractTable();
$db->createBillingTable();

$db= new MongoDatabase;

