<?php
$servername = "mysql-brayanloic.alwaysdata.net";
$username = "338019"; // Remplacez par le nom d'utilisateur de votre base de données si nécessaire
$password = "#Brayan250#alwaysdata"; // Remplacez par le mot de passe de votre base de données si nécessaire
$dbname = "brayanloic_supercar"; // Remplacez par le nom de votre base de données

// Créer une connexion à la base de données
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion à la base de données
if ($mysqli->connect_error) {
    die("La connexion à la base de données a échoué : " . $mysqli->connect_error);
}

// Définir le jeu de caractères de la connexion
$mysqli->set_charset("utf8");

?>
