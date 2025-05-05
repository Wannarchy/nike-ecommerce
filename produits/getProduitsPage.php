<?php
require_once(__DIR__ . '/../config/config.php');  


function getProduitsPage() {
    $pdo = connectDB();

$sql = "SELECT * FROM produits";
$stmt = $pdo -> prepare($sql);
$stmt -> execute();
$produits = $stmt ->fetchAll(PDO::FETCH_ASSOC);

return $produits;
}




?>




