<?php
setlocale(LC_ALL, 'fr_FR.utf-8', 'fra');
date_default_timezone_set('Europe/Paris');

include_once('./database.php');

$bdd = new DataBase;
$bdd->getConnexion();

$idCategorie = $_GET['idCat'];

$categorieQueryStr = 'SELECT * FROM categories WHERE id_categories = :id';

$categorieQuery = $bdd->connexion->prepare($categorieQueryStr);
$categorieQuery->bindParam(':id', $idCategorie, PDO::PARAM_INT);
$categorieQuery->execute();

$categorie = $categorieQuery->fetch();

$date = strftime('Catégorie créée %A %d %b %Y à %H:%M', $categorie['identifiant_categories']);
?>

<form action="../config/updateCategorie.php" method="post" enctype="multipart/form-data">
    <small class="text-muted mt-0 mb-3"><?= $date ?></small>
    <input hidden type="number" name="idCategorie" value="<?= $categorie['id_categories'] ?>" />
    <div class="input-group mb-3">
        <label for="nomCategorie" class="input-group-text">Nom</label>
        <input type="text" name="nomCategorie" id="nomCategorie" value="<?= $categorie['nom_categories'] ?>" class="form-control" required />
    </div>
    <div class="card mx-1">
        <img src="../assets/img/<?= $categorie['img_categories'] ?>" class="card-img-top" alt="..." />
        <div class="card-body">
            <h5 class="card-title">Changer d'image</h5>
            <div class="input-group">
                <input type="file" accept="img/*" name="imgCategorie" class="form-control" id="imgCategorie" />
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