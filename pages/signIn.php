<?php 
if (isset($_POST['g-recaptcha-response']) && !empty ($_POST['g-recaptcha-response'])) {
    $secret = '6Lf9ghAdAAAAAJmWH6u-vrtwWUrM4Dg-6sMrhm0g'; 
    $verifyResponse = file_get_contents ('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST ['g-recaptcha-response']); 
    $responseData = json_decode($verifyResponse); 
    if ($responseData->success) {
        $succMsg = 'Inscription reussie.</br> Vous pouvez maintenant vous connetcer.';
    } else {
        $errMsg = 'La vérification du robot a échoué, veuillez réessayer.'; 
    }
}
else{
    $errMsg = 'Veuillez valider le captcha.';
}
?>

<?php

if(!isset($succMsg)){

    if(isset($errMsg)){
        echo '<div class="h3 text-danger text-center">'.$errMsg.'</div>';
    }
?>
<form action="index.php?page=2" method="post" class="col-md-4 mt-5 me-auto ms-auto">
    <h2 class="h2 mb-3">
        <span class="bi bi-file-earmark-person"></span>
        S'inscrire
    </h2>
    <div class="form-floating mb-2">
        <input class="form-control" id="inputName" type="text" name="signIn_nom" placeholder="Nom" />
        <label for="inputName">Nom</label>
    </div>
    <div class="form-floating mb-2">
        <input class="form-control" id="inputSurname" type="text" name="signIn_prenom" placeholder="prénom" />
        <label for="inputSurname">Prénom</label>
    </div>
    <div class="form-floating mb-2">
        <input class="form-control" id="inputAdresse" type="text" auto-complete="address-level1" name="signIn_adresse" placeholder="N° + rue / avenue / allée" />
        <label for="inputAdresse">Adresse</label>
    </div>
    <div class="form-floating mb-3">
        <input class="form-control" id="inputVille" type="text" auto-complete="address-level2" name="signIn_ville" placeholder="Ville" />
        <label for="inputVille">Ville</label>
    </div>
    <div class="form-floating mb-3">
        <input class="form-control" type="email" id="inputEmail" name="signIn_email" placeholder="Adresse Email" />
        <label for="inputEmail">Adresse Email</label>
    </div>
    <div class="form-floating mb-3">
       <input class="form-control" id="inputNum" type="number" name="signIn_num" placeholder="Téléphone" />
       <label for="inputNum">Numéro de téléphone</lebel> 
    </div>
    <div class="form-floating mb-2">
        <input class="form-control" type="password" id="inputPassword" name="signIn_password" auto-complete="current-password" placeholder="Mot de passe" />
        <label for="inputPassword">Mot de passe</label>
    </div>
    <div class="form-floating mb-3">
        <input class="form-control" type="password" id="inputConfirmPassword" name="signIn_confirm_password" placeholder="Confrimez votre mot de passe" />
        <label for="inputConfirmPassword">Confirmez votre mot de passe</label>
    </div>
    <div class="g-recaptcha w-100 mb-2 form-floating" data-sitekey="6Lf9ghAdAAAAAGQAggdV_lkzKjtDfnckNvR_X0oN">
    </div>
    <button type="submit" class="btn btn-outline-dark">S'inscrire</button>
</form>
<?php

}
else if(isset(
    $succMsg, 
    $_POST['signIn_nom'], 
    $_POST['signIn_prenom'], 
    $_POST['signIn_adresse'], 
    $_POST['signIn_ville'], 
    $_POST['signIn_email'], 
    $_POST['signIn_password'], 
    $_POST['signIn_confirm_password']) && 
    !empty($_POST['signIn_nom']) && 
    !empty($_POST['signIn_prenom']) && 
    !empty($_POST['signIn_adresse']) && 
    !empty($_POST['signIn_ville']) && 
    !empty($_POST['signIn_email']) && 
    !empty($_POST['signIn_password']) && 
    !empty($_POST['signIn_confirm_password'])){

        if($_POST['signIn_password'] === $_POST['signIn_confirm_password']){

            //Here goes the db insert after the mail verification.

            $dbUsersQuery = $bdd->connexion->query('SELECT * FROM users');
            $dbUsersArray = $dbUsersQuery->fetchAll();
            $emailExist = false;

            foreach($dbUsersArray as $dbUsers){
                if($dbUsers['mail_users'] === $_POST['signIn_email']){
                    $emailExist = true;
                    break;
                }
            }

            if($emailExist){
                echo'<div class="text-danger text-center h3">Votre adresse email est déjà connue de nos systéme, connectez-vous.</div>';
            }
            else{
                $nom = $_POST['signIn_nom'];
                $prenom = $_POST['signIn_prenom'];
                $adresse = $_POST['signIn_adresse'];
                $ville = $_POST['signIn_ville'];
                $email = $_POST['signIn_email'];
                $num = $_POST['signIn_num'];
                $pass = $_POST['signIn_password'];
                echo $ville;

                $hashPass = password_hash($pass, PASSWORD_DEFAULT);

                $villeQuery = $bdd->connexion->query('SELECT * FROM ville WHERE nom LIKE "'.$ville.'%"');
                
                if($villeQuery != false){
                    $dbville = $villeQuery->fetch();
                    $villeId = $dbville['ID'];
                    $identifiant = time();

                    $queryStr = 'INSERT INTO users (identifiant_users, nom_users, prenom_users, mail_users, adresse_users, id_ville_users, tel_users, mdp_users, isManager_users)
                                VALUES (:identifiant, :nom, :prenom, :mail, :adresse, :idVille, :num, :mdp, 0)';
                    $insertQuery = $bdd->connexion->prepare($queryStr);

                    $insertQuery->bindValue(':identifiant', $identifiant, PDO::PARAM_INT);
                    $insertQuery->bindValue(':nom', $nom, PDO::PARAM_STR);
                    $insertQuery->bindValue(':prenom', $prenom, PDO::PARAM_STR);
                    $insertQuery->bindValue(':mail', $email, PDO::PARAM_STR);
                    $insertQuery->bindValue(':adresse', $adresse, PDO::PARAM_STR);
                    $insertQuery->bindValue(':idVille', $villeId, PDO::PARAM_INT);
                    $insertQuery->bindValue(':num', $num, PDO::PARAM_INT);
                    $insertQuery->bindValue(':mdp', $hashPass, PDO::PARAM_STR);

                    if($insertQuery->execute()){
                        echo '<div class="text-success text-center h4">Votre inscription a bien été enregistrée.</div>';
                    }
                    else{
                        echo '<div class="text-danger text-center h4">Une erreur est survenue.</br>Vérifiez votre connexion et recommencez.</div>';
                        var_dump($insertQuery->errorInfo());
                    }
                }
                else{
                    echo '<div class="text-danger text-center h4">Nous ne connaissons pas votre ville désolé.</div>';
                }
                

            }

        }
        else{
            echo '<div class="text-danger h3 text-center">Vos mot de passe ne correspondent pas, veuillez réessayer</div>';
            echo '
            <form action="index.php?page=2" method="post" class="col-md-4 mt-5 me-auto ms-auto">
                <h2 class="h2 mb-3">
                    <span class="bi bi-file-earmark-person"></span>
                    S\'inscrire
                </h2>
                <div class="form-floating mb-2">
                    <input class="form-control" id="inputName" type="text" name="signIn_nom" placeholder="Nom" />
                    <label for="inputName">Nom</label>
                </div>
                <div class="form-floating mb-2">
                    <input class="form-control" id="inputSurname" type="text" name="signIn_prenom" placeholder="prénom" />
                    <label for="inputSurname">Prénom</label>
                </div>
                <div class="form-floating mb-2">
                    <input class="form-control" id="inputAdresse" type="text" auto-complete="address-level1" name="signIn_adresse" placeholder="N° + rue / avenue / allée" />
                    <label for="inputAdresse">Adresse</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" id="inputVille" type="text" auto-complete="address-level2" name="signIn_ville" placeholder="Ville" />
                    <label for="inputVille">Ville</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="email" id="inputEmail" name="signIn_email" placeholder="Adresse Email" />
                    <label for="inputEmail">Adresse Email</label>
                </div>
                <div class="form-floating mb-3">
               <input class="form-control" id="inputNum" type="number" name="signIn_num" placeholder="Téléphone" />
               <label for="inputNum">Numéro de téléphone</lebel> </div>
                <div class="form-floating mb-2">
                    <input class="form-control" type="password" id="inputPassword" name="signIn_password" auto-complete="current-password" placeholder="Mot de passe" />
                    <label for="inputPassword">Mot de passe</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="password" id="inputConfirmPassword" name="signIn_confirm_password" placeholder="Confrimez votre mot de passe" />
                    <label for="inputConfirmPassword">Confirmez votre mot de passe</label>
                </div>
                <div class="g-recaptcha w-100 mb-2 form-floating" data-sitekey="6Lf9ghAdAAAAAGQAggdV_lkzKjtDfnckNvR_X0oN">
                </div>
                <button type="submit" class="btn btn-outline-dark">S\'inscrire</button>
            </form>';
        }

}
else if(!isset($succMsg)){

    echo '
    <div class="text-danger h3 text-center">'.$errMsg.'</div>
    <form action="index.php?page=2" method="post" class="col-md-4 mt-5 me-auto ms-auto">
        <h2 class="h2 mb-3">
            <span class="bi bi-file-earmark-person"></span>
            S\'inscrire
        </h2>
        <div class="form-floating mb-2">
            <input class="form-control" id="inputName" type="text" name="signIn_nom" placeholder="Nom" />
            <label for="inputName">Nom</label>
        </div>
        <div class="form-floating mb-2">
            <input class="form-control" id="inputSurname" type="text" name="signIn_prenom" placeholder="prénom" />
            <label for="inputSurname">Prénom</label>
        </div>
        <div class="form-floating mb-2">
            <input class="form-control" id="inputAdresse" type="text" auto-complete="address-level1" name="signIn_adresse" placeholder="N° + rue / avenue / allée" />
            <label for="inputAdresse">Adresse</label>
        </div>
        <div class="form-floating mb-3">
            <input class="form-control" id="inputVille" type="text" auto-complete="address-level2" name="signIn_ville" placeholder="Ville" />
            <label for="inputVille">Ville</label>
        </div>
        <div class="form-floating mb-3">
            <input class="form-control" type="email" id="inputEmail" name="signIn_email" placeholder="Adresse Email" />
            <label for="inputEmail">Adresse Email</label>
        </div>
        <div class="form-floating mb-3">
       <input class="form-control" id="inputNum" type="number" name="signIn_num" placeholder="Téléphone" />
       <label for="inputNum">Numéro de téléphone</lebel> </div>
        <div class="form-floating mb-2">
            <input class="form-control" type="password" id="inputPassword" name="signIn_password" auto-complete="current-password" placeholder="Mot de passe" />
            <label for="inputPassword">Mot de passe</label>
        </div>
        <div class="form-floating mb-3">
            <input class="form-control" type="password" id="inputConfirmPassword" name="signIn_confirm_password" placeholder="Confrimez votre mot de passe" />
            <label for="inputConfirmPassword">Confirmez votre mot de passe</label>
        </div>
        <div class="g-recaptcha w-100 mb-2 form-floating" data-sitekey="6Lf9ghAdAAAAAGQAggdV_lkzKjtDfnckNvR_X0oN">
        </div>
        <button type="submit" class="btn btn-outline-dark">S\'inscrire</button>
</form>';
}

else{

    $errMsg = 'Une ou plusieurs informations sont manquantes, </br> veuillez recommencer.';

    echo '
    <div class="text-danger h3 text-center">'.$errMsg.'</div>
    <form action="index.php?page=2" method="post" class="col-md-4 mt-5 me-auto ms-auto">
        <h2 class="h2 mb-3">
            <span class="bi bi-file-earmark-person"></span>
            S\'inscrire
        </h2>
        <div class="form-floating mb-2">
            <input class="form-control" id="inputName" type="text" name="signIn_nom" placeholder="Nom" />
            <label for="inputName">Nom</label>
        </div>
        <div class="form-floating mb-2">
            <input class="form-control" id="inputSurname" type="text" name="signIn_prenom" placeholder="prénom" />
            <label for="inputSurname">Prénom</label>
        </div>
        <div class="form-floating mb-2">
            <input class="form-control" id="inputAdresse" type="text" auto-complete="address-level1" name="signIn_adresse" placeholder="N° + rue / avenue / allée" />
            <label for="inputAdresse">Adresse</label>
        </div>
        <div class="form-floating mb-3">
            <input class="form-control" id="inputVille" type="text" auto-complete="address-level2" name="signIn_ville" placeholder="Ville" />
            <label for="inputVille">Ville</label>
        </div>
        <div class="form-floating mb-3">
            <input class="form-control" type="email" id="inputEmail" name="signIn_email" placeholder="Adresse Email" />
            <label for="inputEmail">Adresse Email</label>
        </div>
        <div class="form-floating mb-3">
       <input class="form-control" id="inputNum" type="number" name="signIn_num" placeholder="Téléphone" />
       <label for="inputNum">Numéro de téléphone</lebel> </div>
        <div class="form-floating mb-2">
            <input class="form-control" type="password" id="inputPassword" name="signIn_password" auto-complete="current-password" placeholder="Mot de passe" />
            <label for="inputPassword">Mot de passe</label>
        </div>
        <div class="form-floating mb-3">
            <input class="form-control" type="password" id="inputConfirmPassword" name="signIn_confirm_password" placeholder="Confrimez votre mot de passe" />
            <label for="inputConfirmPassword">Confirmez votre mot de passe</label>
        </div>
        <div class="g-recaptcha w-100 mb-2 form-floating" data-sitekey="6Lf9ghAdAAAAAGQAggdV_lkzKjtDfnckNvR_X0oN">
        </div>
        <button type="submit" class="btn btn-outline-dark">S\'inscrire</button>
</form>';

}

?>