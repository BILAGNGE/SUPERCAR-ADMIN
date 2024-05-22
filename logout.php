<?php
session_start();
require_once('admin_database.php'); // Inclure le fichier de connexion à la base de données

// Vérifier si l'administrateur est connecté, sinon rediriger vers la page de connexion
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

// Réinitialiser la valeur "acces" à 0
$username = $_SESSION["admin_username"];
$sql_update_acces = "UPDATE admin SET acces = 0 WHERE username_admin = '$username'";
$mysqli->query($sql_update_acces);

// Détruire la session
session_unset();
session_destroy();

// Rediriger vers la page de connexion
header("Location: login.php");
exit();
?>
