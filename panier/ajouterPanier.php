<?php
require_once(__DIR__.'/../config/config.php');
$user_id = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et nettoyer les données du formulaire
    $id_produit = intval($_POST['produit_id']);
    $id_utilisateur = intval($_POST['id_user']); 
    $quantite = intval($_POST['quantite']); 
    $prix = floatval($_POST['prix']); 

   // $produi_id = isset($_POST['produit_id']) ? intval($_POST['produit_id'])
   

    // Vérifier si tous les champs sont remplis
    if (!empty($id_produit) && !empty($id_utilisateur) && !empty($quantite)  && !empty($prix)) {
        // Préparer les données pour la connexion
        $data = [
            'produit_id' => $id_produit,
            'user_id' => $id_utilisateur,
            'quantite' => $quantite,
            'prix' => $prix
            
        ];  
            
        ajoutPanier($data);


        if (isset($_SERVER['HTTP_REFERER'])) {
           
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit(); 
        } else {
          
            header('Location: ../index.php');
            exit();
        }
        
            
        exit();
       
    }
}






function ajoutPanier($data){

   

    $pdo = connectDB();

    $sql = "SELECT * FROM panier WHERE user_id = :user_id AND produit_id = :produit_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'user_id' => $data['user_id'],
        'produit_id' => $data['produit_id']
    ]);

    // Si le produit existe déjà dans le panier, on met à jour la quantité
    if ($stmt->rowCount() > 0) {
        $sql = "UPDATE panier SET quantite = quantite + :quantite WHERE user_id = :user_id AND produit_id = :produit_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'quantite' => $data['quantite'],
            'user_id' => $data['user_id'],
            'produit_id' => $data['produit_id'],
        ]);
     
    } else {

           
        // Sinon, on insère un nouveau produit dans le panier
        $sql = "INSERT INTO panier (user_id, produit_id, quantite, prix) VALUES (:user_id, :produit_id, :quantite, :prix)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'user_id' => $data['user_id'],
            'produit_id' => $data['produit_id'],
            'quantite' => $data['quantite'],
            'prix' => $data['prix']

        ]);
     
    }

}

function getPanierTest($user_id){
    $pdo = connectDB();  
    $sql = "SELECT p.id, p.nom, p.prix, p.image_url, c.quantite FROM produits p JOIN panier c ON p.id = c.produit_id where c.user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
    
}



function getCartCount($user_id) {
    $pdo = connectDB();
    $sql = "SELECT SUM(quantite) as total_items FROM panier WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_items'] ? $result['total_items'] : 0;
}

function getCartItems($user_id){
    $pdo = connectDB();
     //$sql = "SELECT p.id, p.nom, p.prix, p.image_url, c.quantite FROM produits p JOIN panier c ON p.id = c.produit_id where c.utilisateur_id = :user_id";
    $sql = "SELECT p.id, p.nom, p.prix, p.image_url, c.quantite FROM produits p JOIN panier c ON p.id = c.produit_id where c.user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->excute(['user_id' => $user_id]);
    
    return $result =  $stmt->fetch(PDO::FETCH_ASSOC);
}



function emptyCart($user_id) {
    $pdo = connectDB();
    $stmt = $pdo->prepare('DELETE FROM panier WHERE user_id = :user_id');
    return $stmt->execute(['user_id' => $user_id]);
}








?>