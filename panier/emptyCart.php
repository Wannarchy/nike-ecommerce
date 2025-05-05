<?php
require_once(__DIR__ . '/../config/config.php');       // un slash manquant avant "config"



function emptyCart($user_id) {
    $pdo = connectDB();
    $stmt = $pdo->prepare('DELETE FROM panier WHERE user_id = :user_id');
    $stmt->execute(['user_id' => $user_id]);
}




?>