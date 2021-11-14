<?php
include_once('./database.php');

$id = $_GET['idProduit'];
$userId = $_GET['user'];

$bdd = new DataBase;
$bdd->getConnexion();

$productQueryStr = 'SELECT * FROM produits WHERE id_produits = :id';
$productQuery = $bdd->connexion->prepare($productQueryStr);
$productQuery->bindParam(':id', $id, PDO::PARAM_INT);
$productQuery->execute();

$product = $productQuery->fetch();

$identifiant = time();
$ingredient = null;


$insertQueryStr = 'INSERT INTO
                    cart (identifiant_panier, id_produits, id_ingredients, id_users, price)
                    VALUES (:idPanier, :idProduit, :idIngredient, :idUser, :price)';
$insertQuery = $bdd->connexion->prepare($insertQueryStr);
$insertQuery->bindParam(':idPanier', $identifiant, PDO::PARAM_INT);
$insertQuery->bindParam(':idProduit', $id, PDO::PARAM_INT);
$insertQuery->bindParam(':idIngredient', $ingredient, PDO::PARAM_NULL);
$insertQuery->bindParam('idUser', $userId, PDO::PARAM_INT);
$insertQuery->bindParam('price', $product['prix_produits'], PDO::PARAM_STR);

$insertQuery->execute();

?>