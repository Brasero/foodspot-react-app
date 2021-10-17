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

<div id="page" class="w-100 p-3 container">

    <?php
        if(isset($catInfo)){

            echo '<h1 class="text-center m-4">'.$catInfo['nom_categories'].'</h1>';

        }
    ?>

    <div class="card-deck justify-content-center">
        <?php

            if(isset($productInfoList)){

                foreach($productInfoList as $productInfo){
                    $productKey = explode(';', $productInfo['id_ingredients']);

                    echo '
                    <div class="card text-center" style="max-width: 311px;">
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
                                    foreach($productKey as $key){
                                        $ingredientQuery = $bdd->connexion->query('SELECT * FROM ingredients WHERE id_ingredients = '.$key.'');
                                        $ingredientDetail = $ingredientQuery->fetch();
                                        
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
                            <a class="btn btn-primary mb-1 mr-1 ml-auto" href="#">
                                Ajouter au panier
                            </a>    
                        </div>
                    </div>';
                }
            }

        ?>
    </div>

</div>