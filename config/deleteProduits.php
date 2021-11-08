<?php
include_once('./database.php');

$bdd = new DataBase;
$bdd->getConnexion();


if(isset($_GET['id'])){
    $id = $_GET['id'];

    $deleteQueryStr = 'DELETE FROM produits WHERE identifiant_produits = :id';
    $deleteQuery = $bdd->connexion->prepare($deleteQueryStr);
    $deleteQuery->bindParam(':id', $id, PDO::PARAM_INT);
    $deleteQuery->execute();

    header('Location ../config/index.php?page=2');
}


?>