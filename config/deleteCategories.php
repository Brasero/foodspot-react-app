<?php

include_once('./database.php');

$bdd = new DataBase;

$bdd->getConnexion();

$id = $_GET['id'];

$catQuery = $bdd->connexion->prepare('SELECT * FROM categories WHERE identifiant_categories = :id');
$catQuery->bindParam(':id', $id, PDO::PARAM_INT);
$catQuery->execute();

$catInfo = $catQuery->fetch();

$imgPath = $catInfo['img_categories'];

$imgPath = '../assets/img/'.$imgPath;

unlink($imgPath);

$deleteRequest = $bdd->connexion->prepare('DELETE FROM categories WHERE identifiant_categories = :id');
$deleteRequest->bindParam(':id', $id, PDO::PARAM_INT);

if(!$deleteRequest->execute()){
    var_dump($deleteRequest->errorInfo());
}

?>