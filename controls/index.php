<?php
session_start();

include_once('../config/database.php');

$passAttempt = 'FoodSpotNancy';

$bdd = new DataBase;

$bdd->getConnexion();

if(isset($_POST['adminPass']) || isset($_SESSION['authorized'])){
    if(isset($_POST['adminPass']) && $_POST['adminPass'] === $passAttempt || $_SESSION['authorized']){
        $_SESSION['authorized'] = true;
    }
    else{
        $_SESSION['authorized'] = false;
    }
}
else{
    $_SESSION['authorized'] = false;   
}

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

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.0/font/bootstrap-icons.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        
    </head>

    <body class="w-100 bg-light" style="align-items: center;">

        <header>

        </header>
        <?php 

        if(isset($_SESSION['authorized']) && $_SESSION['authorized'] === true){

            include_once('./navBar/navBar.php'); 

            if(isset($_GET['page'])){
                switch($_GET['page']){
                    case 1 :
                        include_once('');
                        break;
                    case 2 :
                        include_once('');
                        break;
                    case 3 :
                        include_once('');
                        break;
                }
            }
            else{
                include_once('./acceuil/acceuil.php');
            }   

        }
        else{
            include_once('./connexion/connexion.php');
        }

            
        ?>
        


        <footer>

        </footer>
        <!-- END OF FILE  -->
        
        <!-- BOOTSTRAP -->

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    </body>

</html>