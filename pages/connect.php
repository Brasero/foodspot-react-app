<?php


if(!isset($message)){
?>


<form action="index.php?page=1" method="post" class="col-md-4 mt-5 me-auto ms-auto">
    <h2 class="h2 mb-3">
        <span class="bi bi-box-arrow-in-right"></span>
        Se connecter
    </h2>
    <div class="form-floating mb-3">
        <input class="form-control" type="email" id="inputEmail" name="connect_email" placeholder="Adresse Email" />
        <label for="inputEmail">Adresse Email</label>
    </div>
    <div class="form-floating mb-3">
        <input class="form-control" type="password" id="inputPassword" name="connect_password" auto-complete="current-password" placeholder="Mot de passe" />
        <label for="inputPassword">Mot de passe</label>
    </div>
    <button type="submit" class="btn btn-outline-dark">Se connecter</button>
</form>
<?php 

}
else{
echo   '<div class="text-center">'.$message.'</div>'; 
}
?>