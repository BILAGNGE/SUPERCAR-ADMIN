<?php
session_start();
require_once 'admin_database.php';

if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM evenement";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $evenements = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $evenements = array();
}

// Gérer les actions (Mettre à jour et Supprimer)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'mettre_a_jour') {
            // Récupérer les données du formulaire de mise à jour
            $id_evenement = $_POST['id_evenement'];
            $titre = $_POST['titre'];
            $details_evenement = $_POST['details_evenement'];

            // Exécuter la requête pour mettre à jour un événement
            $sql = "UPDATE evenement SET titre = '$titre', details_evenement = '$details_evenement' WHERE id_evenement = $id_evenement";
            $result = $mysqli->query($sql);

            if ($result) {
                // Rediriger après la mise à jour
                header("Location: Evenements.php");
            } else {
                echo "Erreur lors de la mise à jour de l'événement.";
            }
        } elseif ($_POST['action'] === 'supprimer') {
            // Récupérer l'ID de l'événement à supprimer
            $id_evenement = $_POST['id_evenement'];

            // Afficher un message de confirmation
            echo "
            <script>
                var confirmation = confirm('Voulez-vous vraiment supprimer cet événement ?');
                if (confirmation) {
                    window.location.href = 'supprimer_evenement.php?id_evenement=$id_evenement';
                }
            </script>
            ";
        }
    }
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
                    
                <div class="container-fluid">
                    <!-- Bouton "Ajouter un événement" -->
                    <a href="ajouter_evenement.php" class="btn btn-primary">Ajouter un événement</a>
                    <br><br>
                    <!-- Tableau d'administration des événements -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Détails</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($evenements as $evenement) {
                                echo "<tr>";
                                echo "<td>{$evenement['titre']}</td>";
                                echo "<td>{$evenement['details_evenement']}</td>";
                                echo "<td>
                                        <a href='modifier_evenement.php?id_evenement={$evenement['id_evenement']}' class='btn btn-warning'>Modifier</a>
                                    </td>";
                                echo "<td>
                                        <a href='supprimer_evenement.php?id_evenement={$evenement['id_evenement']}' class='btn btn-danger' onclick=\"return confirm('Voulez-vous vraiment supprimer cet événement ?');\">Supprimer</a>
                                    </td>";
                                echo "</tr>";
                            }
                            ?>
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
