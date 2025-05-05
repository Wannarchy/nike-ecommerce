<?php
require_once (__DIR__.'/produits/listeProduits.php');
require_once (__DIR__.'/auth/functionLogin.php');
require_once (__DIR__.'/panier/ajouterPanier.php');




 ?>

<!DOCTYPE html>
<html lang="fr">
<!-- Déclaration du type de document HTML5 et définition de la langue du document -->
<head>
    <!-- Déclaration de l'encodage des caractères en UTF-8 pour supporter les caractères spéciaux -->
    <meta charset="UTF-8">
    <!-- Rendre le site responsive, s'adaptant à différents écrans comme ceux des smartphones -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Titre de la page affiché dans l'onglet du navigateur -->
    <title>Nike Basketball</title>
    <!-- Lien vers le fichier CSS pour styliser la page -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="images/logo.png" type="image/png">

    
</head>
   <!-- Début du contenu de la page -->
<body>

    <!-- En-tête contenant la barre de navigation -->
    <header>
        <nav>
            <div class="nav-container">
                <!-- Logo placé à gauche dans la barre de navigation -->
                <div class="logo">
                    <!-- Affichage de l'image du logo avec un texte alternatif si l'image ne se charge pas -->
                    <img src="images/logo.png" alt="Logo Nike" class="logo-img">
                </div>
                <!-- Menu de navigation avec des liens vers différentes sections de la page -->
                <ul class="nav-links">
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="#new-arrivals">Nouveautés</a></li>
                    <li><a href="./produits/product.php">Produits</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>

                <div class="account-cart">
                    <!-- Panier à droite de la barre de navigation avec une icône et le nombre d'articles -->
                    <div class="cart">
                        <img src="images/cart-icon1.jpg" alt="Panier" class="cart-icon">
                        <?php
                        if (isUserLoggedIn() && isset($_SESSION['connectedUser']['id'])):
                            $user_id = $_SESSION['connectedUser']['id'];
                            $cart_count = getCartCount($user_id);
                        ?>
                        <span id="cart-count"><?php echo $cart_count ?></span> <!-- Indicateur du nombre d'articles dans le panier -->
                        <?php endif; ?>
                    </div>

                    <div class="account">
                        <img src="images/account-icon.webp" alt="Compte" class="account-icon" id="account-icon">
                        <?php if (isUserLoggedIn()) { ?>
                            <span><?php echo htmlspecialchars($_SESSION['connectedUser']['nom']); ?></span>
                            <!-- Sous-menu pour le profil et la deconnexion -->
                        <div class="account-dropdown" id="account-dropdown">
                            <ul>
                                <li><a href="profile/profile.php">Mon Profil</a></li>
                                <li><a href="config/logout.php">Se Déconnecter</a></li>

                            </ul>
                        </div>
                    <?php } else { ?>
                                <a href="./auth/login.php">Se connecter</a>
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
                        <img src="<?= htmlspecialchars($cartget['image_url']) ?>" 
                             alt="<?= htmlspecialchars($cartget['nom']) ?>" 
                             style="width: 100px; height: auto; object-fit: cover; border-radius: 8px;">
                        
                        <div>
                            <p><strong><?= htmlspecialchars($cartget['nom']) ?></strong></p>
                            <p>Prix unitaire : <?= number_format($cartget['prix'], 2, ',', ' ') ?> $</p>
                            <p>Quantité : <?= intval($cartget['quantite']) ?></p>
                            
                        </div>
                    </div>
                    <div>
                    <form method="POST" action="./panier/deleteProduit.php">
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

    <!-- Section Accueil avec un slider (diaporama) -->
    <section id="home">
        <div class="slider">
            <!-- Première diapositive active par défaut -->
            <div class="slide active">
                <img src="images/basket1.jpg" alt="Nike Basket 1">
                <div class="caption">Nike Air Zoom BB NXT</div> <!-- Légende de l'image -->
            </div>
            <!-- Autres diapositives du slider -->
            <div class="slide">
                <img src="images/basket2.jpg" alt="Nike Basket 2">
                <div class="caption">Nike LeBron 18</div>
            </div>
            <div class="slide">
                <img src="images/basket3.jpg" alt="Nike Basket 3">
                <div class="caption">Nike KD 14</div>
            </div>
            <!-- Boutons pour naviguer dans les diapositives -->
            <button class="prev">❮</button>
            <button class="next">❯</button>
        </div>
    </section>

    <!-- Section Nouveautés avec une grille de produits -->
    <section id="new-arrivals">
        <h2>Nouveautés</h2> <!-- Titre de la section -->
        <div class="product-grid">
            <?php

        $newproduits = getNouveauxProduits();

            // Vérification s'il y a des nouveaux produits

            // Si des nouveaux produits sont disponibles, on les affiche
            foreach ($newproduits as $produit): ?>
                <div class="product" data-product-id= "<?php echo $produit['id']; ?>" >
                  
                    <img src="<?php echo $produit['image_url']; ?>"
                        alt="<?php echo htmlspecialchars($produit['nom']); ?>" class="product-image" >
                    <p><?php echo htmlspecialchars($produit['nom']); ?></p>
                    <p><?php echo number_format($produit['prix'], 2, ',', ' '); ?> €</p>


                    <?php if (isUserLoggedIn() && isset($_SESSION['connectedUser']['id'])) : ?>
                    <form action="./panier/ajouterPanier.php"  method="POST">

                    
                    <input type="hidden" id="produit_id" name="produit_id" value="<?php echo $produit['id'];?>">
                    <input type="hidden" id="quantite" name="quantite" value="1">
                    <input type="hidden" id="prix" name="prix" value="<?php echo $produit['prix'];?>">
                  
                        
                        <input type="hidden" id="id_user" name="id_user" value="<?php echo htmlspecialchars($_SESSION['connectedUser']['id']);?>">
                        <button type="submit" class="add-to-cart" data-product-id="<?php echo $produit['id']; ?>">Ajouter au panier</button>
                    </form>
                        <?php else: ?>
                           
                            <button type="submit" class="add-to-cart" >Ajouter au panier</button>
                    <?php endif; ?>
                    
           

              

                </div>
            <?php endforeach; ?>
         
        </div>
    </section>





    
    <!-- Section Tous les Produits -->
    <section id="products">
        <h2>Tous les Produits</h2>

        <div class="product-grid">
            <?php
           
            $produits = getProduits();
            
            foreach($produits as $produit):?>
            <div class="product">
                  
                    <img src="<?php echo $produit['image_url']; ?>"
                        alt="<?php echo htmlspecialchars($produit['nom']); ?>" class="product-image" >
                    <p><?php echo htmlspecialchars($produit['nom']); ?></p>

                    

                    <?php if (isUserLoggedIn() && isset($_SESSION['connectedUser']['id'])) : ?>
                    <form action="./panier/ajouterPanier.php"  method="POST">

                    
                    <input type="hidden" id="produit_id" name="produit_id" value="<?php echo $produit['id'];?>">
                    <input type="hidden" id="quantite" name="quantite" value="1">
                    <input type="hidden" id="prix" name="prix" value="<?php echo $produit['prix'];?>">
                  
                        
                        <input type="hidden" id="id_user" name="id_user" value="<?php echo htmlspecialchars($_SESSION['connectedUser']['id']);?>">
                        <button type="submit" class="add-to-cart" data-product-id="<?php echo $produit['id']; ?>">Ajouter au panier</button>
                    </form>
                        <?php else: ?>
                           
                            <button type="submit" class="add-to-cart" >Ajouter au panier</button>
                    <?php endif; ?>

                

                </div>
            <?php endforeach; ?>
         
        </div>
 
    </section>

    <!-- Section Contact avec un formulaire -->
    <section id="contact">
        <h2>Contactez-nous</h2>
        <form id="contact-form">
            <!-- Champs du formulaire avec étiquettes et champs obligatoires -->
            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" id="name" name="name" placeholder="Votre nom" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Votre email" required>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" placeholder="Votre message" required></textarea>
            </div>
            <button type="submit">Envoyer</button>
        </form>
    </section>


    <!-- Pied de page -->
    <footer>
        <div class="footer-container">
            <!-- Liens vers les différentes sections de la page -->
            <div class="footer-links">
                <a href="#home">Accueil</a>
                <a href="#new-arrivals">Nouveautés</a>
                <a href="#products">Produits</a>
                <a href="#contact">Contact</a>
            </div>
            <!-- Liens vers les réseaux sociaux -->
            <div class="footer-social">
                <a href="https://facebook.com" target="_blank"><img src="images/facebook-icon.png" alt="Facebook"></a>
                <a href="https://twitter.com" target="_blank"><img src="images/x-icon.png" alt="x"></a>
            </div>
        </div>
        <!-- Texte du copyright -->
        <p>&copy; 2024 Nike Basketball. Tous droits réservés.</p>
    </footer>
    <!-- Lien vers un fichier JavaScript externe -->
    <script src="js/script.js"></script>
</body>
</html>