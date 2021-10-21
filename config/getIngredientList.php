<?php
session_start();

require_once('database.php');

$bdd = new DataBase;

$bdd->getConnexion();

$ListText = trim($_GET['ingredientId']);

$productName = $_GET['idProduit'];

$List = explode(';', $ListText);

$returnList = [];

foreach($List as $id) {
    $query = $bdd->connexion->query('SELECT * FROM ingredients WHERE id_ingredients = '.$id.'');
    $ingredient = $query->fetch();

    array_push($returnList, $ingredient);
}

function key_compare_func($a, $b) {
    $missingIndex = [];

    foreach($a as $prod){
        $find = false;

        foreach($b as $compareProd) {
            if($prod['id_ingredients'] === $compareProd['id_ingredients']){
                $find = true;
                break;
            }
        }

        if($find === false){
            array_push($missingIndex, $prod);
        }
    }
    return $missingIndex;
}

$ingredientQuery = $bdd->connexion->query('SELECT * FROM ingredients');
$ingredientList = $ingredientQuery->fetchAll();
$ingredientSupp = key_compare_func($ingredientList, $returnList);

echo '<form action="./config/addToCart.php" method="post"><ul class="list-group form-group list-group-flush text-left">
    <input type="text" hidden name="idProduit" value="'.$productName.'" />
';

if(isset($_SESSION['user'])){
    echo '<input type="text" hidden name="idUser" value="'.$_SESSION['user']['id_users'].'" />';
}

foreach($returnList as $ingredient) {

    echo '<li class="list-group-item form-group form-deck">
        <label><input class="form-check-input" name="'.$ingredient['id_ingredients'].'" type="checkbox" value="'.$ingredient['id_ingredients'].'" checked />
        '.$ingredient['nom_ingredients'].'
        </label> 
    </li>';

    
}

foreach($ingredientSupp as $supp) {

    echo '<li class="list-group-item form-group form-deck">
            <label><input class="form-check-input" type="checkbox" name="supp-'.$supp['id_ingredients'].'" value="supp-'.$supp['id_ingredients'].'" />
            '.$supp['nom_ingredients'].'<span class="text-muted"> + '.$supp['prix_ingredients'].'€</span>
            </label>
    </li>';
}


echo '</ul>';

if(isset($_SESSION['user'])){
    echo '<button type="submit" class="btn w-100 mt-2 btn-success">Ajouter au panier</button>';
}

echo '</form>';

if(!isset($_SESSION['user'])){
    echo '
    <div class="d-grid">
        <a href="index.php?page=1" class="btn btn-outline-dark mt-5" >
            <span class="bi bi-box-arrow-in-right"></span>
            Connexion
        </button>
    </a>
    ';
}
?>