<?php

class DataBase{

    private $host = "localhost";
    private $dbName = "foodspot";
    private $char = "utf8";
    private $user = "root";
    private $password = "";
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
        $queryStr = 'SELECT * FROM cart WHERE id_users = '.$user['id_users'];
        $query = $this->connexion->query($queryStr);
        if($query != false AND !empty($query)){
            $respond = $query->fetchAll();
        }
        else{
            $respond = 'Votre panier est vide';
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

    public function getCommande0() {
        $idCommandeQuery = $this->connexion->query('SELECT statut_commande.identifiant_commande
                                                FROM statut_commande
                                                WHERE statut_commande.statut_commande_value = 0');
        $idCommandeArray = $idCommandeQuery->fetchAll();
        $respond = [];

        foreach($idCommandeArray as $idCommande){
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
                $produitName = $this->getProductNameById($produitId['id_produits']);
                $ingredientArray = $this->getIngredientList($produitId['id_ingredients']);
                $array = [];
                $array['ingredients'] = $ingredientArray;
                $array['nom_produits'] = $produitName['nom_produits'];
                array_push($respond[$idCommande['identifiant_commande']], $array);
            }
        }
        return $respond;
    }

    public function getUserById($id){
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
        $queryStr = 'SELECT * FROM ingredients';
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