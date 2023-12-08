<?php
// Inclure le fichier de connexion à la base de données
include('admin_database.php');

// Vérifier si l'ID du message est présent dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id']; // Utiliser l'ID du message

    // Préparez votre requête SQL pour supprimer le message en utilisant l'ID du message
    $query = "DELETE FROM contact WHERE id_client = $id";

    // Exécutez la requête de suppression
    if ($mysqli->query($query)) {
        // Redirigez l'utilisateur vers la page des messages après la suppression
        header("Location: Contact.php");
        exit();
    } else {
        echo "Erreur lors de la suppression du message : " . $mysqli->error;
    }
} else {
    echo "ID de message non spécifié.";
}
?>
