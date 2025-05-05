<?php
require_once(__DIR__ . '/../config/config.php');       // un slash manquant avant "config"



function commandeList($user_id) {
    try {
        $pdo = connectDB();
        // Requête SQL pour récupérer les commandes de l'utilisateur avec les détails
        $sql = "SELECT c.id as commande_id, c.statut, c.montant_total, c.commandee_le, 
                       dc.quantite, p.nom as nom_produit, p.prix, p.image_url,l.status
                FROM commande c
                JOIN details_commande dc ON c.id = dc.commande_id
                JOIN produits p ON dc.produit_id = p.id
                JOIN livraison l ON c.id = l.commande_id WHERE c.user_id = :user_id
                ORDER BY c.commandee_le DESC";
                
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT); 
        $stmt->execute();
        
        $commandes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $commandes[] = $row;
        }
        //var_dump($commandes);
        return ['success' => true, 'data' => $commandes];
    } catch (Exception $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

?>