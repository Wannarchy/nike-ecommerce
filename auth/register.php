<?php

include ('./functionInscription.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Inscription</title>
    <link rel="icon" href="images/logo.png" type="image/png">
    <link rel="stylesheet" href="../css/register.css">
</head>
<body>
    <div class="signup-container">

        <h2>Inscription</h2>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récuperer et nettoyer les données du formulaire
            $nom = htmlspecialchars(trim($_POST['nom']));            
            $email = htmlspecialchars(trim($_POST['email']));
            $pwd = $_POST['pwd'];

            // Vérifier si tous les champs sont remplis
            if (!empty($nom) && !empty($email) && !empty($pwd)) {
                // Hacher le mot de passe pour la sécurité



                $password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';

                if (!preg_match($password_pattern, $pwd)) {
                    echo "<div class='error-message'>
                            Le mot de passe doit contenir au moins 8 caractères, 
                            avec une majuscule, une minuscule, un chiffre et un caractère spécial.
                          </div>";
                }else{
                    
                $hashed_password = password_hash($pwd, PASSWORD_BCRYPT);

                // Préparer les données pour l'insertion
                $data = [
                    'Nom' => $nom,
                    'email' => $email,
                    'mot_de_passe' => $hashed_password,
                    'role' => 'client' 
                ];

                // Insérer l'utilisateur dans la base de données
                if (insertUser($data)) {
                    // Redirection vers la page d'acceuil après une inscription réussie
                    header('Location: login.php');
                    exit;
                } else {
                    echo "<div class='error-message'>Un problème est survenu. Veuillez réessayer plus tard.</div>";
                }
                }



            } else {
                echo "<div class='error-message'>Tous les champs sont obligatoires.</div>";
            }
        }
        ?>

        <form action="#" method="post">
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" placeholder="test@test.com" required>
            </div>
            <div class="form-group">
                <label for="pwd">Mot de passe :</label>
                <input type="password" id="pwd" name="pwd" required>
            </div>
            <button type="submit" name="submit">S'inscrire</button>
          </form>
          <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
    </div>    
</body>
</html> 