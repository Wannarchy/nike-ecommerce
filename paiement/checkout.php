<?php
require(__DIR__ . '/../vendor/autoload.php');
require_once '../vendor/autoload.php'; 


// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '../config');
// $dotenv->load();
// var_dump($dotenv);


if (!isset($_SESSION['connectedUser']['id'])) {
    echo 'Vous devez etre connecter';
}




$stripeSecretKey = "sk_test_51RDcpHFRyzew27UaSSArpAAhs1zk9J51TOMbITmMlZBTMqOU3St2dYnpEDxNdgpfaVhiZMEfrCOQNrmaRxXAgULc007KgC6w02";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['panier'])) {
        // Initialisation de la variable pour le total du panier
        $totalPanier = 0;
        $line_items = [];
        $panier = [];  // Crée un tableau pour stocker les produits du panier
        
        // Parcours des produits envoyés dans le panier
        foreach ($_POST['panier'] as $produit) {
            $nom = $produit['nom'];
            $prix = $produit['prix'];
            $quantite = $produit['quantite'];
            $produitId = $produit['id']; // Assurez-vous que chaque produit a un champ 'id'

            // Ajoute chaque produit au tableau panier
            $panier[] = [
                'id' => $produitId,
                'nom' => $nom,
                'prix' => $prix,
                'quantite' => $quantite
            ];

            // Calcul du total du panier
            $totalPanier += $prix * $quantite;

            // Préparation des produits pour Stripe
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $nom,
                    ],
                    'unit_amount' => (int)($prix * 100), // Prix en centimes
                ],
                'quantity' => $quantite,
            ];
        }

        
        setcookie('panier', json_encode($panier), time() + (3600), "/"); 

      
     

        // Stripe paiement
        \Stripe\Stripe::setApiKey($stripeSecretKey);

        // Créer une session de paiement Stripe
        $checkout_session = \Stripe\Checkout\Session::create([
            "payment_method_types" => ['card'],
            "mode" => "payment",
            "success_url" => "http://localhost/e-commerce/e-commerce/paiement/succes.php?session_id={CHECKOUT_SESSION_ID}",
            "cancel_url" => "http://localhost/e-commerce/e-commerce/",
            "locale" => "auto",
            "line_items" => $line_items,
        ]);

        // Redirige vers la session de paiement Stripe
        http_response_code(303);
        header("Location: " . $checkout_session->url);
        exit();
    }
}
?>



