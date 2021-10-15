<nav class="navbar navbar-expand-md navbar-light bg-light w-100" style="position: sticky;">

    <!--TOOGLE MENU BUTTON -->
    <button class="navbar-toggler mr-3" data-toggle="collapse" data-target="#navbarMenu">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- BRAND -->
    <a href="index.php" class="navbar-brand mr-auto fs-1">FoodSpot</a>

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
                    <a href="#" class="dropdown-item">
                        <span class="bi bi-cart"></span>
                        Panier
                    </a>
                    <a href="#" class="dropdown-item" >
                        <?php 
                            if(isset($_SESSION['user'])){
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
                <a href="#" role="button" class="btn btn-md btn-outline-dark ml-2">
                    <span class="bi bi-cart"></span>
                    Panier
                </a>
            </li>  
        </ul> 
        <br />
        <ul class="navbar-nav">
            <li class="navbar-item p-2">
                Produits
            </li>
            <li class="navbar-item p-2">
                Boissons
            </li>
        </ul>
    </div>
</nav>
<!-- END OF NAVBAR -->

<!-- CONNEXION MODAL -->

