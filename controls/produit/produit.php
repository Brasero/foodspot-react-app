<?php

$produitArray = $bdd->getProduits();
$catArray = $bdd->getCategoriesName();
$ingredientArray = $bdd->getIngredients();

if(isset($_POST['nom'], $_POST['prix'], $_POST['ingredient'], $_POST['cat'])){

    $bdd->addProduct($_POST['nom'], $_POST['prix'], $_POST['ingredient'], $_POST['cat'], $_FILES);
}

?>

<div class="w-100 d-flex bg-light" style="justify-content: center; align-items: center; margin-top: 70px;">
    <div class="w-75 card-deck row row-cols-1 row-cols-md-3 gap-3" style="justify-content: space-evenly;">
        <div class="card col">
            <h3 class="card-title text-left card-header">
                Produits
            </h3>
            <div class="card-body">
                <ul class="list-group list-group-flush">

                    <?php

                        foreach($produitArray as $produit){
                            echo '
                            <li id="I'.$produit['identifiant_produits'].'" class="list-group-item d-flex" style="align-items: center;">
                                <h5 class="h5 me-auto">'.$produit['nom_produits'].'</h5> 
                                <button class="btn btn-outline-danger me-2">
                                    <span class="bi bi-trash" onclick="deleteItem('.$produit['identifiant_produits'].')"></span>
                                </button>
                                <button class="btn btn-primary">
                                    <span class="bi bi-pencil"></span>
                                </button>
                            </li>                            
                        ';
                        }

                    ?>

                </ul>
            </div>
        </div>
        <div class="card col mx-2">
            <div class="card-body">
                <h3 class="card-title text-left">
                    Créer un nouveau produit
                </h3>
                <form class="needs-validation" action="index.php?page=2" method="post" enctype="multipart/form-data">
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" id="prodName" placeholder="Nom" name="nom" required />
                        <label for="prodName">Nom</label>
                    </div>
                    <div class="form-floating input-group mb-2">
                        <input type="text" class="form-control" id="prodPrice" placeholder="prix" name="prix" required />
                        <label for="prodPrice">Prix</label>
                        <span class="input-group-text">Format : 0.00</span>
                    </div>
                    <!-- Remplacer la list ul par un mulitselect  -->
                    <ul class="list-group">
                        <li class="list-group-item h4">Ingrédients</li>
                        <?php
                            foreach($ingredientArray as $ingredient){
                                echo '
                                    <li class="list-group-item">
                                        <input class="form-check-input me-2" type="checkbox" name="ingredient[]" value="'.$ingredient['id_ingredients'].'" />
                                        '.$ingredient['nom_ingredients'].'
                                    </li>
                                ';
                            }

                        ?>
                    
                    </ul>
                    <select class="form-select mt-3 form-select-lg" name="cat">
                        <option selected>Catégorie</option>

                        <?php
                            foreach($catArray as $cat){
                                echo '
                                    <option value="'.$cat['id_categories'].'">'.$cat['nom_categories'].'</option>
                                ';
                            }
                        ?>
                    </select>
                    <div class="mb-2">
                        <label for="prodImg" class="form-label">Ajouter une image</label>
                        <input id="prodImg" name="img" class="form-control" type="file" accept="img/*" required /> 
                    </div>
                    
                    <button class="btn btn-primary" type="submit">
                        Ajouter
                        <span class="bi bi-plus"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

function deleteItem(item){

    var itemDiv = document.querySelector('#I'+item);

    var deleteRequest = new XMLHttpRequest();
    var deleteUrl = "../config/deleteProduits.php?id="+item;

    deleteRequest.open('GET', deleteUrl);
    deleteRequest.send();

    deleteRequest.onload = function(){
        itemDiv.remove();
    }

}

</script>