<?php
require_once(__DIR__ . '/../config/config.php');       // un slash manquant avant "config"





if ($_SERVER['REQUEST_METHOD'] === 'POST') {
if (isset($_POST['produit_delete'])) {
    $produit_id = $_POST['produit_delete']; 
    $user_id = $_SESSION['connectedUser']['id'];
    // Vérifiez si le panier existe dans la session
    if ($produit_id) {
        // Vérifiez si le produit est dans le panier
        removeCartItem($user_id, $produit_id);
}
}

if (!empty($_SERVER['HTTP_REFERER'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    // Si HTTP_REFERER est vide, recharger une page par défaut
    header('Location: ../index.php');
}
exit();
}

function removeCartItem($user_id, $produit_id) {
    $pdo = connectDB();
    $stmt = $pdo->prepare('DELETE FROM panier WHERE user_id = :user_id AND produit_id = :produit_id');
    return $stmt->execute(['user_id' => $user_id, 'produit_id' => $produit_id]);
}



?>