<?php
session_start();

$data = $_GET['mode'];

$_SESSION['user']['mode'] = $data;

?>