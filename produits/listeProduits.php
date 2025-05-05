<?php
require_once(__DIR__ . '/../config/config.php');  

function getProduits() {
    $pdo = connectDB();

$sql = "SELECT * FROM produits LIMIT 6";
$stmt = $pdo -> prepare($sql);
$stmt -> execute();
$produits = $stmt ->fetchAll(PDO::FETCH_ASSOC);

return $produits;
}

function getNouveauxProduits()
{
    $conn = connectDB();
    // Requête pour récupérer les produits sortis dans le dernier mois
    $sql = "SELECT * FROM produits WHERE date_sortie >= CURDATE() - INTERVAL 1 MONTH LIMIT 3";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $nouveaux_produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$nouveaux_produits) {
        $sql = "SELECT * FROM produits ORDER BY date_sortie DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $nouveaux_produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return $nouveaux_produits;
    
}



function getProduitsFilter() {
    
    $pdo = connectDB();  
    
    
    $sql = "SELECT * FROM produits WHERE 1";  

   
    if (isset($_POST['prix_min']) && isset($_POST['prix_max'])) {
        $prix_min = (float) $_POST['prix_min'];  
        $prix_max = (float) $_POST['prix_max'];

        // Ajout de la condition de prix dans la requête
        $sql .= " AND prix BETWEEN :prix_min AND :prix_max"; 
    }

    
    $sql .= " LIMIT 6";

   
    $stmt = $pdo->prepare($sql);

  
    if (isset($prix_min) && isset($prix_max)) {
        $stmt->bindParam(':prix_min', $prix_min, PDO::PARAM_STR);
        $stmt->bindParam(':prix_max', $prix_max, PDO::PARAM_STR);
    }

    $stmt->execute();

   
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $produits;
}

?>