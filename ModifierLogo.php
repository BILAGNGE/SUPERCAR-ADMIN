<?php
session_start();
require_once 'admin_database.php'; // Inclure le fichier de connexion à la base de données

// Vérifier si l'administrateur est connecté, sinon rediriger vers la page de connexion
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

// Initialiser les variables
$id_marque = "";
$nom_marque = "";
$logo = "";
$errorMessage = "";

// Récupérer l'identifiant de la marque à modifier depuis l'URL
if (isset($_GET["id"])) {
    $id_marque = $_GET["id"];

    // Récupérer les données de la marque à partir de la base de données
    $sql = "SELECT * FROM marque WHERE id_marque = $id_marque";
    $result = $mysqli->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $nom_marque = $row["nom_marque"];
        $logo = $row["logo"];
    } else {
        $errorMessage = "La marque n'a pas été trouvée.";
    }
}

// Traitement du formulaire de modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_marque = $_POST["nom_marque"];

    // Vérifier si un nouveau logo a été téléchargé
    if (isset($_FILES["logo"]) && $_FILES["logo"]["error"] == 0) {
        // Chemin de destination pour télécharger le fichier
        $targetDirectory = "../assets/img/LOGO/";
        $newLogo = basename($_FILES["logo"]["name"]);
        $targetPath = $targetDirectory . $newLogo;

        // Vérifier si le fichier est une image
        $imageFileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
        $allowedExtensions = array("jpg", "jpeg", "png", "gif");
        if (in_array($imageFileType, $allowedExtensions)) {
            // Déplacer le nouveau fichier téléchargé vers le dossier de destination
            if (move_uploaded_file($_FILES["logo"]["tmp_name"], $targetPath)) {
                // Supprimer l'ancien logo s'il existe
                if (!empty($logo) && file_exists($targetDirectory . $logo)) {
                    unlink($targetDirectory . $logo);
                }

                // Mettre à jour les données dans la base de données avec le nouveau logo
                $sql = "UPDATE marque SET nom_marque = '$nom_marque', logo = '$newLogo' WHERE id_marque = $id_marque";
                if ($mysqli->query($sql) === true) {
                    // Rediriger vers la page de liste des logos après la modification
                    header("Location: Logo.php");
                    exit();
                } else {
                    $errorMessage = "Erreur lors de la mise à jour de la marque.";
                }
            } else {
                $errorMessage = "Erreur lors du téléchargement du nouveau logo.";
            }
        } else {
            $errorMessage = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
        }
    } else {
        // Si aucun nouveau logo n'a été téléchargé, mettre à jour seulement le nom de la marque
        $sql = "UPDATE marque SET nom_marque = '$nom_marque' WHERE id_marque = $id_marque";
        if ($mysqli->query($sql) === true) {
            // Rediriger vers la page de liste des logos après la modification
            header("Location: Logo.php");
            exit();
        } else {
            $errorMessage = "Erreur lors de la mise à jour de la marque.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Modifier un Logo</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/Bold-BS4-Image-Caption-Hover-Effect-5.css">
    <link rel="stylesheet" href="assets/css/Clients-UI.css">
    <link rel="stylesheet" href="assets/css/Table-With-Search-search-table.css">
    <link rel="stylesheet" href="assets/css/Table-With-Search.css">
</head>

<body>

    <div id="wrapper" class="d-flex">
        <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0" style="background: #021c4f;">
            <div class="container-fluid d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#"><img style="padding-right: 0px;margin-top: 11px;" src="assets/img/logo.jpg" width="100">
                    <div class="sidebar-brand-icon rotate-n-15"></div>
                    <div class="sidebar-brand-text mx-3"></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar" style="margin-top: 19px;">
                    <li class="nav-item"><a class="nav-link" href="Accueil.php"><i class="fas fa-home" style="color: var(--bs-accordion-bg);border-color: var(--bs-accordion-bg);"></i><span>Tableau de bord</span></a></li>
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
                    <h3 class="text-dark mb-1" style="margin-top: 20px;">Logos</h3>
                    <div class="container">
                        <form method="POST" enctype="multipart/form-data">
                            <div>
                                <label for="nom_marque">Nom de la Marque:</label>
                                <input type="text" name="nom_marque" id="nom_marque" value="<?php echo $nom_marque; ?>" required>
                            </div>
                            <div>
                                <label for="logo">Nouveau Logo de la Marque:</label>
                                <input type="file" name="logo" id="logo" accept="image/*">
                            </div>
                                <br>
                            <div>
                                <button type="submit" class="btn btn-secondary">Modifier</button>
                                <a href="Logo.php" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                        <?php if (!empty($errorMessage)) { ?>
                            <div class="error"><?php echo $errorMessage; ?></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>    

</body>

</html>
