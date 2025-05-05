<?php
require_once(__DIR__ . '/../config/config.php');       // un slash manquant avant "config"



function addCommandeDetail($commande_detail) {
    $pdo = connectDB();
    $sql = "INSERT INTO details_commande (commande_id, produit_id, quantite,  prix_a_achat 	) VALUES (:commande_id, :produit_id, :quantite, :prix_a_achat)";
    $stmt = $pdo->prepare($sql);

    try {

        foreach ($commande_detail as $commande) {
            $stmt->execute([
                'commande_id' => $commande['commande_id'],
                'produit_id' => $commande['produit_id'],
                'quantite' => $commande['quantite'],
                'prix_a_achat' => $commande['prix_unit']
            ]);
        }
       
            
     
            
       
    } catch (PDOException $e) {
      
        echo "Erreur d'insertion dans la base de données : " . $e->getMessage();
        return false;  // Retourner false en cas d'erreur
    }
}

?>