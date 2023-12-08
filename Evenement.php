<?php
session_start();

// Vérifier si l'administrateur est connecté, sinon rediriger vers la page de connexion
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

// Inclure le fichier de connexion à la base de données
include('admin_database.php');

// Récupérer les événements depuis la base de données
$query = "SELECT id_evenements, titre, type, date, heure, image, details FROM evenements";
$result = $mysqli->query($query);

// Vérifier s'il y a des résultats
if ($result->num_rows > 0) {
    $evenements = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $evenements = [];
}

// Fonction pour afficher un message d'erreur
function showError($message) {
    echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
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
    <style>
        .btn-success {
    --bs-btn-padding-x: 0.75rem;
    --bs-btn-padding-y: 0.375rem;
    --bs-btn-font-family: ;
    --bs-btn-font-size: 1rem;
    --bs-btn-font-weight: 400;
    --bs-btn-line-height: 1.5;
    --bs-btn-color: #ffffff;
    --bs-btn-hover-color: white;
    --bs-btn-bg: #ffa419;
    --bs-btn-hover-bg: gray;
    --bs-btn-border-width: 2px;
    --bs-btn-border-color: black;
    --bs-btn-border-radius: 10px;
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
    color: var(--bs-btn-color);
    text-align: center;
    text-decoration: none;
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

.btn-primary {
  color: #fff;
  background-color: chocolate;
  margin-left: 0px;
  margin-bottom: 5px;
  width: 100px;
  --bs-btn-hover-bg: gray;
  --bs-btn-hover-color: white;
  --bs-btn-active-color: white;
  --bs-btn-active-bg: gray;
}

.table-responsive {
    border: 2px solid black;
    border-radius: 15px;
    margin-bottom: 15px;
    padding-left: 15px;
    margin-right: 60px;
}
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0" style="background: #ff5200;">
            <div class="container-fluid d-flex flex-column p-0">
            <a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="Accueil.php">
                    <div class="sidebar-brand-icon rotate-n-15" bis_skin_checked="1"><img src="assets\img\Logo.png" alt="Logo" style="color: rgb(0,0,0); width: 85px; height: 70px;"></div>
                    <div class="sidebar-brand-text mx-3" bis_skin_checked="1"><span>SUPERCAR</span></div>
                </a>

            <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar" style="margin-top: 19px;">
                <li class="nav-item">
                <a class="nav-link active" href="Accueil.php"><i class="fas fa-home" style="color: var(--bs-accordion-bg);border-color: var(--bs-accordion-bg);"></i><span>Accueil</span></a>
                    </li>


                    <li class="nav-item"><a class="nav-link" href="Voiture.php"><i class="fas fa-car-side" style="color: var(--bs-accordion-bg);"></i><span>Voitures</span></a><a class="nav-link" href="Clients.php"><i class="fas fa-hands-helping" style="color: var(--bs-accordion-bg);"></i><span>Clients</span></a><a class="nav-link" href="Demandes%20d_essai.php"><i class="far fa-comments" style="color: var(--bs-accordion-bg);"></i><span>Demandes d'essai</span></a><a class="nav-link active" href="Evenement.php"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-calendar-event-fill" style="color: var(--bs-accordion-bg);">
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
                <h3 class="text-dark mb-0" style="margin-left: 43px;">Évènements</h3>
                </div>

                <a href="AjouterEvenement.php" class="btn btn-success" style="margin-bottom: 20px;margin-left: 42px;">Ajouter un nouvel événement</a>
                    
                <?php if (!empty($errorMessage)) {
                            showError($errorMessage);
                        } ?>
                        <div class="table-responsive" style="margin-left: 42px;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>id_evenements</th>
                                    <th>Titre</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Heure</th>
                                    <th>Détails</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($evenements as $evenement) { ?>
                                    <tr>
                                        <td><?php echo $evenement['id_evenements']; ?></td>
                                        <td><?php echo $evenement['titre']; ?></td>
                                        <td><?php echo $evenement['type']; ?></td>
                                        <td><?php echo $evenement['date']; ?></td>
                                        <td><?php echo $evenement['heure']; ?></td>
                                        <td><?php echo (strlen($evenement['details']) > 80) ? substr($evenement['details'], 0, 80) . '...' : $evenement['details']; ?></td>
                                        <td><img src="../assets/img/<?php echo $evenement['image']; ?>" alt="Image de l'événement" style="max-width: 100px;"></td>
                                        <td>
                                            <a href="ModifierEvenement.php?id=<?php echo $evenement['id_evenements']; ?>" class="btn btn-primary">Modifier</a>
                                            <a href="javascript:void(0);" onclick="confirmerSuppression(<?php echo $evenement['id_evenements']; ?>);" class="btn btn-danger">Supprimer</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        </div>
                

            </div>
        </div>
    </div>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/Table-With-Search.js"></script>
<script src="assets/js/theme.js"></script>
</body>

</html>
