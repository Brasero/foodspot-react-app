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

    $productInfoListQuery = $bdd->connexion->prepare('SELECT * FROM produits WHERE id_categorie = :id');

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

    <div class="card-deck justify-content-center row">
        <?php

            if(isset($productInfoList)){

                foreach($productInfoList as $productInfo){
                    echo '
                    <div class="card text-center col-md-4 mx-4 mx-md-2 mt-md-2" style="min-width: 311px; padding: 0;">
                        <img class="card-img-top" src="./assets/img/'.$productInfo['img_produits'].'" alt="Card image" />
                        <div class="card-body">
                            <h5 class="card-title text-left mb-4">
                                '.$productInfo['nom_produits'].'
                            </h5>
                            <button class="btn btn-outline-dark dropdown-toggle" type="button" data-toggle="collapse" data-target="#collapseIngredient'.$productInfo['id_produits'].'">
                                Ingrédients
                            </button>
                            <p class="card-text">
                                <ul class="list-group list-group-flush collapse text-left" id="collapseIngredient'.$productInfo['id_produits'].'">
                                    ';
                                    $ingredientDetailList = $bdd->getIngredientList($productInfo['id_ingredients']);
                                    foreach($ingredientDetailList as $ingredientDetail){
                                        echo '
                                        <li class="list-group-item">
                                            '.$ingredientDetail['nom_ingredients'].'
                                        </li>';
                                    }
                    echo '
                                </ul>
                            </p>
                            
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary mb-1 mr-1 ml-auto" data-toggle="modal" data-target="#moreModal" data-produit="'.$productInfo['nom_produits'].'" data-ingredient="'.$productInfo['id_ingredients'].'" >
                                Voir +
                            </button>    
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="ingredient"></p>
            </div>
        </div>
    </div>
</div>

<script>

    $('#moreModal').on('focus', function(event) {
        var button = $(event.relatedTarget)
        var product = button.data('produit')
        var ingredientList = button.data('ingredient')
        var modal = $(this)
        modal.find('.modal-title').html(product)
        modal.find('#ingredient').html(ingredientList)
    })
</script>