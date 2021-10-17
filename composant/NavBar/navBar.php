<?php
$categoriesArray = $bdd->getCategoriesName();
?>

<nav class="navbar navbar-expand-md navbar-light bg-white w-100 border-bottom sticky-top" style="position: sticky;">

    <!--TOOGLE MENU BUTTON -->
    <button class="navbar-toggler mr-3" data-toggle="collapse" data-target="#navbarMenu">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- BRAND -->
    <a href="index.php" class="navbar-brand mr-auto fs-1">
        <img src='./assets/img/logo.png' height="60" class="d-inline-block align-center" alt="" />
        FoodSpot
    </a>

    <!-- EXPAND MENU UL -->
    <div class="collapse navbar-collapse" style="flex-direction: column;" id="navbarMenu">
        <ul class="navbar-nav mt-3 ml-auto" style='flex-direction: row;'>
            <li class="nav-item dropdown">
                <button class="nav-link dropdown-toggle btn btn-md btn-outline-light" id="accountDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="bi-solid bi-person"></span>
                    Mon Compte
                </button>
                <div class="dropdown-menu" aria-labelledby="accountDropdown">
                    <a href="#" class="dropdown-item">
                        <span class="bi bi-person"></span>
                        Mon compte
                    </a>
                    <a href="#" role="button" data-toggle="modal" data-target="#cartModal" class="dropdown-item">
                        <span class="bi bi-cart"></span>
                        Panier
                    </a>
                    <a href="#" class="dropdown-item" >
                        <?php 
                            if(isset($_SESSION['userName'])){
                                echo '
                                <span class="bi bi-box-arrow-out-right"></span>
                                Déconnexion';
                            }
                            else{
                                echo '
                                <span class="bi bi-box-arrow-in-right"></span>
                                Connexion';
                            }
                        ?>
                    </a>
                </div>
            </li>
            <li class="nav-item">
                <button href="#" type="button" class="btn btn-md btn-outline-dark ml-2" data-toggle="modal" data-target="#cartModal">
                    <span class="bi bi-cart"></span>
                    Panier
                </button>
            </li>
        </ul> 
        <br />
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="index.php" class="mr-1 mt-1 btn btn-outline-dark <?php if(!isset($_GET['cat'])){echo 'active';} ?>">
                    <span class="bi bi-house"></span>
                </a>
            </li>
            <?php 
                foreach($categoriesArray as $categorie){
                    if(isset($_GET['cat'])){
                        if($_GET['cat'] == $categorie['id_categories']){
                            echo '
                            <li class="nav-item">
                                <a href="index.php?cat='.$categorie['id_categories'].'" class="mr-1 mt-1 btn btn-outline-dark active">
                                    '.$categorie['nom_categories'].'
                                </a>
                            </li>'; 
                        }
                        else{
                            echo '
                            <li class="nav-item">
                                <a href="index.php?cat='.$categorie['id_categories'].'" class="mr-1 mt-1 btn btn-outline-dark">
                                    '.$categorie['nom_categories'].'
                                </a>
                            </li>';
                        }
                    }
                    else{
                        echo '
                        <li class="nav-item">
                            <a href="index.php?cat='.$categorie['id_categories'].'" class="mr-1 mt-1 btn btn-outline-dark">
                                '.$categorie['nom_categories'].'
                            </a>
                        </li>';
                    }
                    
                   
                }
            ?>
        </ul>
    </div>
</nav>
<!-- END OF NAVBAR -->

<!-- CONNEXION MODAL -->

