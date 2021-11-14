<?php
session_start();

include_once('./database.php');

$bdd = new DataBase;

$bdd->getConnexion();

$data = $_POST;

$idProduit = $data['idProduit'];

if(isset($data['nomProduit'])){
    $bdd->updateAttrProduit('nom_produits', $data['nomProduit'], $idProduit, 0);
}

if(isset($data['prixProduit'])){
    $data['prixProduit'] = str_replace(',', '.', $data['prixProduit']);
    $bdd->updateAttrProduit('prix_produits', $data['prixProduit'], $idProduit, 0);
}

if(isset($data['catProduit'])){
    $bdd->updateAttrProduit('id_categorie', $data['catProduit'], $idProduit, 1);
}

if(isset($data['ingredient'])){
    $ingredientArray = $data['ingredient'];
    $ingredientStr = '';
    for($i = 0; $i < sizeof($ingredientArray); $i++){
        if($i === sizeof($ingredientArray) - 1){
            $ingredientStr = $ingredientStr.$ingredientArray[$i];
        }
        elseif($i === 0){
            $ingredientStr = $ingredientArray[$i].';';
        }
        else{
            $ingredientStr = $ingredientStr.$ingredientArray[$i].';';
        }
    }
    $bdd->updateAttrProduit('id_ingredients', $ingredientStr, $idProduit, 0);
}

if(isset($data['dispoProduit'])){
    $bdd->updateAttrProduit('dispo_produits', 1, $idProduit, 1);
}
else{
    $bdd->updateAttrProduit('dispo_produits', 0, $idProduit, 1);
}

if(isset($_FILES) && !empty($_FILES['imgProduit']['name'])){
    $img = $_FILES;
    $imgName = $img['imgProduit']['name'];
    $imgObj = $img['imgProduit']['tmp_name'];
    $query = $bdd->connexion->query('SELECT img_produits FROM produits WHERE id_produits = '.$idProduit.'');
    $actualImgName = $query->fetch();
    $actualImgPath = '../assets/img/'.$actualImgName['img_produits'];

    unlink($actualImgPath);

    move_uploaded_file($imgObj, '../assets/img/'.$imgName);
    
    $bdd->updateAttrProduit('img_produits', $imgName, $idProduit, 0);
}

header('Location: ../controls/index.php?page=2');





?>