<h1>BIBLIOTHEQUE EASYLOC</h1>
<?php
require_once("model/db_mysql.php");
require_once("model/db_mongo.php");
$db = new Database;
echo ("<pre>");
var_dump($db);
echo ("<pre>");
$db->getDbConnect();

$db= new MongoDatabase;
echo ("<pre>");
var_dump($db);
echo ("<pre>");
$cust=$db->getCustomers();
$document = [
    'nom' => 'John Doe',
    'email' => 'john.doe@example.com',
    'age' => 30
];

$insertedId = $cust->find()->toArray();
echo ("<pre>");
var_dump($insertedId);
echo ("<pre>");