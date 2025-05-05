<?php
require_once(__DIR__ . '/../commande/newCommande.php');
require_once(__DIR__ . '/../commande/commandeDetail.php');
require_once(__DIR__ . '/./newPaiement.php');
require_once(__DIR__ . '/../panier/emptyCart.php');
require '../vendor/autoload.php';




if (isset($_SESSION['connectedUser']['id'])) {
    $user_id = $_SESSION['connectedUser']['id'];  

    $stripeSecretKey = "sk_test_51RDcpHFRyzew27UaSSArpAAhs1zk9J51TOMbITmMlZBTMqOU3St2dYnpEDxNdgpfaVhiZMEfrCOQNrmaRxXAgULc007KgC6w02";
    \Stripe\Stripe::setApiKey($stripeSecretKey);
    if (isset($_GET['session_id'])) {
        $session_id = $_GET['session_id'];
    
        // Récupérer la session Stripe
        $checkout_session = \Stripe\Checkout\Session::retrieve($session_id);
    
        // Si tu veux accéder au PaymentIntent lié
        $payment_intent = \Stripe\PaymentIntent::retrieve($checkout_session->payment_intent);
    
        // Récupérer le montant payé en euros
        $total_payer = $payment_intent->amount_received / 100; // Stripe travaille en centimes
    
      
        $payment_intent = \Stripe\PaymentIntent::retrieve($checkout_session->payment_intent);
       // $payment = \Stripe\PaymentIntent::retrieve($checkout_session->statut);
       $payment_id = $payment_intent->id;
       $status = $payment_intent->status;
      
       $payment_method_id = $payment_intent->payment_method;
       $payment_method = \Stripe\PaymentMethod::retrieve($payment_method_id);
       $card_details = $payment_method->card;


       $card_type = $card_details->brand;
       $last4 = $card_details->last4;

   
       
    
        $status_message = '';

        // Utilisation du switch pour modifier la valeur de status_message
        switch ($status) {
            case 'succeeded':
                $status_message = 'Le paiement a été effectué avec succès !';
                break;
            
            case 'requires_payment_method':
                $status_message = 'Le paiement nécessite une autre méthode de paiement.';
                break;
        
            case 'requires_confirmation':
                $status_message = 'Le paiement nécessite une confirmation.';
                break;
        
            case 'requires_action':
                $status_message = 'Le paiement nécessite une action supplémentaire de votre part.';
                break;
        
            case 'failed':
                $status_message = 'Le paiement a échoué. Veuillez réessayer.';
                break;
        
            case 'canceled':
                $status_message = 'Le paiement a été annulé.';
                break;
        
            case 'expired':
                $status_message = 'Le paiement a expiré.';
                break;
        
            case 'processing':
                $status_message = 'Le paiement est en cours de traitement.';
                break;
        
            default:
                $status_message = 'Statut du paiement inconnu.';
                break;
        }
    } 

    

    if (isset($_COOKIE['panier'])) {
        $panier = json_decode($_COOKIE['panier'], true);  // Récupère et décode le cookie contenant le panier
        $dateActuelle = date('Y-m-d H:i:s');
        // Affichage du récapitulatif de la commande
        echo "<h1>Commande reussie, vous allez etre redirectionné a l'accueil</h1>";
        $commande = [
            'user_id' => $user_id,
            'statut' => 'en attente',
            'montant_total' => $total_payer,
            'date' =>  $dateActuelle
            
            
           
        ];

        $commandeId = addCommande($commande);




        foreach ($panier as $produit) {
          $commande_detail[]  = [
            
                'commande_id' => $commandeId,
                'produit_id' => $produit['id'],
                'prix_unit' => $produit['prix'],
                'quantite' => $produit['quantite'],
            
       
          ];
           
        }
        addCommandeDetail($commande_detail);


        $paiement = [
            'user_id' => $user_id,
            'commande_id' => $commandeId,
            'montant_total' => $total_payer,
            'date' =>  $dateActuelle,
            'statut' => $status_message,
            'montant_total' => $total_payer,
            'transaction_id' => $payment_id,
            'card_type' =>  $card_type,
            'last4' => $last4


           
        ];
        addPaiement($paiement);

        // Supprimer le cookie 'panier'
setcookie('panier', '', time() - 3600, '/'); // expire le cookie immédiatement
emptyCart($user_id);

    }

   
}else{
    echo "Vous devez etre connecter";
}

?>
   




    <script type="text/javascript">
    setTimeout(function() {
        window.location.href = "http://localhost/e-commerce/e-commerce/"; 
    }, 4000); 
</script> 