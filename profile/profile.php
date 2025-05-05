<?php
require_once("../auth/functionLogin.php");
require_once('../commande/commandeList.php');
require_once('../commande/historiqueAchat.php');
require_once('../paiement/infoPaiement.php');


if (!isset($_SESSION['connectedUser']['id'])) {
    echo 'Utilisateur non connecté';
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Mon profil</title>
	<link rel="stylesheet" href="../css/profile.css">
    <link rel="icon" href="images/logo.png" type="image/png">

</head>

<body>
	<div class="container">

		<div class="sidebar">
			<h2> Mon Profil</h2>
			<ul>
                <li><a href="../index.php">Accueil</a></li>
				<li><a href="#orders">Commandes</a></li>
				<li><a href="#history">Historique des achats</a></li>
				<li><a href="#payment-methods">Méthodes de paiement</a></li>
				<li><a href="#profile-info">Informations personnelles</a></li>
				<li><a href="../config/logout.php" class="logout-btn">Se Déconnecter</a></li>
			</ul>


		</div>


		<div class="main-content">
			<h1>Bienvenue</h1>
			<div id="orders" class="orders">
    <h2>Mes Commandes</h2>
    
	<?php
$user_id = $_SESSION['connectedUser']['id'];

$result = commandeList($user_id);


if ($result['success']) {
    $commandes = $result['data'];

    if (count($commandes) > 0) {
        $grouped_orders = [];

        // Grouper les commandes par commande_id
        foreach ($commandes as $row) {
            $commande_id = $row['commande_id'];

            if (!isset($grouped_orders[$commande_id])) {
                $grouped_orders[$commande_id] = [
                    'commande_id' => $commande_id,
                    'montant_total' => $row['montant_total'],
                    'statut' => $row['statut'],
                    'status' => $row['status'],
                    'date_commande' => date('d/m/Y à H:i', strtotime($row['commandee_le'])),
                    'articles' => []
                ];
            }

            // Ajouter chaque article à la commande
            $grouped_orders[$commande_id]['articles'][] = $row;
        }

        // Afficher les commandes regroupées
        ?>
        <table>
            <thead>
                <tr>
                    <th>ID de la commande</th>
                    <th>Total de la commande</th>
                    <th>Articles</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Date de commande</th>
                    <th>Statut</th>
                    <th>Livraison</th>
                </tr>
            </thead>
            <tbody>
        <?php

        foreach ($grouped_orders as $order) {
         
            ?>
            <tr>
                <td rowspan="<?php echo count($order['articles']); ?>"><?php echo $order['commande_id']; ?></td>
                <td rowspan="<?php echo count($order['articles']); ?>"><?php echo number_format($order['montant_total'], 2); ?> €</td>
                <td>
                    <?php echo htmlspecialchars($order['articles'][0]['nom_produit']); ?>
                </td>
                <td>
                    <?php echo $order['articles'][0]['quantite']; ?>
                </td>
                <td>
                    <?php echo number_format($order['articles'][0]['prix'], 2); ?> €
                </td>
                <td rowspan="<?php echo count($order['articles']); ?>"><?php echo $order['date_commande']; ?></td>
                <td rowspan="<?php echo count($order['articles']); ?>"><?php echo $order['statut']; ?></td>
                <td rowspan="<?php echo count($order['articles']); ?>"><?php echo($order['status']); ?></td>

                

                    
            </tr>
            <?php

            // Afficher les autres articles de la même commande
            for ($i = 1; $i < count($order['articles']); $i++) {
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['articles'][$i]['nom_produit']); ?></td>
                    <td><?php echo $order['articles'][$i]['quantite']; ?></td>
                    <td><?php echo number_format($order['articles'][$i]['prix'], 2); ?> €</td>
                </tr>
                <?php
            }
        }
        ?>
            </tbody>
        </table>
        <?php
    } else {
        echo "<p>Vous n'avez encore passé aucune commande.</p>";
    }
} else {
    echo "<p>Erreur lors de la récupération des commandes: " . $result['message'] . "</p>";
}
?>
</div>
			<!-- Section Historique des achats -->
			<!-- <div id="history" class="orders"> -->
			<div id="history" class="orders">
    <h2>Historique des Achats</h2>
    
 
    
	<?php
// Récupérer l'historique des achats depuis la base de données
$result = historiqueAchats($user_id); // Fonction à définir pour récupérer les achats

if ($result['success']) {
    $achats = $result['data'];

    if (count($achats) > 0) {
        echo '<table>';
        echo '<thead><tr><th>Numéro de commande</th><th>Date</th><th>Statut</th><th>Total</th></tr></thead>';
        echo '<tbody>';

        foreach ($achats as $achat) {
            // Formater la date
            $date_achat = date('d/m/Y', strtotime($achat['date_paiement']));
            // Formater le montant total
            $montant_total = number_format($achat['total_commande'], 2, ',', ' ') . ' €';
            ?>

            <tr>
                <td>#<?php echo htmlspecialchars($achat['commande_id']); ?></td>
                <td><?php echo $date_achat; ?></td>
                <td><?php echo htmlspecialchars($achat['statut_paiement']); ?></td>
                <td><?php echo $montant_total; ?></td>
            </tr>

            <?php
        }

        echo '</tbody></table>';
    } else {
        echo "<p>Aucun achat effectué pour le moment.</p>";
    }
} else {
    echo "<p>Erreur lors de la récupération de l'historique des achats.</p>";
}
?>



			</div>
			<?php
					$paiementInfo = getCardInfo($user_id);
					echo '<div id="payment-methods" class="payment-methods">';
					echo    '<h2>Méthodes de paiement</h2>';
					
					// Vérification si l'info de paiement existe
					if (is_array($paiementInfo) && isset($paiementInfo['card_type']) && isset($paiementInfo['last4'])) {
						// Affichage des informations de la carte
						echo '<p>Carte : ' . htmlspecialchars($paiementInfo['card_type']) . ' ( **** **** **** ' . htmlspecialchars($paiementInfo['last4']) . ')</p>';
					} else {
						echo '<p>Aucune information de paiement disponible.</p>';
					}
					
					echo '</div>';
			?>
			<!-- Section Informations personnelle -->
			<div id="profile-info" class="profile-info">
				<h2>Informations personnelles</h2>
				<p>Nom: <?php echo htmlspecialchars($_SESSION['connectedUser']['nom']); ?></p>
				<p>Email: <?php echo htmlspecialchars($_SESSION['connectedUser']['email']); ?></p>
				
			</div>

            <div id="profile-info" class="profile-info">
				<h2>Adresse de livraison</h2>
				<form action="../adresse/adresse.php" method="post">
                    <input type="hidden" name="user_id" value="<?= $_SESSION['connectedUser']['id']?>">
                    <input type="text" name="adresse" placeholder="adresse">
                    <input type="text" name="adresse_facturation" placeholder="adresse_facturation">
                    <button class="edit-btn" href="">Modifier</button>
                </form>
				
			</div>

		</div>
	</div>
</body>