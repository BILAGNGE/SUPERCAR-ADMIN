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
                    <h3 class="text-dark mb-0" style="margin-left: 30px;">Évènements</h3>
                </div>

                    <a href="AjouterEvenement.php" class="btn btn-success" style="margin-bottom: 20px;">Ajouter un nouvel événement</a>
                    
                <?php if (!empty($errorMessage)) {
                            showError($errorMessage);
                        } ?>
                        <div class="table-responsive">
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
