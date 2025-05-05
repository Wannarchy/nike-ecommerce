<?php
require_once(__DIR__ . '/../config/config.php');       // un slash manquant avant "config"



function getCardInfo($user_id) {
   

    try {
        $pdo = connectDB();
   
            $sql = "SELECT card_type, last4  FROM paiement  WHERE user_id = :user_id ORDER BY date_paiement DESC LIMIT 1";
      
            $stmt = $pdo->prepare($sql);
        
           
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            $stmt->execute();
        
            return $result =  $stmt->fetch(PDO::FETCH_ASSOC);
                     
       
    } catch (PDOException $e) {
      
        echo "Erreur d'insertion dans la base de données : " . $e->getMessage();
        return false;  // Retourner false en cas d'erreur
    }
}

?>