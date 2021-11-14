<?php
session_start();
include_once('../../config/database.php');

$bdd = new DataBase;

$bdd->getConnexion();

$cart = $bdd->getCart($_SESSION['user']);
$identifiantCommande = time();

foreach($cart as $item){
    if(isset($item['id_ingredients'])){
        $insertQueryStr = 'INSERT INTO commande (identifiant_commande, id_produits, id_ingredients, id_users, prix_commande) VALUES (:identifiant, :produits, :ingredients, :user, :prix)';
        $insertQuery = $bdd->connexion->prepare($insertQueryStr);
        $insertQuery->bindParam(':identifiant', $identifiantCommande, PDO::PARAM_INT);
        $insertQuery->bindParam(':produits', $item['id_produits'], PDO::PARAM_INT);
        $insertQuery->bindParam(':ingredients', $item['id_ingredients'], PDO::PARAM_STR);
        $insertQuery->bindParam(':user', $item['id_users'], PDO::PARAM_INT);
        $insertQuery->bindParam(':prix', $item['price'], PDO::PARAM_STR);
    }
    else{
        $ingredients = null;
        $insertQueryStr = 'INSERT INTO commande (identifiant_commande, id_produits, id_ingredients, id_users, prix_commande) VALUES (:identifiant, :produits, :ingredients, :user, :prix)';
        $insertQuery = $bdd->connexion->prepare($insertQueryStr);
        $insertQuery->bindParam(':identifiant', $identifiantCommande, PDO::PARAM_INT);
        $insertQuery->bindParam(':produits', $item['id_produits'], PDO::PARAM_INT);
        $insertQuery->bindParam(':ingredients', $ingredients, PDO::PARAM_NULL);
        $insertQuery->bindParam(':user', $item['id_users'], PDO::PARAM_INT);
        $insertQuery->bindParam(':prix', $item['price'], PDO::PARAM_STR);
    }

    if($insertQuery->execute()){
        $deleteQuery = $bdd->connexion->query('DELETE FROM cart WHERE id_cart = '.$item['id_cart'].'');
        $registerStatut = true;
    }
    else{
        var_dump($insertQuery->errorInfo());
        $registerStatut = false;
    }
}
if($registerStatut){
    $statutCommandeQuery = $bdd->connexion->query('INSERT INTO statut_commande (identifiant_commande, statut_commande_value) VALUES ('.$identifiantCommande.', 0)');
}

header('Location: ../../index.php');


?>