<?php
session_start();
require_once 'admin_database.php'; // Inclure le fichier de connexion à la base de données
 
// Vérifier si l'administrateur est connecté
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}
 
// Récupérer l'ID du client à supprimer depuis les paramètres GET
if (isset($_GET['id_contact'])) {
    $id_contact= $_GET['id_contact'];
 
    // Utiliser une requête DELETE SQL pour cela
    $sql = "DELETE FROM contact WHERE id_contact = $id_contact";
 
    if ($mysqli->query($sql)) {
        // La suppression a réussi
        header("Location: Contact.php");
        exit();
    } else {
        // La suppression a échoué
        echo "La suppression du contact a échoué : " . $mysqli->error;
    }
} else {
    echo "ID de contact non spécifié.";
}
?>