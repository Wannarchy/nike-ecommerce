<?php
require_once (__DIR__.'/../config/config.php');


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_user = $_POST['user_id'];
    $adresse = $_POST['adresse'];
    $adresse_facturation = $_POST['adresse_facturation'];

    if (!empty($id_user) && !empty($adresse) && !empty($adresse_facturation)) {

   $data = [
        'user_id' => $id_user,
        'adresse' => $adresse,
        'adresse_facturation' =>  $adresse_facturation,
        
        
    ]; 

        addAdresseLivraison($data);
    
     header('Location: ../profile/profile.php ' );
     exit(); 
    }
 


    
} 



function addAdresseLivraison($data) {
   
    try {
        $pdo = connectDB();
        $sql = "update utilisateur set adresse = :adresse, adresse_facturation = :adress_facturation where user_id = :user_id";
        $stmt = $pdo->prepare($sql);
       
        $stmt->execute([
            'user_id' => $data['user_id'],
            'adresse' => $data['adresse'],
            'adress_facturation' => $data['adress_facturation'],
            
        ]);

        
        
       
        
    } catch (PDOException $e) {
      
        echo "Erreur d'insertion dans la base de données : " . $e->getMessage();
        return false;  // Retourner false en cas d'erreur
    }
}

function getAdresseLivraison($data) {
    try {
        $pdo = connectDB();
        $sql = "select utilisateur set adresse = :adresse, adresse_facturation = :adress_facturation where user_id = :user_id";
        $stmt = $pdo->prepare($sql);
       
        $stmt->execute([
            'user_id' => $data['user_id'],
            'adresse' => $data['adresse'],
            'adress_facturation' => $data['adress_facturation'],
            
        ]);

        
        
       
        
    } catch (PDOException $e) {
      
        echo "Erreur d'insertion dans la base de données : " . $e->getMessage();
        return false;  // Retourner false en cas d'erreur
    }
}



?>
