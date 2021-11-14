<?php
include_once('./database.php');

$bdd = new DataBase;
$bdd->getConnexion();

$commande = $_GET['id_commande'];

if(isset($commande)){
    $bdd->deliverCommande($commande);
}
?>