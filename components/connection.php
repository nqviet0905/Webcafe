<?php

$db_host   = 'localhost';       
$db_name   = 'webcafe';
$db_user   = 'root';            
$db_pass   = '';              
try {
   
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
} catch(PDOException $e) {
    die("Không kết nối được database: " . $e->getMessage());
}

function unique_id(){
    $chars = '0123456789abcdefghijklnmopqrstuvwxyzABCDEFGHIJKLNMOPQRSTUVWXYZ';
    $charsLength = strlen($chars);
    $randomString = '';
    
    for($i = 0; $i < 20; $i++){
        $randomString .= $chars[mt_rand(0, $charsLength - 1)];
    }
    return $randomString;
}
?>
