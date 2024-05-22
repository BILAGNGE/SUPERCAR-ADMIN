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
$marque = $modele = $type = $annee = $description = $prix = $rating = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
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
    $imageNames = array(); // Pour stocker les noms des images téléchargées

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
                $imageNames[] = $fileName; // Ajouter le nom de l'image à notre tableau
            } else {
                $errorMessage = "Une erreur s'est produite lors du téléchargement de l'image.";
                break;
            }
        }
    }

    if (empty($errorMessage)) {
        // Toutes les images ont été téléchargées avec succès, nous pouvons insérer les données dans la base de données
        $image1 = isset($imageNames[0]) ? $imageNames[0] : '';
        $image2 = isset($imageNames[1]) ? $imageNames[1] : '';
        $image3 = isset($imageNames[2]) ? $imageNames[2] : '';
        $image4 = isset($imageNames[3]) ? $imageNames[3] : '';

        // Requête d'insertion dans la base de données
        $query = "INSERT INTO voiture (marque, modele, type, annee, description, prix, rating, image1, image2, image3, image4) VALUES ('$marque', '$modele', '$type', '$annee', '$description', '$prix', '$rating', '$image1', '$image2', '$image3', '$image4')";

        if ($mysqli->query($query)) {
            header("Location: Voitures.php");
            exit();
        } else {
            $errorMessage = "Erreur lors de l'ajout de la voiture : " . $mysqli->error;
        }
    }
}

// Fonction pour afficher un message d'erreur
function showError($message)
{
    echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
}
?>
