<?php

$ingredientArray = $bdd->getIngredients();

if(isset($_POST['nomIngredient'], $_POST['prixIngredient'])){
    $bdd->addIngredient($_POST['nomIngredient'], $_POST['prixIngredient']);
}

?>


<div id="categoriesWrapper" class="w-100 d-flex bg-light" style="justify-content: center; align-items: center; margin-top: 70px;">
    <div class="w-75 card-deck row row-cols-1 row-cols-md-3 gap-3" style="justify-content: space-evenly;">
        <div class="card col">
            <h3 class="card-title text-left card-header">
                    Ingrédients
            </h3>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    
                <?php

                    foreach($ingredientArray as $ingredient){
                        if($ingredient['dispo_ingredients']){
                            echo '
                            <li id="I'.$ingredient['identifiant_ingredients'].'" class="list-group-item d-flex" style="align-items: center;">
                                <h5 class="h5 me-auto">'.$ingredient['nom_ingredients'].'</h5> 
                                <button class="btn btn-outline-danger me-2">
                                    <span class="bi bi-trash" onclick="deleteItem('.$ingredient['identifiant_ingredients'].')"></span>
                                </button>
                                <button type="button" class="btn click btn-primary" data-toggle="modal" data-target="#modifyModal" data-ingredient="'.$ingredient['id_ingredients'].'">
                                    <span class="bi bi-pencil"></span>
                                </button>
                            </li>                            
                        ';
                        }
                        else{
                            echo '
                            <li id="I'.$ingredient['identifiant_ingredients'].'" class="list-group-item text-muted d-flex" style="align-items: center;">
                                <h5 class="h5 me-auto">'.$ingredient['nom_ingredients'].'<small class="text-danger ms-2">Indisponible</small></h5> 
                                <button class="btn btn-outline-danger me-2">
                                    <span class="bi bi-trash" onclick="deleteItem('.$ingredient['identifiant_ingredients'].')"></span>
                                </button>
                                <button type="button" class="btn click btn-primary" data-toggle="modal" data-target="#modifyModal" data-ingredient="'.$ingredient['id_ingredients'].'">
                                    <span class="bi bi-pencil"></span>
                                </button>
                            </li>                            
                        ';
                        }
                    }
                ?>

                </ul>
            </div>
        </div>
        <div class="card col mx-2">
            <div class="card-body">
                <h3 class="card-title text-left">
                    Créer un nouvelle ingrédient
                </h3>
                <form class="needs-validation" action="index.php?page=3" method="post">
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" id="nomIngredient" placeholder="Nom" name="nomIngredient" required />
                        <label for="nomIngredient">Nom</label>
                    </div>
                    <div class="form-floating input-group mb-2">
                        <input type='text' class="form-control" id="prixIngredient" name="prixIngredient" placeholder="prix" required />
                        <label for="prixIngredient">Prix du supplément</label>
                        <span class="input-group-text d-none d-md-flex">Format 0,00 €</span>
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

<div class="modal fade" id="modifyModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modification d'ingredient</h5>
                <button class="btn-close" type="button" data-dismiss="modal" aria-label="close"></button>
            </div>
            <div class="modal-body">
                <h6 class="modal-subtitle text-center"></h6>
                <div id="productDetail"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

function deleteItem(item){

    var itemDiv = document.querySelector('#I'+item);

    var deleteRequest = new XMLHttpRequest();
    var deleteUrl = "../config/deleteIngredient.php?id="+item;

    deleteRequest.open('GET', deleteUrl);
    deleteRequest.send();

    deleteRequest.onload = function(){
        itemDiv.remove();
    }

}

$('.click').on('click', function(event) {
    var button = $(event.currentTarget)
    var ingredientId = button.data('ingredient')
    var modal = $('#modifyModal')
    modal.find('#productDetail').html('Chargement...')

    var urlIngredientToModify = '../config/getIngredientById.php?idIngredient='+ingredientId

    var request = new XMLHttpRequest();

    if(ingredientId != undefined){
        request.open('GET', urlIngredientToModify)
        request.send();

        request.onload = function(){
            modal.find('#productDetail').html(request.response)
        }
    }
})

</script>