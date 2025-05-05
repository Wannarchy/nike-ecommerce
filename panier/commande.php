<?php
require_once(__DIR__ . '/ajouterPanier.php');
require_once(__DIR__ . '/../auth/functionLogin.php');


// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['connectedUser']['id'])) {
    echo 'Utilisateur non connecté';
    exit;
}

// Vérifier si le panier existe et n'est pas vide

?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Récapitulatif de votre commande</title>
        <link rel="stylesheet" href="../css/commande.css">
        <link rel="stylesheet" href="../css/style.css">
        <link rel="icon" href="../images/logo.png" type="image/png">
       
    </head>
    <body>

    <header>
        <nav>
            <div class="nav-container">
                <!-- Logo placé à gauche dans la barre de navigation -->
                <div class="logo">
                    <!-- Affichage de l'image du logo avec un texte alternatif si l'image ne se charge pas -->
                    <img src="../images/logo.png" alt="Logo Nike" class="logo-img">
                </div>
                <!-- Menu de navigation avec des liens vers différentes sections de la page -->
                <ul class="nav-links">
                    <li><a href="../index.php">Accueil</a></li>
                    <li><a href="#new-arrivals">Nouveautés</a></li>
                    <li><a href="../produits/product.php">Produits</a></li>
                    <li><a href="../index.php#contact">Contact</a></li>
                </ul>

                <div class="account-cart">
                    <!-- Panier à droite de la barre de navigation avec une icône et le nombre d'articles -->
                    <div class="cart">
                        <img src="../images/cart-icon1.jpg" alt="Panier" class="cart-icon">
                        <?php
                        if (isUserLoggedIn() && isset($_SESSION['connectedUser']['id'])):
                            $user_id = $_SESSION['connectedUser']['id'];
                            $cart_count = getCartCount($user_id);
                        ?>
                        <span id="cart-count"><?php echo $cart_count ?></span> <!-- Indicateur du nombre d'articles dans le panier -->
                        <?php endif; ?>
                    </div>

                    <div class="account">
                        <img src="../images/account-icon.webp" alt="Compte" class="account-icon" id="account-icon">
                        <?php if (isUserLoggedIn()) { ?>
                            <span><?php echo htmlspecialchars($_SESSION['connectedUser']['nom']); ?></span>
                            <!-- Sous-menu pour le profil et la deconnexion -->
                        <div class="account-dropdown" id="account-dropdown">
                            <ul>
                                <li><a href="../profile/profile.php">Mon Profil</a></li>
                                <li><a href="../config/logout.php">Se Déconnecter</a></li>

                            </ul>
                        </div>
                    <?php } else { ?>
                                <a href="../auth/login.php">Se connecter</a>
                    <?php } ?>

                        

                    </div>

                    <!--popup du panier pour afficher les produits ajoutes-->
                    <div id="cart-modal" class="cart-popup">
                        <div class="cart-popup-content">
                            <span class="close-popup">&times;</span>
                           
                            <h3>Votre Panier</h3>
                            <div id="cart-items">
                            <?php
if (isset($_SESSION['connectedUser']['id'])) {
    $user_id = $_SESSION['connectedUser']['id'];
    $panier = getPanierTest($user_id);
    if (isset($_SESSION['connectedUser']['id'])) {
        if (!empty($panier)) {
            $totalPanier = 0; // Initialiser la variable du total du panier
            foreach ($panier as $cartget): ?>
                <div class="cart-items-container" >
                    
                    <div id="my-items">
                        <img src="../<?= htmlspecialchars($cartget['image_url']) ?>" 
                             alt="<?= htmlspecialchars($cartget['nom']) ?>" 
                             style="width: 100px; height: auto; object-fit: cover; border-radius: 8px;">
                        
                        <div>
                            <p><strong><?= htmlspecialchars($cartget['nom']) ?></strong></p>
                            <p>Prix unitaire : <?= number_format($cartget['prix'], 2, ',', ' ') ?> $</p>
                            <p>Quantité : <?= intval($cartget['quantite']) ?></p>
                            
                        </div>
                    </div>
                    <div>
                    <form method="POST" action="../deleteProduit.php">
                        <input type="hidden" name="produit_delete" value="<?= $cartget['id'] ?>">
                        
                        <button type="submit" class="cart-delete-item">
                            X
                        </button>
                    </form>
                    </div>
                    
    
                </div>
                <?php 
                // Ajouter le total de l'article au total global du panier
                $totalPanier += $cartget['prix'] * $cartget['quantite']; 
            endforeach; ?>
    
            <div class="total-panier">
                <p><strong>Total du panier : <?= number_format($totalPanier, 2, ',', ' ') ?> $</strong></p>
            </div>
    
            
        <?php } else { ?>
            
            <p>Votre panier est vide.</p>
        <?php }
    } else {
        echo "<p>Vous devez être connecté pour voir votre panier.</p>";
        
    }
}
?>


                              
                          
                               
                            </div>

                            <?php
// Vérifier si l'utilisateur est connecté et que le panier existe
if (isset($_SESSION['connectedUser']['id'])) {
    $panierVide = !empty($panier); // Vérifier si le panier est vide

    if ($panierVide) {
        // Si le panier n'est pas vide, afficher le bouton "Passer à la caisse"
        echo '<a href="./panier/commande.php"><button id="checkout">Passer à la caisse</button></a>';
    }else{
        echo '<button id="checkout">Passer à la caisse</button>';
    }
} 

?>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

        <div class="container">



        <!-- <?php
// Vérifier si l'utilisateur est connecté et que le panier existe



?> -->




        
<?php if (isUserLoggedIn() && isset($_SESSION['connectedUser']['id'])) : ?>
    
    <?php if (isset($panier) && !empty($panier)) : ?>
        
        <h1>Récapitulatif de votre commande</h1>

        <div class="commande-container">
            <?php foreach ($panier as $produit): ?>
                <div class="produit">
                    <img src="../<?= htmlspecialchars($produit['image_url']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>">
                    <div class="produit-details">
                        <p><strong><?= htmlspecialchars($produit['nom']) ?></strong></p>
                        <p>Prix unitaire : <?= number_format($produit['prix'], 2, ',', ' ') ?> $</p>
                        <p>Quantité : <?= intval($produit['quantite']) ?></p>
                    </div>
                    
                </div>
            <?php endforeach; ?>
        </div>

        <div class="commande-total">
            <p>Total du panier : <?= number_format($totalPanier, 2, ',', ' ') ?> $</p>
        </div>

        <div class="button-container">
            <form action="../paiement/checkout.php" method="post">
                <?php foreach ($panier as $index => $produit): ?>
                    <input type="hidden" name="panier[<?= $index ?>][id]" value="<?= htmlspecialchars($produit['id']) ?>">
                    <input type="hidden" name="panier[<?= $index ?>][nom]" value="<?= htmlspecialchars($produit['nom']) ?>">
                    <input type="hidden" name="panier[<?= $index ?>][prix]" value="<?= $produit['prix'] ?>">
                    <input type="hidden" name="panier[<?= $index ?>][quantite]" value="<?= $produit['quantite'] ?>">
                <?php endforeach; ?>
                <button type="submit">Finaliser ma commande</button>
            </form>
        </div>

    <?php else : ?>

        <p>Votre panier est vide. Ajoutez des produits avant de passer à la caisse.</p>

    <?php endif; ?>

<?php else : ?>

    <p>Veuillez vous connecter pour voir votre commande.</p>

<?php endif; ?>


        </div>
    

     <script src="../js/navScript"></script>
    </body>
 </html>