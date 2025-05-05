<?php
// Activer l'affichage des erreurs pour déboguer (désactiver en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("functionLogin.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et nettoyer les données du formulaire
    $email = htmlspecialchars(trim($_POST['email']));
    $pwd = $_POST['password'];

    // Vérifier si tous les champs sont remplis
    if (!empty($email) && !empty($pwd)) {
        // Préparer les données pour la connexion
        $data = [
            'email' => $email,
            'password' => $pwd
        ];

        // Authentifier l'utilisateur
        if (login($data)) {
            // Redirection vers la page d'accueil après une connexion réussie
            header('Location: ../index.php');
            exit;
        } else {
            echo "<div class='error-message'>Un problème est survenu. Veuillez réessayer plus tard.</div>";
        }
    } else {
        echo "<div class='error-message'>Tous les champs sont obligatoires.</div>";
    }
}
?>






<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="icon" href="images/logo.png" type="image/png">

</head>
<body>
    <div class="login-container">
        <h2>Se connecter</h2>

        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Entrez votre email">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required placeholder="Entrez votre mot de passe">
            </div>

            <button type="submit">Connexion</button>
        </form>
        <p>Pas encore de compte ? <a href="register.php">S'inscrire</a></p>
    </div>
</body>
</html>


