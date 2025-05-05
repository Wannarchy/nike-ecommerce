<?php
require_once(__DIR__ . '/../panier/ajouterPanier.php');




if (isset($_SESSION['connectedUser']['id'])) {
    $user_id = $_SESSION['connectedUser']['id'];  

    
    echo "<h1>Echec de votre paiement, Vous allez etre redirigé a l'accueil</h1>";
   
    
    }

   

   



?>
<script type="text/javascript">
    setTimeout(function() {
        window.location.href = "http://localhost/e-commerce/e-commerce/"; 
    }, 4000); 
</script>