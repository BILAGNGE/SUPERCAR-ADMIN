<?php
// Inclure le fichier de connexion à la base de données
include('admin_database.php');

// Vérifier si l'ID de la voiture est présent dans l'URL
if (isset($_GET['id'])) {
    $idVoiture = $_GET['id'];

    // Préparez votre requête SQL pour supprimer la voiture en utilisant l'ID
    $query = "DELETE FROM voiture WHERE id_voiture = $idVoiture";

    // Exécutez la requête de suppression
    if ($mysqli->query($query)) {
        // Redirigez l'utilisateur vers la page des voitures après la suppression
        header("Location: Voitures.php");
        exit();
    } else {
        echo "Erreur lors de la suppression de la voiture : " . $mysqli->error;
    }
} else {
    echo "ID de voiture non spécifié.";
}
?>
