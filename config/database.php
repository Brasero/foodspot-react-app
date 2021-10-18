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

}

?>