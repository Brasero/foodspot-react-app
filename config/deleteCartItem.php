<?php

include_once('./database.php');

$bdd = new DataBase;
$bdd->getConnexion();

if(isset($_GET['idUser'], $_GET['idCart'])){
    var_dump($_GET);

    $idCart = $_GET['idCart'];
    $idUser = $_GET['idUser'];

    $deleteQueryStr = 'DELETE FROM cart WHERE id_cart = :idCart AND id_users = :idUser';
    $deleteQuery = $bdd->connexion->prepare($deleteQueryStr);
    $deleteQuery->bindParam(':idUser', $idUser, PDO::PARAM_INT);
    $deleteQuery->bindParam(':idCart', $idCart, PDO::PARAM_INT);
    $deleteQuery->execute();

    header('Location: ../index.php');
}

?>