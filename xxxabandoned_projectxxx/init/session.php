<?php
session_start();
if (!isset($_SESSION['user_id'])){    
    if (strpos($_SERVER['REQUEST_URI'],'item') !== false){
        echo "not logged in";
        header( "Location:".BASE_URL."src/user/login.php");
        exit();
    }
}
