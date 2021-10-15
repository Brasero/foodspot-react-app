<?php
$_SESSION['user_id'] = 'brandon';

$query = $bdd->connexion->query('SELECT * FROM cart WHERE client_id ='.$_SESSION['user_id'].'');

$return = $query->fetch();

echo $return;

echo "<h1>I'm your modal !</h1>";
?>