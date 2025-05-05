<?php

require_once("../config/config.php");

function insertUser($data) {
    // Obtenir la connexion à la base de données
    $link = connectDB();
    
    if (!$link) {
        echo "Problème de connexion à la base de données.";
        return false;
    }

    // Vérifier si l'utilisateur existe déjà
    if (!checkExistUser($data['email'])) {
        // Requête d'insertion
        $req = "INSERT INTO utilisateur (Nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)";
        $stmt = $link->prepare($req);
        
        if ($stmt->execute(array_values($data))) {
            // Récupérer l'ID du dernier utilisateur inséré
            $idUser = $link->lastInsertId();
            $user = array_merge(['id' => $idUser], $data);

            // Enregistrer l'utilisateur dans la session
            $_SESSION['connectedUser'] = $user;

            return true;
        } else {
            // Gestion des erreurs SQL
            var_dump($stmt->errorInfo());
            return false;
        }
    } else {
        echo "<div class='error-message'>L'utilisateur existe déjà.</div>";
        return false;
    }
}

function checkExistUser($email) {
    // Obtenir la connexion à la base de données
    $link = connectDB();

    if (!$link) {
        echo "Problème de connexion à la base de données.";
        return false;
    }

    // Requête pour vérifier si l'utilisateur existe
    $req = "SELECT COUNT(*) AS nombre FROM utilisateur WHERE email = ?";
    $stmt = $link->prepare($req);

    if ($stmt->execute([$email])) {
        $value = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si un utilisateur avec cet email existe
        if ($value['nombre'] > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        // Gestion des erreurs SQL
        var_dump($stmt->errorInfo());
        return false;
    }
}


?>