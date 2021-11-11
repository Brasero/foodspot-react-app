<?php
session_start();

include_once('./database.php');
$bdd = new DataBase;
$bdd->getConnexion();


$id = $_POST['idIngredient'];

if(isset($_POST['dispoIngredient'])){
    $updateQuery = $bdd->connexion->query('UPDATE ingredients SET dispo_ingredients = 1 WHERE id_ingredients = '.$id.'');
}
else{
    $updateQuery = $bdd->connexion->query('UPDATE ingredients SET dispo_ingredients = 0 WHERE id_ingredients = '.$id.'');
}

if(isset($_POST['nomIngredient'])){
    $updateQueryStr = 'UPDATE ingredients SET nom_ingredients = :nom WHERE id_ingredients = '.$id.'';
    $updateQuery = $bdd->connexion->prepare($updateQueryStr);
    $updateQuery->bindParam(':nom', $_POST['nomIngredient'], PDO::PARAM_STR);
    $updateQuery->execute();
}

if(isset($_POST['prixIngredient'])){
    $prix = $_POST['prixIngredient'];
    $prix = str_replace(',', '.', $prix);
    $updateQueryStr = 'UPDATE ingredients SET prix_ingredients = :prix WHERE id_ingredients = '.$id.'';
    $updateQuery = $bdd->connexion->prepare($updateQueryStr);
    $updateQuery->bindParam(':prix', $prix, PDO::PARAM_STR);
    $updateQuery->execute(); 
}

header('Location: ../controls/index.php?page=3');

?>