<?php

if(!isset($message)){

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
    <div class="form-floating mb-2">
        <input class="form-control" type="password" id="inputPassword" name="signIn_password" auto-complete="current-password" placeholder="Mot de passe" />
        <label for="inputPassword">Mot de passe</label>
    </div>
    <div class="form-floating mb-3">
        <input class="form-control" type="password" id="inputConfirmPassword" name="signIn_confirm_password" placeholder="Confrimez votre mot de passe" />
        <label for="inputConfirmPassword">Confirmez votre mot de passe</label>
    </div>
    <button type="submit" class="btn btn-outline-dark">Se connecter</button>
</form>
<?php

}

?>