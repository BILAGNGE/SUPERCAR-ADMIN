<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

// Inclure le fichier de connexion à la base de données
include('admin_database.php');

// Initialiser les variables
$id_voiture = $marque = $modele = $type = $annee = $description = $prix = $rating = "";
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $id_voiture = $_POST['id_voiture'];
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $type = $_POST['type'];
    $annee = $_POST['annee'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $rating = $_POST['rating'];

    // Gestion de l'upload d'image
    $targetDirectory = "../assets/img/VOITURE/";

    // Vérifier et traiter les images
    for ($i = 1; $i <= 4; $i++) {
        $fieldName = "image" . $i;

        if (!empty($_FILES[$fieldName]['name'])) {
            $targetFile = $targetDirectory . basename($_FILES[$fieldName]['name']);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Vérifier le type de fichier (par exemple, autoriser uniquement les images)
            $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
            if (!in_array($imageFileType, $allowedTypes)) {
                $errorMessage = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
                break;
            }

            // Gérer la taille du fichier (par exemple, limite à 5 Mo)
            if ($_FILES[$fieldName]['size'] > 5 * 1024 * 1024) {
                $errorMessage = "La taille du fichier est trop grande. Limite de 5 Mo.";
                break;
            }

            // Renommer le fichier pour éviter les doublons
            $fileName = uniqid() . "." . $imageFileType;
            $targetFile = $targetDirectory . $fileName;

            if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $targetFile)) {
                // Mettre à jour le chemin de l'image dans la base de données
                $updateImageQuery = "UPDATE voiture SET $fieldName = '$fileName' WHERE id_voiture = $id_voiture";
                if (!$mysqli->query($updateImageQuery)) {
                    $errorMessage = "Erreur lors de la mise à jour de l'image.";
                    break;
                }
            } else {
                $errorMessage = "Une erreur s'est produite lors du téléchargement de l'image.";
                break;
            }
        }
    }

    // Mettre à jour les détails de la voiture dans la base de données
    $query = "UPDATE voiture SET marque = '$marque', modele = '$modele', type = '$type', annee = '$annee', description = '$description', prix = '$prix', rating = '$rating' WHERE id_voiture = $id_voiture";

    if ($mysqli->query($query)) {
        header("Location: Voitures.php");
        exit();
    } else {
        $errorMessage = "Erreur lors de la mise à jour de la voiture : " . $mysqli->error;
    }
} else {
    // Rediriger si la méthode de requête n'est pas POST
    header("Location: Voitures.php");
    exit();
}
?>
