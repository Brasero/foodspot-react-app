<?php
$totalCart = 0.0;
?>


<div class="modal" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalDialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalDialog">Mon panier</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="close">
                </button>
            </div>
            <div class="modal-subtitle">
                <span class="bi bi-car"></span>
            </div>
            <div class="modal-body">                
                    <?php
                        $itemCount = 0;
                        if(isset($_SESSION['user']) AND !empty($_SESSION['user'])){
                            echo '<div class="accordion" id="modalCartAccordion">';
                            $cart = $bdd->getCart($_SESSION['user']);
                            if(isset($cart['0']) AND !empty($cart['0'])){
                                foreach($cart as $item){
                                    $itemCount ++;
                                    $totalCart += $item['price'];
                                    $itemName = $bdd->getProductById($item['id_produits']);
                                    $label = str_replace(' ', '', $itemName['nom_produits']);
                                    $label = htmlspecialchars(str_replace('\'', '', $label));
                                    $label = str_replace('\' ', '', $label);
                                    $label = str_replace('é', 'e', $label);
                                    $label = str_replace('è', 'e', $label);
                                    $label = str_replace('à', 'a', $label);
                                    $label = str_replace('ç', 'c', $label);
                                    $label = str_replace('ù', 'u', $label);
                                    $label = str_replace('ê', 'e', $label);
                                    $label = $label.'-'.$itemCount;

                                    if(isset($item['id_ingredients'])){
                                        $ingredientList = $bdd->getIngredientList($item['id_ingredients']);
                                    }
                                    if(isset($_SESSION['user']['id_users'])){
                                        $userId = $_SESSION['user']['id_users'];
                                    }
                                    elseif(isset($_SESSION['user']['identifiant_users'])){
                                        $userId = $_SESSION['user']['identifiant_users'];
                                    }

                                    echo '
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading'.$label.'-'.$itemName['id_produits'].'">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#'.$label.'-'.$itemName['id_produits'].'" aria-expended="true" aria-controls="'.$label.'-'.$itemName['id_produits'].'">
                                                <a class="btn btn-outline-danger me-3" href="./config/deleteCartItem.php?idUser='.$userId.'&idCart='.$item['id_cart'].'">
                                                    <span class="bi bi-trash"></span>
                                                </a>
                                                    '.$itemName['nom_produits'].'<span class="text-right ms-5">'.number_format($item['price'], 2, ',', '.').'€</span>
                                                </button>
                                            </h2>
                                            <div id="'.$label.'-'.$itemName['id_produits'].'" class="accordion-collapse collapse" aria-labelledby="heading'.$label.'-'.$itemName['id_produits'].'" data-bs-parent="#modalCartAccordion">
                                                <ul class="accordion-body list-group-flush list-group">
                                                ';
                                    if(isset($ingredientList)){
                                        foreach($ingredientList as $ingredient){
                                            if($ingredient){
                                                if($ingredient['dispo_ingredients'] == 1){
                                                    echo '
                                                    <li class="list-group-item">
                                                        '.$ingredient['nom_ingredients'].'
                                                    </li>';
                                                    }
                                                else{
                                                    echo '
                                                    <li class="list-group-item text-muted">
                                                        '.$ingredient['nom_ingredients'].' <span class="text-danger bi bi-patch-exclamation"></span><span class="text-danger ms-4">Rupture</span>
                                                    </li>';
                                                    }
                                            }
                                        }
                                    }

                                    echo '</ul>
                                    </div>
                                </div>
                                <div class="">
                                    Commander en :
                                    <div id="pickMode">
                                        <button type="button" class="btn btn-outline-dark liv" onClick="livClick(event)" value="1">
                                            Click & Collect
                                        </button>
                                        <button type="button" class="btn btn-outline-dark liv" onClick="livClick(event)" value="2">
                                            Livraison
                                        </button>
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

                        if(isset($totalCart)){
                            $totalCart = number_format($totalCart, 2, ',', '.');
                        }
                    ?>
                
            </div>
            <div class="modal-footer">
                <div class="d-inline me-auto color-secondary h3"><span class="text-muted h4">Total :</span> <?= $totalCart; ?>€</div>
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Fermer</button>
                <?php  if($totalCart != 0.0 AND isset($_SESSION['user']['id_users'])){  ?>
                <a type="button" href="composant/validateCart/validateCart.php" class="btn btn-success">Commander</a>
                <?php }else if(isset($_SESSION['user']['id_users']) AND $totalCart == 0.0){ ?>
                    <button type="button" disabled class="btn btn-outline-dark">
                        <span class="bi bi-dash-circle"></span>
                        Commander
                    </button>
                <?php } else {?>
                    <a type="button" href="index.php?page=1" class="btn btn-outline-dark">
                        <span class="bi bi-box-arrow-in-right"></span>
                        Se connecter
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<!-- modifier la class du bouton non cliqué, valeur déjà récuperer plus qu'a l'enregistrer -->
<script type="text/javascript">
    function livClick(event){
        var button = event.target;
        var classList = button.classList.value;
        var buttonValue = button.value;
        console.log(buttonValue)
        if(classList.match('activeLiv')){
            button.classList.remove('activeLiv');
        }
        else{
            button.classList.add('activeLiv');
        }
    }

</script>