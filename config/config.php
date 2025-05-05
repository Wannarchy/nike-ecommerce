<?php

session_start();
function connectDB(){
    
$host = 'localhost';
$dbname = 'nike_basketball';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return$pdo;

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
}

function closeDB($pdo){
    $pdo=null;
}
?>
