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
    // Utilisez une requête préparée pour mettre à jour la description en toute sécurité
    $query = "UPDATE voiture SET marque = ?, modele = ?, type = ?, annee = ?, description = ?, prix = ?, rating = ? WHERE id_voiture = ?";
    $stmt = $mysqli->prepare($query);

    // Liez les paramètres et leurs valeurs
    $stmt->bind_param("sssssssi", $marque, $modele, $type, $annee, $description, $prix, $rating, $id_voiture);

    // Exécutez la requête préparée
    if ($stmt->execute()) {
        header("Location: Voitures.php");
        exit();
    } else {
        $errorMessage = "Erreur lors de la mise à jour de la voiture : " . $stmt->error;
    }


    if ($mysqli->query($query)) {
        header("Location: Voitures.php");
        exit();
    } else {
        $errorMessage = "Erreur lors de la mise à jour de la voiture : " . $mysqli->error;
    }
} else {
    // Récupérer l'ID de la voiture depuis l'URL
    if (isset($_GET['id'])) {
        $id_voiture = $_GET['id'];

        // Récupérer les détails de la voiture depuis la base de données
        $query = "SELECT * FROM voiture WHERE id_voiture = $id_voiture";
        $result = $mysqli->query($query);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $marque = $row['marque'];
            $modele = $row['modele'];
            $type = $row['type'];
            $annee = $row['annee'];
            $description = $row['description'];
            $prix = $row['prix'];
            $rating = $row['rating'];
        } else {
            $errorMessage = "Voiture non trouvée.";
        }
    } else {
        $errorMessage = "ID de voiture non spécifié.";
    }
}

// Fonction pour afficher un message d'erreur
function showError($message)
{
    echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Modifier la voiture</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/Bold-BS4-Image-Caption-Hover-Effect-5.css">
    <link rel="stylesheet" href="assets/css/Clients-UI.css">
    <link rel="stylesheet" href="assets/css/Table-With-Search-search-table.css">
    <link rel="stylesheet" href="assets/css/Table-With-Search.css">
</head>

<body>
    <nav class="fixed-top bg-white">
        <!-- Mettez ici votre barre de navigation -->
    </nav>

    <div id="wrapper" class="d-flex">
        <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0" style="background: #021c4f;">
            <div class="container-fluid d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#"><img style="padding-right: 0px;margin-top: 11px;" src="assets/img/logo.jpg" width="100">
                    <div class="sidebar-brand-icon rotate-n-15"></div>
                    <div class="sidebar-brand-text mx-3"></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar" style="margin-top: 19px;">
                    <li class="nav-item"><a class="nav-link" href="Acceuil.php"><i class="fas fa-home" style="color: var(--bs-accordion-bg);border-color: var(--bs-accordion-bg);"></i><span>Tableau de bord</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="Voiture.php"><i class="fas fa-car-side" style="color: var(--bs-accordion-bg);"></i><span>Voitures</span></a><a class="nav-link" href="Clients.php"><i class="fas fa-hands-helping" style="color: var(--bs-accordion-bg);"></i><span>Clients</span></a><a class="nav-link" href="Demandes%20d_essai.php"><i class="far fa-list-alt" style="color: var(--bs-accordion-bg);"></i><span>Demandes d'essai</span></a><a class="nav-link" href="Evenement.php"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-calendar-event-fill" style="color: var(--bs-accordion-bg);">
                                <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4V.5zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2zm-3.5-7h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5z"></path>
                            </svg><span>&nbsp;Evènements</span></a><a class="nav-link" href="Contact.php"><i class="fas fa-file-contract" style="color: var(--bs-accordion-bg);"></i><span>Contact</span></a><a class="nav-link" href="Profil.php"><i class="fas fa-user" style="color: var(--bs-accordion-bg);"></i><span>Profile</span></a></li>
                    <li class="nav-item"></li>
                    <li class="nav-item"></li>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button" style="border-width: 0px;border-color: rgb(0,0,0);"></button></div>
            </div>
        </nav>

        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <div class="container-fluid">
                    <h3 class="text-dark mb-1" style="margin-top: 20px;">Modifier la voiture</h3>

                    <div class="container" style="margin-top: 20px;">
                        <!-- Afficher un message d'erreur s'il y en a un -->
                        <?php if (!empty($errorMessage)) {
                            showError($errorMessage);
                        } ?>

                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id_voiture" value="<?php echo $id_voiture; ?>">
                            <div class="mb-3">
                                <label for="marque" class="form-label">Marque</label>
                                <input type="text" class="form-control" id="marque" name="marque" value="<?php echo $marque; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="modele" class="form-label">Modèle</label>
                                <input type="text" class="form-control" id="modele" name="modele" value="<?php echo $modele; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <input type="text" class="form-control" id="type" name="type" value="<?php echo $type; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="annee" class="form-label">Année</label>
                                <input type="text" class="form-control" id="annee" name="annee" value="<?php echo $annee; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo $description; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="prix" class="form-label">Prix</label>
                                <input type="text" class="form-control" id="prix" name="prix" value="<?php echo $prix; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="rating" class="form-label">Rating</label>
                                <input type="number" class="form-control" id="rating" name="rating" value="<?php echo $rating; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="image1" class="form-label">Image 1</label>
                                <input type="file" class="form-control" id="image1" name="image1">
                            </div>
                            <div class="mb-3">
                                <label for="image2" class="form-label">Image 2</label>
                                <input type="file" class="form-control" id="image2" name="image2">
                            </div>
                            <div class="mb-3">
                                <label for="image3" class="form-label">Image 3</label>
                                <input type="file" class="form-control" id="image3" name="image3">
                            </div>
                            <div class="mb-3">
                                <label for="image4" class="form-label">Image 4</label>
                                <input type="file" class="form-control" id="image4" name="image4">
                            </div>
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                            <a href="Voitures.php" class="btn btn-secondary">Annuler</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
</body>

</html>