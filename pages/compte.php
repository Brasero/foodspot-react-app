<?php
setlocale(LC_ALL, 'fr_FR.utf-8', 'FRA');
date_default_timezone_set('Europe/Paris');

if(isset($_POST['passUser'])){
    if(password_verify($_POST['passUser'], $_SESSION['user']['mdp_users'])){
        $id = $_SESSION['user']['id_users'];
        if(isset($_POST['nomUser']) && !empty($_POST['nomUser'])){
            $bdd->updateUserInfo($id, 'nom_users', $_POST['nomUser'], 0);
            $_SESSION['user']['nom_users'] = $_POST['nomUser'];
        }
        if(isset($_POST['prenomUser']) && !empty($_POST['prenomUser'])){
            $bdd->updateUserInfo($id, 'prenom_users', $_POST['prenomUser'], 0);
            $_SESSION['user']['prenom_users'] = $_POST['prenomUser'];
        }
        if(isset($_POST['telUser']) && !empty($_POST['telUser'])){
            $bdd->updateUserInfo($id, 'tel_users', $_POST['telUser'], 1);
            $_SESSION['user']['tel_users'] = $_POST['telUser'];
        }
        if(isset($_POST['adresseUser']) && !empty($_POST['adresseUser'])){
            $bdd->updateUserInfo($id, 'adresse_users', $_POST["adresseUser"], 0);
            $_SESSION['user']['adresse_users'] = $_POST['adresseUser'];
        }
        if(isset($_POST['villeUser']) && !empty($_POST['villeUser'])){
            $villeQueryStr = 'SELECT * FROM ville WHERE nom LIKE "'.$_POST['villeUser'].'%"';
            $villeQuery = $bdd->connexion->query($villeQueryStr);
            $dbVille = $villeQuery->fetch();

            if($dbVille != false){
                $idVille = $dbVille['ID'];
                $bdd->updateUserInfo($id, 'id_ville_users', $idVille, 1);
                $_SESSION['user']['id_ville_users'] = $idVille;
            }
        }

        if(isset($_POST['newPassUser'], $_POST['confirmPassUser']) && $_POST['newPassUser'] === $_POST['confirmPassUser'] && !password_verify($_POST['newPassUser'], $_SESSION['user']['mdp_users']) && !empty($_POST['newPassUser']) && !empty($_POST['confirmPassUser'])){
            $hashPass = password_hash($_POST['newPassUser'], PASSWORD_DEFAULT);
            $bdd->updateUserInfo($id, 'mdp_users', $hashPass, 0);
            $_SESSION['user']['mdp_users'] = $hashPass;
        }
    }
    else{
        echo '<div class="text-danger text-center">Le mot de passe ne correspond pas.</div>';
    }
}

$villeQueryStr = 'SELECT * FROM ville
                    WHERE id = :id';
$villeQuery = $bdd->connexion->prepare($villeQueryStr);
$villeQuery->bindParam(':id', $_SESSION['user']['id_ville_users'], PDO::PARAM_INT);
$villeQuery->execute();

$ville = $villeQuery->fetch();

$commandeQueryStr = 'SELECT * FROM commande
                    INNER JOIN statut_commande
                    ON statut_commande.identifiant_commande = commande.identifiant_commande
                    WHERE commande.id_users = :id AND statut_commande.statut_commande_value < 2';
$commandeQuery = $bdd->connexion->prepare($commandeQueryStr);
$commandeQuery->bindParam(':id', $_SESSION['user']['id_users']);
$commandeQuery->execute();
$commandeArrayClassed = [];

if($commandeQuery != false){
    $commandeArray = $commandeQuery->fetchAll();
    foreach($commandeArray as $commande){
        if(isset($commandeArrayClassed[$commande['identifiant_commande']])){
            array_push($commandeArrayClassed[$commande['identifiant_commande']], $commande);
        }
        else{
            $commandeArrayClassed[$commande['identifiant_commande']][0] = $commande;
        }
    }
    foreach($commandeArrayClassed as $commandeNb => $commandeItems){
        $total = 0;
        $nbArticle = 0;
        foreach($commandeItems as $itemId => $item){
            $produitInfoQuery = $bdd->connexion->query('SELECT * FROM produits WHERE id_produits = '.$item['id_produits'].'');
            $produitInfo = $produitInfoQuery->fetch();
            $commandeArrayClassed[$commandeNb][$itemId]['info_produit'] = $produitInfo;
            $total += floatval($item['prix_commande']);
            $nbArticle++;
        }
        $commandeArrayClassed[$commandeNb]['total'] = $total;    
        $commandeArrayClassed[$commandeNb]['nbArticle'] = $nbArticle;    
    }
}
?>



<div class="w-100 p-1 p-md-0" id="page">
    <h1 class="h1 text-secondary text-start">Mon compte</h1>
    <div class="row row-cols-1 row-cols-md-2 gap-0">
        <div class="card col border-secondary p-0">
                <div class="card-header bg-secondary ms-0 me-0">
                    <div class="card-title h4 text-light">Mes informations personnelles</div>
                </div>
                <div class="card-body">
                    <form action="index.php?page=compte" method="post" class="needs-validation" novalidate>
                        <h5 class="h5 text-muted">Identité</h5>
                        <div class="input-group mb-1">
                            <label for="nomUser" class="input-group-text">Nom</label>
                            <input type="text" id="nomUser" name="nomUser" placeholder="<?= $_SESSION['user']['nom_users'] ?>" class="form-control">
                        </div>
                        <div class="input-group mb-1">
                            <label for="prenomUser" class="input-group-text">Prénom</label>
                            <input type="text" id="prenomUser" name="prenomUser" placeholder="<?= $_SESSION['user']['prenom_users'] ?>" class="form-control">
                        </div>
                        <div class="input-group mb-1">
                            <label for="mailUser" class="input-group-text">E-mail</label>
                            <input type="email" id="mailUser" name="mailUser" placeholder="<?= $_SESSION['user']['mail_users'] ?>" disabled class="form-control">
                        </div>
                        <div class="input-group mb-4">
                            <label for="telUser" class="input-group-text">+33</label>
                            <input type="tel" pattern="[0-9]{9}" size="8" class="form-control" id="telUser" minlength="9" maxlength="9" name="telUser" placeholder="<?= $_SESSION['user']['tel_users'] ?>">
                            <small class="invalid-tooltip">9 caractères requis, uniquement numérique.</small>
                        </div>
                        <h5 class="h5 text-muted">Adresse</h5>
                        <div class="input-group mb-1">
                            <label for="adresseUser" class="input-group-text">Adresse</label>
                            <input type="text" id="adresseUser" name="adresseUser" placeholder="<?= $_SESSION['user']['adresse_users'] ?>" class="form-control">
                        </div>
                        <div class="input-group mb-1">
                            <label for="postalUser" class="input-group-text">Code postal</label>
                            <input type="text" id="postalUser" name="postalUser" disabled placeholder="<?= $ville['codePostal'] ?>" class="form-control">
                        </div>
                        <div class="input-group mb-1">
                            <label for="villeUser" class="input-group-text">Ville</label>
                            <input type="text" id="villeUser" name="villeUser" placeholder="<?= $ville['nom'] ?>" class="form-control">
                        </div>
                        <div class="input-group mt-3">
                            <label for="newPassUser" class="input-group-text">
                                <span class="bi bi-lock"></span>
                            </label>
                            <input type="password" id="newPassUser" name="newPassUser" minlength="4" maxlength="16" placeholder="Modifier mon mot de passe" class="form-control">
                            <small class="invalid-tooltip">Entre 4 et 16 caratères requis</small>
                        </div>
                        <div class="input-group mt-1">
                            <label for="confirmPassUser" class="input-group-text">
                                <span class="bi bi-lock"></span>
                            </label>
                            <input type="password" id="confirmPassUser" name="confirmPassUser" minlength="4" maxlength="16" placeholder="Confirmer mon nouveau mot de passe" class="form-control">
                            <small class="invalid-tooltip">Entre 4 et 16 caratères requis</small>
                        </div>
                        <div class="input-group mt-3 border-danger position-relative">
                            <label for="passUser" class="input-group-text">
                                <span class="bi bi-unlock"></span>
                            </label>
                            <input type="password" id="passUser" name="passUser" placeholder="Mot de passe actuel" class="form-control" required />
                            <small class="invalid-tooltip">Requis pour valider la modification</small>
                        </div>
                        <div class="d-grid mt-5">
                            <button class="btn btn-outline-warning" type="submit">
                                <span class="bi bi-check-lg"></span>
                                Modifier mes informations
                            </button>
                        </div>
                    </form>
                </div> 
        </div>
        <div class="card col border-secondary p-0">
            <div class="card-content">
                <div class="card-header bg-secondary">
                    <div class="card-title h4 text-light">Mes commandes</div>
                </div>
                <div class="card-body">
                    <?php
                        if(isset($commandeArrayClassed) && !empty($commandeArrayClassed)){
                            echo '<div class="row row-cols-auto gap-2">';

                                foreach($commandeArrayClassed as $commandeNb => $commandeItems){
                                    $date = strftime('%A %d %b %Y à %H:%M', $commandeNb);
                                    echo '<div class="card border-primary p-0">
                                            <div class="card-header">
                                                <h4 class="card-title">Commande N°'.$commandeNb.' <small class="text-muted ms-md-2 fs-6">'.$date.'</small></h4>';
                                            if($commandeItems[0]['statut_commande_value'] == 0){
                                                echo '<div class="card-subtitle text-success">Commande reçue</div>';
                                            }
                                            elseif($commandeItems[0]['statut_commande_value'] == 1){
                                                echo '<div class="card-subtitle text-success">Commande en cours</div>';
                                            }
                                        
                                        echo '<div class="d-flex" style="justify-content: space-between; align-items: center;><small class="text-muted">'.$commandeArrayClassed[$commandeNb]['nbArticle'].' articles</small> <strong class="text-right">'.number_format($commandeArrayClassed[$commandeNb]['total'], 2, ',', '.').'€</strong></div>';    

                                    echo   '</div>';

                                    echo '</div>';
                                }

                            echo '</div>';
                        }
                        else{
                            echo '<div class="card-text fw-bold">Vous n\'avez aucune commande en cours.</div>';
                        }
                        
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

(function () {
  'use strict'
  var forms = document.querySelectorAll('.needs-validation')

  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})()

</script>