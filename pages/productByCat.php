<?php

if(isset($_GET['cat'])){
    $id = $_GET['cat'];
}

if(isset($id)){

    $catInfoQuery = $bdd->connexion->prepare('SELECT * FROM categories WHERE id_categories = :id');
    
    $catInfoQuery->bindParam(':id', $id, PDO::PARAM_INT);

    if($catInfoQuery->execute()){
        $catInfo = $catInfoQuery->fetch();
    }
    else{
        print_r($catInfoQuery->errorInfo());
    }

    $productInfoListQuery = $bdd->connexion->prepare('SELECT * FROM produits WHERE id_categorie = :id AND dispo_produits = 1');

    $productInfoListQuery->bindParam(':id', $id, PDO::PARAM_INT);

    if($productInfoListQuery->execute()){
        $productInfoList = $productInfoListQuery->fetchAll();
    }
    else{
        print_r($productInfoListQuery->errorInfo());
    }
}

?>

<div id="page" class="w-100 p-3">

    <?php
        if(isset($catInfo)){

            echo '<h1 class="text-center m-4">'.$catInfo['nom_categories'].'</h1>';

        }
    ?>

    <div class="card-deck justify-content-center p-2 p-md-none row">
        <?php

            if(isset($productInfoList)){

                foreach($productInfoList as $productInfo){
                    echo '
                    <div class="card text-center col-md-3 m-2 mx-md-2 mt-md-2" style="min-width: 311px; padding: 0;">
                        <span class="position-absolute top-0 px-3 py-3 h1 badge w-25 rounded bg-secondary">
                            '.number_format($productInfo['prix_produits'], 2, ',', '.').'€
                        </span>
                        <img class="card-img-top" src="./assets/img/'.$productInfo['img_produits'].'" alt="Card image" />
                        <div class="card-body p-1">
                            <h5 class="card-title text-left mb-2 mt-1">
                                '.$productInfo['nom_produits'].'
                            </h5>
                            <div>';
                            
                            if(isset($productInfo['id_ingredients']) && !empty($productInfo['id_ingredients'])){
                                echo '
                                <div class="d-grid">
                                    <button class="btn btn-outline-dark dropdown-toggle" type="button" data-toggle="collapse" data-target="#collapseIngredient'.$productInfo['id_produits'].'">
                                        Ingrédients
                                    </button>
                                </div>
                                ';
                            }
                    if(isset($productInfo['id_ingredients']) && !empty($productInfo['id_ingredients'])){

                            echo'                            
                            <p class="card-text">
                                <ul class="list-group list-group-flush collapse text-left" id="collapseIngredient'.$productInfo['id_produits'].'">
                                    ';
                            $ingredientDetailList = $bdd->getIngredientList($productInfo['id_ingredients']);
                                foreach($ingredientDetailList as $ingredientDetail){
                                    if($ingredientDetail['dispo_ingredients']){
                                    echo '
                                    <li class="list-group-item">
                                        '.$ingredientDetail['nom_ingredients'].'
                                    </li>';
                                    }
                                    else{
                                        echo '
                                        <li class="list-group-item text-muted">
                                            '.$ingredientDetail['nom_ingredients'].' <span class="text-danger bi bi-patch-exclamation"></span><span class="text-danger d-none d-sm-inline ms-4">Rupture</span>
                                        </li>';
                                    }
                                }
                              }      
                        echo '
                                    </ul>
                                </p>';
                            if(isset($productInfo['id_ingredients']) && !empty($productInfo['id_ingredients'])){

                                echo '
                                    <div class="d-grid">
                                        <button type="button" class="btn click btn-outline-secondary" data-toggle="modal" data-target="#moreModal" data-produit="'.$productInfo['id_produits'].'" data-categorie='.$_GET['cat'].' data-ingredient="'.$productInfo['id_ingredients'].'" >
                                            Voir +
                                        </button>    
                                    </div>
                                ';
                            }
                            else{
                                echo '
                                    <div class="d-grid">
                                        <button class="btn btn-outline-success" onclick="addProductWhithoutIngredient('.$productInfo['id_produits'].', '.$_SESSION['user']['id_users'].')">
                                            + Ajouter au panier
                                        </button>
                                    </div>
                                ';
                            }

                        echo '        
                            </div>
                        </div>
                    </div>';
                }
            }

        ?>
    </div>

</div>

<div class="modal fade" id="moreModal" tabindex="-1" role="dialog" aria-labelledby="moreModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel"></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <h6 class="modal-subtitle text-center"></h6>
                <div id="ingredient"></div>
            </div>
        </div>
    </div>
</div>

<script>

    $('.click').on('click', function(event) {
        var button = $(event.target)
        var product = button.data('produit')
        var ingredientList = button.data('ingredient')
        var categorie = button.data('categorie')
        var modal = $('#moreModal')
        modal.find('#ingredient').html('Chargement...')
        modal.find('.modal-title').html('Chargement...')

        var urlProduit = './config/getProductById.php?idProduit='+product
        var urlIngredient = './config/getIngredientList.php?ingredientId='+ingredientList+'&idProduit='+product+'&categorie='+categorie

        var request = new XMLHttpRequest();

        if(product != undefined){
            request.open('GET', urlProduit)
            request.send()

            request.onload = function(){
                modal.find('.modal-title').html(request.response)

                request.open('GET', urlIngredient)
                request.send()

                request.onload = function(){
                    modal.find('#ingredient').html(request.response)
                    modal.find('.modal-subtitle').html('Personnaliser')
                }
            }
        }
        else{
            modal.find('.modal-title').html('<span class="text-danger">Erreur</span>')
            modal.find('#ingredient').html('<span class="text-danger">Il semble qu\'il y ai un soucis, veuillez vérifier votre connexion.</span>')
            modal.find('.modal-subtitle').html('')
        }
        
    })

    function addProductWhithoutIngredient(id, user) {
        var url = './config/addToCartWhithoutIngredient.php?idProduit='+id+'&user='+user

        var request = new XMLHttpRequest();

        request.open('GET', url)
        request.send()
        request.onload = function(){
            location.reload();
        }
    }
</script>