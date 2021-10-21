<?php

if(isset($_SESSION['user']) AND !empty($_SESSION['user'])){
    $cart = $bdd->getCart($_SESSION['user']);
    if(isset($cart) AND !empty($cart)){
        foreach($cart as $item){

        }
    }
}


?>


<div class="modal" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalDialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalDialog">Mon panier</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="close">
                </button>
            </div>
            <div class="modal-body">
                
                    <?php
                        if(isset($_SESSION['user']) AND !empty($_SESSION['user'])){
                            echo '<div class="accordion" id="modalCartAccordion">';
                            $cart = $bdd->getCart($_SESSION['user']);
                            if(isset($cart['0']) AND !empty($cart['0'])){
                                foreach($cart as $item){
                                    $itemName = $bdd->getProductById($item['id_produits']);
                                    $ingredientList = $bdd->getIngredientList($item['id_ingredients']);
                                    $label = str_replace(' ', '', $itemName['nom_produits']);

                                    echo '
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading'.$label.'-'.$itemName['id_produits'].'">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#'.$label.'-'.$itemName['id_produits'].'" aria-expended="true" aria-controls="'.$label.'-'.$itemName['id_produits'].'">
                                                    '.$itemName['nom_produits'].'
                                                </button>
                                            </h2>
                                            <div id="'.$label.'-'.$itemName['id_produits'].'" class="accordion-collapse collapse" aria-labelledby="heading'.$label.'-'.$itemName['id_produits'].'" data-bs-parent="#modalCartAccordion">
                                                <ul class="accordion-body list-group-flush list-group">
                                                ';
                                    foreach($ingredientList as $ingredient){
                                        echo '<li class="list-group-item">
                                                '.$ingredient['nom_ingredients'].'
                                            </li>';
                                    }

                                    echo '</ul>
                                    </div>
                                </div>
                            ';
                                                
                                }
                            }
                            else{
                                echo '<span class="text-muted">Votre panier est vide, commandez maintenant</span>';
                            }
                           echo '</div>';
                        }
                        else{
                            echo '<span class="text-danger"> Veuillez vous connecter.</span>';
                        }

                    ?>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary">Commander</button>
            </div>
        </div>
    </div>
</div>