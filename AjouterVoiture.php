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

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Ajouter une voiture</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/Bold-BS4-Image-Caption-Hover-Effect-5.css">
    <link rel="stylesheet" href="assets/css/Clients-UI.css">
    <link rel="stylesheet" href="assets/css/Table-With-Search-search-table.css">
    <link rel="stylesheet" href="assets/css/Table-With-Search.css">
    <style>
        .btn {
    --bs-btn-padding-x: 0.75rem;
    --bs-btn-padding-y: 0.375rem;
    --bs-btn-font-family: #ff5200;
    --bs-btn-hover-bg: #b9bbba;
    --bs-btn-font-size: 1rem;
    --bs-btn-active-border-color: black;
      margin-top: 15px;
    --bs-btn-font-weight: 400;
    --bs-btn-active-bg: #a4a4a4;
    --bs-btn-line-height: 1.5;
    --bs-btn-color: #858796;
    --bs-btn-bg: #ffa419;
    --bs-btn-border-width: 2px;
    --bs-btn-border-color: black;
    --bs-btn-border-radius: 0.35rem;
    --bs-btn-hover-border-color: black;
    --bs-btn-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
    --bs-btn-disabled-opacity: 0.65;
    --bs-btn-focus-box-shadow: 0 0 0 0.25rem rgba(var(--bs-btn-focus-shadow-rgb), .5);
    display: inline-block;
    padding: var(--bs-btn-padding-y) var(--bs-btn-padding-x);
    font-family: var(--bs-btn-font-family);
    font-size: var(--bs-btn-font-size);
    font-weight: var(--bs-btn-font-weight);
    line-height: var(--bs-btn-line-height);
    color: #ffffff;
    text-align: center;
    text-decoration: none;
    margin-right: 50px;
    vertical-align: middle;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border: var(--bs-btn-border-width) solid var(--bs-btn-border-color);
    border-radius: var(--bs-btn-border-radius);
    background-color: var(--bs-btn-bg);
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}

.btn-secondary {
    --bs-btn-color: #fff;
    --bs-btn-bg: #ef1b1b;
    --bs-btn-border-color: #000000;
    --bs-btn-hover-color: #fff;
    --bs-btn-hover-bg: #717380;
    --bs-btn-hover-border-color: #000000;
    --bs-btn-focus-shadow-rgb: 151, 153, 166;
    --bs-btn-active-color: #fff;
    --bs-btn-active-bg: #6a6c78;
    --bs-btn-active-border-color: #000000;
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    --bs-btn-disabled-color: #fff;
    --bs-btn-disabled-bg: #858796;
    --bs-btn-disabled-border-color: #000000;
}

.div-personnalise {
  border: 2px solid black;
  border-radius: 5px;
}

.mb-3 {
    margin-bottom: 1rem !important;
    margin-left: 20px;
    margin-right: 20px;
    margin-top: 5px;
}

    </style>
</head>

<body>
    <nav class="fixed-top bg-white">
        <!-- Mettez ici votre barre de navigation -->
    </nav>

    <div id="wrapper" class="d-flex">
        <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0" style="background: #ff5200;">
            <div class="container-fluid d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="Accueil.php">
                    <div class="sidebar-brand-icon rotate-n-15" bis_skin_checked="1"><img src="assets\img\Logo.png" alt="Logo" style="color: rgb(0,0,0); width: 85px; height: 70px;"></div>
                    <div class="sidebar-brand-text mx-3" bis_skin_checked="1"><span>SUPERCAR</span></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar" style="margin-top: 19px;">
                    <li class="nav-item"><a class="nav-link" href="Accueil.php"><i class="fas fa-home" style="color: var(--bs-accordion-bg);border-color: var(--bs-accordion-bg);"></i><span>Tableau de bord</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="Voiture.php"><i class="fas fa-car-side" style="color: var(--bs-accordion-bg);"></i><span>Voitures</span></a><a class="nav-link" href="Clients.php"><i class="fas fa-hands-helping" style="color: var(--bs-accordion-bg);"></i><span>Clients</span></a><a class="nav-link" href="Demandes%20d_essai.php"><i class="far fa-comments" style="color: var(--bs-accordion-bg);"></i><span>Demandes d'essai</span></a><a class="nav-link" href="Evenement.php"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-calendar-event-fill" style="color: var(--bs-accordion-bg);">
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
                    <h3 class="text-dark mb-1" style="margin-top: 20px; margin-left: 24px;">Ajouter une voiture</h3>

                    <div class="container" style="margin-top: 20px;">
                        <!-- Afficher un message d'erreur s'il y en a un -->
                        <?php if (!empty($errorMessage)) {
                            showError($errorMessage);
                        } ?>

                        <form method="POST" enctype="multipart/form-data">
                        <div class="div-personnalise">
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
                    </div>
                            <button type="submit" class="btn btn-primary">Ajouter </button>
                            <a href="Voitures.php" class="btn btn-secondary">Annuler</a>
                        </form>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
</body>

</html>
