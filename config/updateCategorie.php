<?php
session_start();

include_once('./database.php');

$bdd = new DataBase;
$bdd->getConnexion();

$data = $_POST;
$idCategorie = $data['idCategorie'];


if(isset($data['nomCategorie'])){
    $bdd->updateAttrCategorie('nom_categories', $data['nomCategorie'], $idCategorie, 0);
}

if(isset($_FILES['imgCategorie']['name'], $_FILES['imgCategorie']['tmp_name'])){

    $img = $_FILES;
    $imgName = $img['imgCategorie']['name'];
    $imgObj = $img['imgCategorie']['tmp_name'];
    $query = $bdd->connexion->query('SELECT img_categories FROM categories WHERE id_categories = '.$idCategorie.'');
    $actualImgName = $query->fetch();
    $actualImgPath = '../assets/img/'.$actualImgName['img_categories'];

    //unlink($actualImgPath);

    move_uploaded_file($imgObj, '../assets/img/'.$imgName);

    $bdd->updateAttrCategorie('img_categories', $imgName, $idCategorie, 0);
}

header('Location: ../controls/index.php?page=1');

?>