<?php
$catInfo = $bdd->getCategoriesName();
$carouselCount = 0;
?>


<div id="page" class="w-100 p-3">

    <div class="w-100 slide carousel carousel-fade d-none d-md-block mt-3 mb-4" style="max-height: 300px;" data-ride="carousel" id="banniere">
        <div class="carousel-inner">
            <?php  
                foreach($catInfo as $cat){
                    if($carouselCount == 0){
                        echo '
                        <div class="carousel-item active" style="max-height: 300px;">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Nos '.$cat['nom_categories'].'</h5>
                        </div>
                            <img class="d-block w-100 img-thumbnail" src="./assets/img/'.$cat['img_categories'].'"  alt="'.$cat['nom_categories'].'" /> 
                        </div> 
                        ';
                    }
                    else{
                        echo '
                        <div class="carousel-item" style="max-height: 300px;">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Nos '.$cat['nom_categories'].'</h5>
                        </div>
                            <img class="d-block w-100 img-thumbnail" src="./assets/img/'.$cat['img_categories'].'" alt="'.$cat['nom_categories'].'" /> 
                        </div> 
                        ';
                    }
                    $carouselCount++;
                }
            ?>
        </div>
    </div>

    <div class="row card-deck g-4 mx-auto card-group justify-content-center">
        <?php
            foreach($catInfo as $cat){
                echo '
                <div class="card text-center col-md-3 m-auto mx-md-1 mt-2" style="min-width: 311px; padding: 0;">
                    <img class="card-img-bottom" src="./assets/img/'.$cat['img_categories'].'" style="max-height: 300px;" alt="'.$cat['nom_categories'].'" />
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