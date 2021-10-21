<?php

include_once('database.php');

$bdd = new DataBase;

$bdd->getConnexion();

$data = $_POST;


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
    $match = preg_match("/supp-/", $value);
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

var_dump($suppIngredientId);
var_dump($noSuppIngredient);
var_dump($dbproductInfo); 
var_dump($data);

$suppIngredientArray = getIngredients($suppIngredientId, $bdd);

var_dump($suppIngredientArray);

$commandePrice = floatval($dbproductInfo['prix_produits']);

foreach($suppIngredientArray as $produits){
    $commandePrice += floatval($produits['prix_ingredients']);
}

var_dump($commandePrice);

//enregistrer les données en bdd avec nouveau prix.

?>