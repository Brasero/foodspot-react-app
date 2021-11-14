<div class="w-100 p-1" id="page">
    <h1 class="h1 text-secondary text-start">Mon compte</h1>
    <div class="card-deck gap-0 row">
        <div class="card col-md-6 col-12 border-secondary mt-4 p-0">
            <div class="card-content">
                <div class="card-header bg-secondary ms-0 me-0">
                    <div class="card-title h4 text-light">Mes informations personnelles</div>
                </div>
                <div class="card-body">
                    <form action="" method="post" class="needs-validation">
                        <div class="input-group mb-1">
                            <label for="nomUser" class="input-group-text">Nom</label>
                            <input type="text" id="nomUser" name="nomUser" value="<?= $_SESSION['user']['nom_users'] ?>" class="form-control">
                        </div>
                        <div class="input-group mb-1">
                            <label for="prenomUser" class="input-group-text">Prénom</label>
                            <input type="text" id="prenomUser" name="prenomUser" value="<?= $_SESSION['user']['prenom_users'] ?>" class="form-control">
                        </div>
                        <div class="input-group mb-1">
                            <label for="mailUser" class="input-group-text">E-mail</label>
                            <input type="email" id="mailUser" name="mailUser" value="<?= $_SESSION['user']['mail_users'] ?>" class="form-control">
                        </div>
                        <div class="input-group mb-1">
                            <label for="telUser" class="input-group-text">+33</label>
                            <input type="tel" class="form-control" id="telUser" name="telUser" value="<?= $_SESSION['user']['tel_users'] ?>">
                        </div>
                        <div class="input-group mb-1">
                            <label for="adresseUser" class="input-group-text">Adresse</label>
                            <input type="text" id="adresseUser" name="adresseUser" value="<?= $_SESSION['user']['adresse_users'] ?>" class="form-control">
                        </div>
                    </form>
                </div> 
            </div>
        </div>
        <div class="card col-md-6 col-12 border-secondary mt-4 p-0">
            <div class="card-content">
                <div class="card-header bg-secondary">
                    <div class="card-title h4 text-light">Mes commandes</div>
                </div>
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>
</div>