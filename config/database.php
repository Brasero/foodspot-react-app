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

}

?>