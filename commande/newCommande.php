<?php
require_once(__DIR__ . '/../config/config.php');       // un slash manquant avant "config"



function addCommande($commande) {
    $pdo = connectDB();
    $sql = "INSERT INTO commande (user_id, montant_total, commandee_le, statut) VALUES (:user_id, :montant_total, :commandee_le, :statut)";
    $stmt = $pdo->prepare($sql);

    try {
       
        $stmt->execute([
            'user_id' => $commande['user_id'],
            'montant_total' => $commande['montant_total'],
            'statut' => $commande['statut'],
            'commandee_le' => $commande['date']
        ]);
        
       
        $commandeId = $pdo->lastInsertId();
        
        return $commandeId;  
    } catch (PDOException $e) {
      
        echo "Erreur d'insertion dans la base de données : " . $e->getMessage();
        return false;  // Retourner false en cas d'erreur
    }
}

?>