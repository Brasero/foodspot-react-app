<?php
session_start();
if(isset($_COOKIE['userName'])){

    $_SESSION['userName'] = $_COOKIE['userName'];
}

include_once('./config/database.php');

$bdd = new DataBase;

$bdd->getConnexion()
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>
            FoodSpot
        </title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
		
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
        <link rel="stylesheet" href="./css/main.css" />
        <link rel="stylesheet" href="./css/navBar.css" />
        <link rel="stylesheet" href="./node_modules/bootstrap-icons/font/fonts/bootstrap-icons.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.0/font/bootstrap-icons.css">
        
    </head>

    <body class="w-100 bg-light ml-auto mr-auto" style="max-width: 1024px; align-items: center;">

        <header>

        </header>
        <?php 
        include_once('./composant/NavBar/navBar.php'); 
        include_once('./composant/NavBar/cartModal.php');

        //insertion de la page d'acceuil dans les else, page catégorie dans le if.
        if(isset($_GET['cat'])){
            include('./pages/productByCat.php');
        }
        else{
            include('./pages/acceuil.php');
        }
        ?>
        


        <footer>

        </footer>
        <!-- END OF FILE  -->
        
        <!-- BOOTSTRAP -->

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>

</html>