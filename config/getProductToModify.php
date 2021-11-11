<?php
setlocale(LC_ALL, 'fr_FR.utf-8', 'fra');
date_default_timezone_set('Europe/Paris');

include_once('./database.php');

$bdd = new DataBase;

$bdd->getConnexion();

$id = $_GET['idProduit'];

$catQuery = $bdd->connexion->query('SELECT * FROM categories');
$catList = $catQuery->fetchAll();

$ingredientListQuery = $bdd->connexion->query('SELECT * FROM ingredients');
$dbIngredientList = $ingredientListQuery->fetchAll();

$getQueryStr = 'SELECT * FROM produits
                INNER JOIN categories
                ON categories.id_categories = produits.id_categorie
                WHERE produits.id_produits = :id';

$getQuery = $bdd->connexion->prepare($getQueryStr);
$getQuery->bindParam(':id', $id, PDO::PARAM_INT);
$getQuery->execute();

$productInfo = $getQuery->fetch();
$productIngredientIdArray = explode(';', $productInfo['id_ingredients']);
$productIngredientArray = [];
foreach($productIngredientIdArray as $productIngredient){
    $query = $bdd->connexion->query('SELECT * FROM ingredients WHERE id_ingredients = '.$productIngredient.'');
    $ingredient = $query->fetch();
    array_push($productIngredientArray, $ingredient);
}

$productInfo['ingredientArray'] = $productIngredientArray;

$date = strftime('Produit créé %A %d %b %Y à %H:%M', $productInfo['identifiant_produits']);



?>

<form action="../config/updateProduct.php" method="POST" enctype="multipart/form-data">
    <small class="text-muted mt-0 mb-3"><?= $date ?></small>
    <input type="number" hidden name="idProduit" value="<?=$productInfo['id_produits'] ?>" />
    <div class="form-check form-switch mb-3">
        <label class="form-check-label" for="dispoProduit">Disponibilité </label>
        <?php
            if($productInfo['dispo_produits']){
                echo '<input class="form-check-input" type="checkbox" role="switch" id="dispoProduit" value="1" name="dispoProduit" checked />';
            }
            else{
                echo '<input class="form-check-input" type="checkbox" role="switch" id="dispoProduit" value="1" name="dispoProduit" />';
            }
        ?>
    </div>
    <div class="input-group mb-1">
        <label class="input-group-text" for="nomProduit">Nom</label>
        <input type="text" class="form-control" id="nomProduit" name="nomProduit" value="<?=$productInfo['nom_produits']?>" required />
    </div>
    <div class="input-group mb-3">
        <label class="input-group-text" for="prixProduit">Prix</label>
        <input class="form-control" id="prixProduit" name="prixProduit" value="<?=number_format($productInfo['prix_produits'], 2, ',', '.')?>" required />
        <span class="input-group-text">Format : 0,00 €</span>
    </div>
    <ul class="list-group mb-3">
        <li class="list-group-item h4">Ingrédients</li>
        <?php
            foreach($dbIngredientList as $ingredient){
                if(in_array($ingredient['id_ingredients'], $productIngredientIdArray)){
                    echo '
                        <li class="list-group-item">
                            <input class="form-check-input me-2" type="checkbox" name="ingredient[]" value="'.$ingredient['id_ingredients'].'" checked />
                            '.$ingredient['nom_ingredients'].'
                        </li>
                    ';
                }
                else{
                    echo '
                        <li class="list-group-item">
                            <input class="form-check-input me-2" type="checkbox" name="ingredient[]" value="'.$ingredient['id_ingredients'].'" />
                            '.$ingredient['nom_ingredients'].'
                        </li>
                    ';
                }
            }


        ?>
    </ul>
    <label for="catProduit" class="h4">Catégories</label>
    <select class="form-select form-select-lg mb-3" id="catProduit" name="catProduit">

        <?php  
            foreach($catList as $cat){
                if($cat['id_categories'] === $productInfo['id_categorie']){
                    echo '
                        <option value="'.$cat['id_categories'].'" selected>'.$cat['nom_categories'].'</option>
                    ';
                }
                else{
                    echo '
                        <option value="'.$cat['id_categories'].'">'.$cat['nom_categories'].'</option>
                    ';
                }
            }
        ?>
    </select>

    <div class="card mx-1">
        <img src="../assets/img/<?=$productInfo['img_produits']?>" class="card-img-top" alt="..." />
        <div class="card-body">
            <h5 class="card-title">Changer d'image</h5>
            <div class="input-group">
                <input id="imgProduit" name="imgProduit" class="form-control" type="file" accept="img/*" />
            </div>
        </div>
    </div>
    <div class="d-grid mt-4">
        <button class="btn btn-outline-primary" type="submit">
            Modifier
            <span class="bi bi-pencil"></span>
        </button>
    </div>
</form>