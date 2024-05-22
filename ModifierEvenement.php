<?php
session_start();

if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

include('admin_database.php');

$id = $_GET["id"];
$titre = $type = $date = $heure = $details = $image = '';
$titre_err = $type_err = $date_err = $heure_err = $details_err = $image_err = '';
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["titre"]))) {
        $titre_err = "Veuillez entrer le titre de l'événement.";
    } else {
        $titre = trim($_POST["titre"]);
    }

    if (empty(trim($_POST["type"]))) {
        $type_err = "Veuillez entrer le type de l'événement.";
    } else {
        $type = trim($_POST["type"]);
    }

    if (empty(trim($_POST["date"]))) {
        $date_err = "Veuillez entrer la date de l'événement.";
    } else {
        $date = trim($_POST["date"]);
    }

    if (empty(trim($_POST["heure"]))) {
        $heure_err = "Veuillez entrer l'heure de l'événement.";
    } else {
        $heure = trim($_POST["heure"]);
    }

    if (empty(trim($_POST["details"]))) {
        $details_err = "Veuillez entrer les détails de l'événement.";
    } else {
        $details = trim($_POST["details"]);
    }

    if (empty($_FILES['image']['name'])) {
        $image = $_POST["imagePath"];
    } else {
        $image = $_FILES['image']['name'];
        $target = "../assets/img/EVENEMENT/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    if (empty($titre_err) && empty($type_err) && empty($date_err) && empty($heure_err) && empty($details_err) && empty($image_err)) {
        $sql = "UPDATE evenements SET titre = ?, type = ?, date = ?, heure = ?, image = ?, details = ? WHERE id_evenements = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("ssssssi", $param_titre, $param_type, $param_date, $param_heure, $param_image, $param_details, $param_id);

            $param_titre = $titre;
            $param_type = $type;
            $param_date = $date;
            $param_heure = $heure;
            $param_image = $image;
            $param_details = $details;
            $param_id = $id;

            if ($stmt->execute()) {
                header("location: Evenement.php");
                exit();
            } else {
                $errorMessage = "Une erreur s'est produite. Veuillez réessayer plus tard.";
            }
        }

        $stmt->close();
    }
    $mysqli->close();
}

$id = $_GET["id"];
$query = "SELECT titre, type, date, heure, image, details FROM evenements WHERE id_evenements = ?";
if ($stmt = $mysqli->prepare($query)) {
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($titre, $type, $date, $heure, $image, $details);
            $stmt->fetch();
        }
    }

    $stmt->close();
}

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Évènements</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/Bold-BS4-Image-Caption-Hover-Effect-5.css">
    <link rel="stylesheet" href="assets/css/Clients-UI.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/Table-With-Search-search-table.css">
    <link rel="stylesheet" href="assets/css/Table-With-Search.css">
    <script>
    function confirmerSuppression(idEvenement) {
        if (confirm("Êtes-vous sûr de vouloir supprimer cet événement ?")) {
            // Si l'utilisateur confirme, redirigez vers la page de suppression
            window.location.href = "SupprimerEvenement.php?id=" + idEvenement;
        }
    }
    </script>
</head>

<body id="page-top">
    <div id="wrapper">
        <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0" style="background: #021c4f;">
            <div class="container-fluid d-flex flex-column p-0">
                <a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#"><img style="padding-right: 0px;margin-top: 11px;" src="assets/img/logo.jpg" width="100">
                    <div class="sidebar-brand-icon rotate-n-15"></div>
                    <div class="sidebar-brand-text mx-3"></div>
                </a>
            <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar" style="margin-top: 19px;">
                    <li class="nav-item"><a class="nav-link" href="Accueil.php"><i class="fas fa-home" style="color: var(--bs-accordion-bg);border-color: var(--bs-accordion-bg);"></i><span>Tableau de bord</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="Voiture.php"><i class="fas fa-car-side" style="color: var(--bs-accordion-bg);"></i><span>Voitures</span></a><a class="nav-link" href="Clients.php"><i class="fas fa-hands-helping" style="color: var(--bs-accordion-bg);"></i><span>Clients</span></a><a class="nav-link" href="Demandes%20d_essai.php"><i class="far fa-list-alt" style="color: var(--bs-accordion-bg);"></i><span>Demandes d'essai</span></a><a class="nav-link active" href="Evenement.php"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-calendar-event-fill" style="color: var(--bs-accordion-bg);">
                        <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4V.5zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2zm-3.5-7h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5z"></path>
                    </svg><span>&nbsp;Evènements</span></a><a class="nav-link" href="Contact.php"><i class="fas fa-file-contract" style="color: var(--bs-accordion-bg);"></i><span>Contact</span></a><a class="nav-link" href="Profil.php"><i class="fas fa-user" style="color: var(--bs-accordion-bg);"></i><span>Profil</span></a></li>
                    <li class="nav-item"></li>
                    <li class="nav-item"></li>
                </ul>
            <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button" style="border-width: 0px;border-color: rgb(0,0,0);"></button></div>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <div class="d-sm-flex justify-content-between align-items-center mb-4" style="margin-bottom: 25px;margin-top: 30px;">
                    <h3 class="text-dark mb-0" style="margin-left: 30px;">Modifier un évènements</h3>
                </div>

                <div class="container">

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="imagePath" value="<?php echo $image; ?>">

                        <div class="form-group <?php echo (!empty($titre_err)) ? 'has-error' : ''; ?>">
                            <label>Titre de l'événement</label>
                            <input type="text" name="titre" class="form-control" value="<?php echo $titre; ?>">
                            <span class="help-block"><?php echo $titre_err; ?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($type_err)) ? 'has-error' : ''; ?>">
                            <label>Type de l'événement</label>
                            <input type="text" name="type" class="form-control" value="<?php echo $type; ?>">
                            <span class="help-block"><?php echo $type_err; ?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($date_err)) ? 'has-error' : ''; ?>">
                            <label>Date de l'événement</label>
                            <input type="date" name="date" class="form-control" value="<?php echo $date; ?>">
                            <span class="help-block"><?php echo $date_err; ?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($heure_err)) ? 'has-error' : ''; ?>">
                            <label>Heure de l'événement</label>
                            <input type="time" name="heure" class="form-control" value="<?php echo $heure; ?>">
                            <span class="help-block"><?php echo $heure_err; ?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($details_err)) ? 'has-error' : ''; ?>">
                            <label>Détails de l'événement</label>
                            <textarea name="details" class="form-control"><?php echo $details; ?></textarea>
                            <span class="help-block"><?php echo $details_err; ?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($image_err)) ? 'has-error' : ''; ?>">
                            <label>Image de l'événement</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <span class="help-block"><?php echo $image_err; ?></span>
                        </div>
                        <br>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Enregistrer">
                            <a class="btn btn-secondary" href="Evenement.php">Annuler</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/Table-With-Search.js"></script>
<script src="assets/js/theme.js"></script>
</body>

</html>
