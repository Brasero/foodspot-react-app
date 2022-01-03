<?php

class DataBase{

    private $host = "localhost";//foodspv123.mysql.db
    private $dbName = "foodspot";//foodspv123
    private $char = "utf8";
    private $user = "root";//foodspv123
    private $password = "";//Foodspv123
    public $connexion;

    public function getConnexion(){

        $this->connexion = null;

        try {
            $this->connexion = new PDO('mysql:host='.$this->host.';dbname='.$this->dbName.';charset='.$this->char, $this->user, $this->password);
        }

        catch(PDOException $fail) {
            echo 'Erreur de connexion -> ', $fail->getMessage();
            die();
        }
    }

    public function getCategoriesName() {
        $queryStr = 'SELECT * FROM categories';
        $query = $this->connexion->query($queryStr);
        $respond = $query->fetchAll();
        return $respond;
    }

    public function getProduits() {
        $queryStr = 'SELECT * FROM produits';
        $query = $this->connexion->query($queryStr);
        $respond = $query->fetchAll();
        return $respond;
    }

    
    public function createTemporaryUser() {
        if(!isset($_SESSION['user'])){
            $_SESSION['user']['identifiant_users'] = time();
        }
    }

    public function logIn($mail, $mdp){
        if(isset($mail, $mdp)){
            if(!empty($mail) AND !empty($mdp)){
                $initQuery = $this->connexion->prepare('SELECT * FROM users WHERE mail_users = :mail');
                $initQuery->bindParam(':mail', $mail, PDO::PARAM_STR);
                $initQuery->execute();

                if($initQuery != false){
                    $bddUserInfo = $initQuery->fetch();
                    if(password_verify($mdp, $bddUserInfo['mdp_users'])){
                        $_SESSION['user'] = $bddUserInfo;
                        $return = '<span class="text-success text-center">Connexion reussie</span>';
                        return $return;
                    }
                    else{
                        $return = '<span class="text-danger text-center">Votre identifiant et votre mot de passe ne correspondent pas.</span>';
                        return $return;
                    }
                }
                else{
                    $return = '<span class="text-danger text-center">Votre identifiant est inconnu.</span>';
                    return $return;
                }
                
            }
        }
    }

    public function getCart($user){
        if(isset($user['id_users'])){   
            $this->connexion->query('UPDATE cart SET id_users = '.$user['id_users'].' WHERE id_users = '.$user['identifiant_users']);
            $queryStr = 'SELECT * FROM cart WHERE id_users = '.$user['id_users'].' OR id_users = '.$user['identifiant_users'];
            $query = $this->connexion->query($queryStr);
            
            if($query != false AND !empty($query)){
                $respond = $query->fetchAll();
            }
            else{
                $respond = 'Votre panier est vide';
            }
        }
        elseif(isset($user['identifiant_users'])){
            $queryStr = 'SELECT * FROM cart WHERE id_users = '.$user['identifiant_users'];
            $query = $this->connexion->query($queryStr);

            if($query != false AND !empty($query)){
                $respond = $query->fetchAll();
            }
            else{
                $respond = 'Votre panier est vide';
            }
        }
        
        

        return $respond;
    }

    

    public function getProductNameById($id) {
        $queryStr = 'SELECT nom_produits FROM produits WHERE id_produits = '.$id.'';
        $query = $this->connexion->query($queryStr);
        $respond = $query->fetch();
        return $respond;
    }

    public function getProductById($id) {
        $queryStr = 'SELECT * FROM produits WHERE id_produits = '.$id.'';
        $query = $this->connexion->query($queryStr);
        $respond = $query->fetch();
        return $respond;
    }

    public function validateCommande($commande){
        $queryStr = 'UPDATE statut_commande SET statut_commande_value = 1 WHERE identifiant_commande = :id';
        $query = $this->connexion->prepare($queryStr);
        $query->bindParam(':id', $commande, PDO::PARAM_INT);
        $query->execute();
    }

    public function deliverCommande($commande){
        $queryStr = 'UPDATE statut_commande SET statut_commande_value = 2 WHERE identifiant_commande = :id';
        $query = $this->connexion->prepare($queryStr);
        $query->bindParam(':id', $commande, PDO::PARAM_INT);
        $query->execute();
    }

    function updateUserInfo($userId, $userInfoName, $userInfo, $param){
        if($param == 1){
            $pdoParam = PDO::PARAM_INT;
        }
        elseif($param == 0){
            $pdoParam = PDO::PARAM_STR;
        }
        $updateQueryStr = 'UPDATE users SET '.$userInfoName.' = :info WHERE id_users = :id';
        $updateQuery = $this->connexion->prepare($updateQueryStr);
        $updateQuery->bindParam(':info', $userInfo, $pdoParam);
        $updateQuery->bindParam(':id', $userId, PDO::PARAM_INT);
        $updateQuery->execute();
    }

    public function getCommande0() {
        $idCommandeQuery = $this->connexion->query('SELECT statut_commande.identifiant_commande
                                                FROM statut_commande
                                                WHERE statut_commande.statut_commande_value = 0 
                                                ORDER BY identifiant_commande DESC');
        $idCommandeArray = $idCommandeQuery->fetchAll();
        $respond = [];
        foreach($idCommandeArray as $idCommande){
            if(isset($idCommande)){
                $query = $this->connexion->query('SELECT * FROM commande
                                            WHERE identifiant_commande = '.$idCommande['identifiant_commande'].'');
                $produitIds = $query->fetchAll();
                $userInfo = $this->getUserById($produitIds[0]['id_users']);
                $respond[$idCommande['identifiant_commande']]['prix_total'] = 0;
                $respond[$idCommande['identifiant_commande']]['user'] = $userInfo;
                foreach($produitIds as $produitId){
                    if(isset($produitId['prix_commande'])){
                        $respond[$idCommande['identifiant_commande']]['prix_total'] += $produitId['prix_commande'];
                    }
                    $array = [];
                    $produitName = $this->getProductNameById($produitId['id_produits']);
                    if(isset($produitId['id_ingredients']) && $produitId['id_ingredients'] != NULL){
                        $ingredientArray = $this->getIngredientList($produitId['id_ingredients']);
                        $array['ingredients'] = $ingredientArray;
                    }
                    $array['nom_produits'] = $produitName['nom_produits'];
                    array_push($respond[$idCommande['identifiant_commande']], $array);
                }
            }
        }
        return $respond;
    }

    public function getCommande1() {
        $idCommandeQuery = $this->connexion->query('SELECT statut_commande.identifiant_commande
                                                FROM statut_commande
                                                WHERE statut_commande.statut_commande_value = 1
                                                ORDER BY identifiant_commande DESC');
        $idCommandeArray = $idCommandeQuery->fetchAll();
        $respond = [];
        foreach($idCommandeArray as $idCommande){
            if(isset($idCommande)){
                $query = $this->connexion->query('SELECT * FROM commande
                                            WHERE identifiant_commande = '.$idCommande['identifiant_commande'].'');
                $produitIds = $query->fetchAll();
                $userInfo = $this->getUserById($produitIds[0]['id_users']);
                $respond[$idCommande['identifiant_commande']]['prix_total'] = 0;
                $respond[$idCommande['identifiant_commande']]['user'] = $userInfo;
                foreach($produitIds as $produitId){
                    if(isset($produitId['prix_commande'])){
                        $respond[$idCommande['identifiant_commande']]['prix_total'] += $produitId['prix_commande'];
                    }
                    $array = [];
                    $produitName = $this->getProductNameById($produitId['id_produits']);
                    if(isset($produitId['id_ingredients']) && $produitId['id_ingredients'] != NULL){
                        $ingredientArray = $this->getIngredientList($produitId['id_ingredients']);
                        $array['ingredients'] = $ingredientArray;
                    }
                    $array['nom_produits'] = $produitName['nom_produits'];
                    array_push($respond[$idCommande['identifiant_commande']], $array);
                }
            }
        }
        return $respond;
    }

    private function getUserById($id){
        $queryStr = 'SELECT identifiant_users, mail_users, nom_users, prenom_users, adresse_users, id_ville_users, tel_users, ville.nom AS nom_ville, ville.codePostal 
                    FROM users
                    INNER JOIN ville 
                    ON ville.ID = users.id_ville_users
                    WHERE id_users = '.$id.'';
        $query = $this->connexion->query($queryStr);
        $respond = $query->fetch();
        return $respond;
    }

    public function getIngredientList($numList) {

        $ingredientIdArray = explode(';', $numList);
        $returnArray = [];

        foreach($ingredientIdArray as $ingredientId){
            $ingredientQuery = $this->connexion->query('SELECT * FROM ingredients WHERE id_ingredients = '.$ingredientId.'');
            $ingredientDetail = $ingredientQuery->fetch();
            array_push($returnArray, $ingredientDetail);
        }

        return $returnArray;
    }

    public function getIngredients(){
        $queryStr = 'SELECT * FROM ingredients ORDER BY nom_ingredients';
        $query = $this->connexion->query($queryStr);
        $respond = $query->fetchAll();
        return $respond;
    }

    public function updateAttrProduit($attrName, $attr, $id, $type){
         if($type === 1){
             $pdoParam = PDO::PARAM_INT;
         }
         else{
             $pdoParam = PDO::PARAM_STR;
         }

        $updateQueryStr = 'UPDATE produits SET '.$attrName.'= :val WHERE id_produits = :id';
        $updateQuery = $this->connexion->prepare($updateQueryStr);
        $updateQuery->bindParam(':val', $attr, $pdoParam);
        $updateQuery->bindParam(':id', $id, PDO::PARAM_INT);
        $updateQuery->execute();
    }

    public function updateAttrCategorie($attrName, $attr, $id, $type){
        if($type === 1){
            $pdoParam = PDO::PARAM_INT;
        }
        else{
            $pdoParam = PDO::PARAM_STR;
        }

       $updateQueryStr = 'UPDATE categories SET '.$attrName.'= :val WHERE id_categories = :id';
       $updateQuery = $this->connexion->prepare($updateQueryStr);
       $updateQuery->bindParam(':val', $attr, $pdoParam);
       $updateQuery->bindParam(':id', $id, PDO::PARAM_INT);
       $updateQuery->execute();
    }

    public function addProduct($name, $price, $ingredient, $cat, $img){
        $imgName = $img['img']['name'];
        $imgObj = $img['img']['tmp_name'];
        $identifiant = time();
        $ingredientStr = '';
        $price = str_replace(',', '.', $price);
        $dispo = 1;
        //Construction de la chaine de caractère d'ingredients

        for($i = 0; $i < sizeof($ingredient); $i++){
            if($i === sizeof($ingredient) - 1){
                $ingredientStr = $ingredientStr.$ingredient[$i];
            }
            elseif($i === 0){
                $ingredientStr = $ingredient[$i].';';
            }
            else{
                $ingredientStr = $ingredientStr.$ingredient[$i].';';
            }
        }

        //End

        $insertQueryStr = 'INSERT INTO produits 
                            (identifiant_produits, nom_produits, prix_produits, id_categorie, id_ingredients, dispo_produits, img_produits) 
                            VALUES (:idProd, :nom, :prix, :cat, :ingredients, :dispo, :img)';

        $insertQuery = $this->connexion->prepare($insertQueryStr);
        $insertQuery->bindParam(':idProd', $identifiant, PDO::PARAM_INT);
        $insertQuery->bindParam(':nom', $name, PDO::PARAM_STR);
        $insertQuery->bindParam(':prix', $price, PDO::PARAM_STR);
        $insertQuery->bindParam(':cat', $cat, PDO::PARAM_INT);
        $insertQuery->bindParam(':ingredients', $ingredientStr, PDO::PARAM_STR);
        $insertQuery->bindParam(':dispo', $dispo, PDO::PARAM_INT);
        $insertQuery->bindParam(':img', $imgName, PDO::PARAM_STR);
        
        if($insertQuery->execute()){
            move_uploaded_file($imgObj, '../assets/img/'.$imgName);
        }
        else{
            return $insertQuery->errorInfo();
        }
        header('Location: index.php?page=2');

    }

    public function addProductWhithoutIngredient($nom, $prix, $cat, $img){
        $imgName = $img['img']['name'];
        $imgObj = $img['img']['tmp_name'];
        $identifiant = time();
        $price = str_replace(',', '.', $prix);
        $dispo = 1;
        $ingredient = null;

        $insertQueryStr = 'INSERT INTO produits
                            (identifiant_produits, nom_produits, prix_produits, id_categorie, id_ingredients, dispo_produits, img_produits)
                            VALUES (:idProd, :nom, :prix, :cat, :ingredient, :dispo, :img)';

        $insertQuery = $this->connexion->prepare($insertQueryStr);
        $insertQuery->bindParam(':idProd', $identifiant, PDO::PARAM_INT);
        $insertQuery->bindParam(':nom', $nom, PDO::PARAM_STR);
        $insertQuery->bindParam(':prix', $price, PDO::PARAM_STR);
        $insertQuery->bindParam(':cat', $cat, PDO::PARAM_INT);
        $insertQuery->bindParam(':ingredient', $ingredient, PDO::PARAM_NULL);
        $insertQuery->bindParam(':dispo', $dispo, PDO::PARAM_INT);
        $insertQuery->bindParam(':img', $imgName, PDO::PARAM_STR);

        if($insertQuery->execute()){
            move_uploaded_file($imgObj, '../assets/img/'.$imgName);
        }
        else{
            return $insertQuery->errorInfo();
        }
        header('Location: index.php?page=2');
    }

    public function addIngredient($name, $price){
        $identifiant = time();
        $price = str_replace(',', '.', $price);

        $insertStr = 'INSERT INTO ingredients (identifiant_ingredients, nom_ingredients, prix_ingredients, dispo_ingredients) 
                        VALUES (:id, :nom, :prix, 1)';
        $insertQuery = $this->connexion->prepare($insertStr);
        $insertQuery->bindParam(':id', $identifiant, PDO::PARAM_INT);
        $insertQuery->bindParam(':nom', $name, PDO::PARAM_STR);
        $insertQuery->bindParam(':prix', $price, PDO::PARAM_STR);
        $insertQuery->execute();
        header('Location: index.php?page=3');
    }
}

?>