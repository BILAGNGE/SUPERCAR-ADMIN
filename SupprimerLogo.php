<?php
session_start();
require_once 'admin_database.php'; // Inclure le fichier de connexion à la base de données

// Vérifier si l'administrateur est connecté, sinon rediriger vers la page de connexion
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

// Vérifier si l'identifiant de la marque à supprimer est passé dans l'URL
if (isset($_GET["id"])) {
    $id_marque = $_GET["id"];

    // Récupérer le nom du logo associé à cette marque
    $sql = "SELECT logo FROM marque WHERE id_marque = $id_marque";
    $result = $mysqli->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $logo = $row["logo"];

        // Supprimer le logo du dossier de logos s'il existe
        if (!empty($logo) && file_exists("../assets/img/LOGO/" . $logo)) {
            unlink("../assets/img/LOGO/" . $logo);
        }

        // Supprimer la marque de la base de données
        $sql = "DELETE FROM marque WHERE id_marque = $id_marque";
        if ($mysqli->query($sql) === true) {
            // Rediriger vers la page de liste des logos après la suppression
            header("Location: Logo.php");
            exit();
        } else {
            $errorMessage = "Erreur lors de la suppression de la marque.";
        }
    } else {
        $errorMessage = "La marque n'a pas été trouvée.";
    }
} else {
    $errorMessage = "Identifiant de la marque non spécifié.";
}

// Si vous souhaitez afficher des messages d'erreur, vous pouvez le faire ici
// Par exemple :
if (!empty($errorMessage)) {
    echo "Erreur : " . $errorMessage;
}
?>
