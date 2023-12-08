<?php
session_start();

// Vérifier si l'administrateur est connecté, sinon rediriger vers la page de connexion
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

// Inclure le fichier de connexion à la base de données
include('admin_database.php');

// Vérifier si l'ID de l'événement est présent dans la requête
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    // Préparer une déclaration de suppression
    $sql = "DELETE FROM evenements WHERE id_evenements = ?";

    if ($stmt = $mysqli->prepare($sql)) {
        // Liaison des variables à la déclaration préparée
        $stmt->bind_param("i", $param_id);

        // Définir les paramètres
        $param_id = trim($_GET["id"]);

        // Exécuter la déclaration préparée
        if ($stmt->execute()) {
            // Rediriger vers la page des événements après la suppression
            header("Location: Evenement.php");
            exit();
        } else {
            echo "Une erreur s'est produite. Veuillez réessayer plus tard.";
        }

        // Fermer la déclaration préparée
        $stmt->close();
    }

    // Fermer la lien vers la base de données
    $mysqli->close();
} else {
    // L'ID de l'événement n'est pas présent dans la requête, rediriger vers la page des événements
    header("Location: Evenement.php");
    exit();
}
?>
