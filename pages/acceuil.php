<?php
$catInfo = $bdd->getCategoriesName();
?>

<div id="page" class="w-100 p-3">
    <div class="row card-deck">
        <?php
            foreach($catInfo as $cat){
                echo '
                <div class="card text-center col-md-4 mx-4 mx-md-1 mt-md-1" style="min-width: 311px; padding: 0;">
                    <img class="card-img-bottom" src="./assets/img/'.$cat['img_categories'].'" alt="'.$cat['nom_categories'].'" />
                    <div class="card-body">
                        <div class="card-title text-left mb-4">
                            Nos '.$cat['nom_categories'].'
                        </div>
                        <a class="btn btn-outline-dark btn-block" href="index.php?cat='.$cat['id_categories'].'">
                            Voir les produits
                        </a>
                    </div>
                </div>';
            }
        ?>
    </div>
</div>