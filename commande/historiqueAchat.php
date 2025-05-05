<?php
require_once(__DIR__ . '/../config/config.php');       // un slash manquant avant "config"



function historiqueAchats($user_id) {
    try {
        $pdo = connectDB();
        // Requête SQL pour récupérer les commandes de l'utilisateur avec les détails
        $sql = "
      SELECT 
    c.id AS commande_id,
    c.commandee_le AS date_commande,
    c.statut AS statut_commande,
    c.montant_total AS total_commande,
    p.statut_paiement,
    p.date_paiement
FROM 
    commande c
JOIN 
    paiement p ON c.id = p.commande_id
WHERE 
    c.user_id = :user_id
ORDER BY 
    c.commandee_le DESC";

                
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Utilisation de fetchAll pour récupérer tous les résultats
        $achats = $stmt->fetchAll(PDO::FETCH_ASSOC);

       
        
        // Retourner les données sous forme de tableau
        return ['success' => true, 'data' => $achats];
    } catch (Exception $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}


?>