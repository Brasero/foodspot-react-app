<?php
include_once('./database.php');

$bdd = new DataBase;

$bdd->getConnexion();

$identifiant = $_GET['id'];

$queryIng = $bdd->connexion->query('SELECT * FROM ingredients WHERE identifiant_ingredients = '.$identifiant.'');
$ing = $queryIng->fetch();

$productQuery = $bdd->connexion->query('SELECT * FROM produits');
$productArray = $productQuery->fetchAll();

foreach($productArray as $product){
    $ingredientStr = $product['id_ingredients'];
    $ingredientArray = explode(';', $ingredientStr);
    if(in_array($ing['id_ingredients'], $ingredientArray)){
        unset($ingredientArray[array_search($ing['id_ingredients'], $ingredientArray)]);
        $ingredientStr = '';
        for($i = 0; $i < sizeof($ingredientArray); $i++){
            if(isset($ingredientArray[$i])){
                if($i == sizeof($ingredientArray) - 1){
                    $ingredientStr = $ingredientStr.$ingredientArray[$i];
                }
                elseif($i === 0){
                    $ingredientStr = $ingredientArray[$i].';';
                }
                else{
                    $ingredientStr = $ingredientStr.$ingredientArray[$i].';';
                }}
        }
        $bdd->updateAttrProduit('id_ingredients', $ingredientStr, $product['id_produits'], 0);
    }
}

$deleteQueryStr = 'DELETE FROM ingredients WHERE identifiant_ingredients = :id';

$deleteQuery = $bdd->connexion->prepare($deleteQueryStr);
$deleteQuery->bindParam(':id', $identifiant, PDO::PARAM_INT);
$deleteQuery->execute();



?>