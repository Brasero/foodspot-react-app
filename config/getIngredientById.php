<?php
setlocale(LC_ALL, 'fr_FR.utf-8', 'fra');
date_default_timezone_set('Europe/Paris');

include_once("./database.php");
$bdd = new DataBase;
$bdd->getConnexion();

$id = $_GET['idIngredient'];

$query = $bdd->connexion->query('SELECT * FROM ingredients WHERE id_ingredients = '.$id.'');
$ingredient = $query->fetch();

$date = strftime('Ingrédient créé %A %d %b %Y à %H:%M', $ingredient['identifiant_ingredients']);

?>

<form action="../config/updateIngredient.php" method="post">
    <small class="text-muted mt-0 mb-3"><?= $date ?></small>
    <input type="number" hidden name="idIngredient" value="<?= $ingredient['id_ingredients'] ?>" />
    <div class="form-check form-switch mb-3">
        <label class="form-check-label" for="dispoIngredient">Disponibilité</label>
        <?php
            if($ingredient['dispo_ingredients']){
                echo '<input class="form-check-input" type="checkbox" role="switch" id="dispoIngredient" value="1" name="dispoIngredient" checked />';
            }
            else{
                echo '<input class="form-check-input" type="checkbox" role="switch" id="dispoIngredient" value="1" name="dispoIngredient" />';
            }

        ?>
    </div>
    <div class="input-group mb-1">
        <label for="nomIngredient" class="input-group-text">Nom</label>
        <input type="text" name="nomIngredient" id="nomIngredient" value="<?= $ingredient['nom_ingredients'] ?>" class="form-control" required />
    </div>
    <div class="input-group mb-1">
        <label for="prixIngredient" class="input-group-text">Prix</label>
        <input type="text" name="prixIngredient" id="prixIngredient" value="<?= number_format($ingredient['prix_ingredients'], 2, ',', '.') ?>" class="form-control">
        <span class="input-group-text">Format : 0,00 €</span>
    </div>
    <div class="d-grid mt-4">
        <button class="btn btn-outline-primary" type="submit">
            Modifier
            <span class="bi bi-pencil"></span>
        </button>
    </div>
</form>