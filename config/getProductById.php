<?php

include_once('database.php');

$bdd = new DataBase;

$bdd->getConnexion();

$id = $_GET['idProduit'];

$produitQuery = $bdd->connexion->query('SELECT * FROM produits WHERE id_produits = '.$id.'');

$produit = $produitQuery->fetch();

echo '<span>'.$produit['nom_produits'].'</span>'

?>