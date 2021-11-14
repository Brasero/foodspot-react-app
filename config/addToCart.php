<?php

include_once('database.php');

$bdd = new DataBase;

$bdd->getConnexion();

$data = $_POST;
$cat = $_POST['cat'];


function getIngredients($array, $bdd){
    $return = [];
    foreach($array as $id){
        $queryStr = 'SELECT * FROM ingredients WHERE id_ingredients = '.$id.'';
        $query = $bdd->connexion->query($queryStr);
        $respond = $query->fetch();
        array_push($return, $respond);
    }

    return $return;
}


$dbproductQuery = $bdd->connexion->query('SELECT * FROM produits WHERE id_produits = '.$data['idProduit'].'');
$dbproductInfo = $dbproductQuery->fetch();


$supIngredientListUntraited = [];

$noSuppIngredient = [];

foreach($data as $key => $value){
    $match;
    $match = preg_match("/supp-/", $key);
    if($match == 1){
        array_push($supIngredientListUntraited, $value);
    }
    elseif($key != 'idProduit' AND $key != 'idUser' AND $match != 1){
        array_push($noSuppIngredient, $value);
    }
}

$suppIngredientId = [];

foreach($supIngredientListUntraited as $suppId){
    $id = explode('supp-', $suppId);
    foreach($id as $i){
        if($i != ''){
            array_push($suppIngredientId, $i);
        }
    }
}

$suppIngredientArray = getIngredients($suppIngredientId, $bdd);


$commandePrice = floatval($dbproductInfo['prix_produits']);

foreach($suppIngredientArray as $produits){
    $commandePrice += floatval($produits['prix_ingredients']);
}

$count = 1;
$ingredientString = '';

foreach($data as $key => $value){
    if($key != 'idProduit' AND $key != 'idUser' AND $key != 'cat'){
        if($count < count($data)){
            $ingredientString = $ingredientString.$value.';'; 
        }
        else{
            $ingredientString = $ingredientString.$value;
        }
    }
    $count++;
}

function createCartItem($idProduit, $ingredientString, $idUser, $price){
    $constructItem = [];

    $constructItem['identifiant_panier'] = time();
    $constructItem['id_produits'] = $idProduit;
    $constructItem['id_ingredients'] = $ingredientString;
    $constructItem['id_users'] = $idUser;
    $constructItem['price'] = $price;

    return $constructItem;
}

$cartItem = createCartItem($data['idProduit'], $ingredientString, $data['idUser'], $commandePrice);


if(isset(
    $cartItem['identifiant_panier'], 
    $cartItem['id_produits'], 
    $cartItem['id_ingredients'], 
    $cartItem['id_users'], 
    $cartItem['price']
    )){
        $queryStr = 'INSERT INTO 
                    cart (identifiant_panier, id_produits, id_ingredients, id_users, price) 
                    VALUES (:idPanier, :idProduits, :idIngredients, :idUsers, :price)';
        $insertQuery = $bdd->connexion->prepare($queryStr);
        $insertQuery->bindParam(':idPanier', $cartItem['identifiant_panier'], PDO::PARAM_INT);
        $insertQuery->bindParam('idProduits', $cartItem['id_produits'], PDO::PARAM_STR);
        $insertQuery->bindParam(':idIngredients', $cartItem['id_ingredients'], PDO::PARAM_STR);
        $insertQuery->bindParam(':idUsers', $cartItem['id_users'], PDO::PARAM_STR);
        $insertQuery->bindParam(':price', $cartItem['price'], PDO::PARAM_STR);

        $insertQuery->execute();

        header('Location: ../index.php?cat='.$cat.'');
    }
?>