<?php
require_once(__DIR__ . '/../config/config.php');       // un slash manquant avant "config"



function addPaiement($paiement) {
    $pdo = connectDB();
    $sql = "INSERT INTO paiement (user_id, commande_id, montant, date_paiement, statut_paiement, transaction_id,card_type ,last4 	) VALUES (:user_id, :commande_id, :montant, :date_paiement, :statut_paiement, :transaction_id, :card_type , :last4)";
    $stmt = $pdo->prepare($sql);

    try {

    
            $stmt->execute([
                'user_id' => $paiement['user_id'],
                'commande_id' => $paiement['commande_id'],
                'montant' => $paiement['montant_total'],
                'date_paiement' => $paiement['date'],
                'statut_paiement' => $paiement['statut'],
                'transaction_id' => $paiement['transaction_id'],
                'card_type' => $paiement['card_type'],
                'last4' =>  $paiement['last4']

            ]);
        
           
              
    
        
            
       
    } catch (PDOException $e) {
      
        echo "Erreur d'insertion dans la base de données : " . $e->getMessage();
        return false;  // Retourner false en cas d'erreur
    }
}

?>