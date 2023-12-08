<?php
session_start();
require_once 'admin_database.php'; // Inclure le fichier de connexion à la base de données

// Vérifier si l'administrateur est connecté, sinon rediriger vers la page de connexion
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

// Requête pour récupérer les données de contact avec le nom du client correspondant
$sql = "SELECT * FROM contact
        LEFT JOIN client ON contact.id_client = client.id";
$result = $mysqli->query($sql);

// Vérifier s'il y a des résultats
if ($result->num_rows > 0) {
    $contacts = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $contacts = array(); // Aucun élément de contact trouvé
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Contact</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/Bold-BS4-Image-Caption-Hover-Effect-5.css">
    <link rel="stylesheet" href="assets/css/Clients-UI.css">
    <link rel="stylesheet" href="assets/css/Table-With-Search-search-table.css">
    <link rel="stylesheet" href="assets/css/Table-With-Search.css">
    <script>
    function confirmerSuppression(id) {
        if (confirm("Êtes-vous sûr de vouloir supprimer ce message ?")) {
            // Si l'utilisateur confirme, redirigez vers la page de suppression
            window.location.href = "SupprimerContact.php?id=" + id;
        }
    }
    </script>
    <style>
        .card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: initial;
    border: 0 solid transparent;
    border-radius: 15px;
}

.btn-danger {
    --bs-btn-color: #fff;
    --bs-btn-bg: #e74a3b;
    --bs-btn-border-color: #e74a3b;
    --bs-btn-hover-color: #fff;
    --bs-btn-hover-bg: #c43f32;
    --bs-btn-hover-border-color: #b93b2f;
    --bs-btn-focus-shadow-rgb: 235, 101, 88;
    --bs-btn-active-color: #fff;
    --bs-btn-active-bg: #b93b2f;
    --bs-btn-active-border-color: #ad382c;
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    --bs-btn-disabled-color: #fff;
    --bs-btn-disabled-bg: #e74a3b;
    --bs-btn-disabled-border-color: #e74a3b;
}

    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0" style="background: #ff5200;">
            <div class="container-fluid d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="Accueil.php">
                    <div class="sidebar-brand-icon rotate-n-15" bis_skin_checked="1"><img src="assets\img\Logo.png" alt="Logo" style="color: rgb(0,0,0); width: 85px; height: 70px;"></div>
                    <div class="sidebar-brand-text mx-3" bis_skin_checked="1"><span>SUPERCAR</span></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar" style="margin-top: 19px;">
                    <li class="nav-item"><a class="nav-link" href="Accueil.php"><i class="fas fa-home" style="color: var(--bs-accordion-bg);border-color: var(--bs-accordion-bg);"></i><span>Accueil</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="Voiture.php"><i class="fas fa-car-side" style="color: var(--bs-accordion-bg);"></i><span>Voitures</span></a><a class="nav-link" href="Clients.php"><i class="fas fa-hands-helping" style="color: var(--bs-accordion-bg);"></i><span>Clients</span></a><a class="nav-link" href="Demandes%20d_essai.php"><i class="far fa-comments" style="color: var(--bs-accordion-bg);"></i><span>Demandes d'essai</span></a><a class="nav-link" href="Evenement.php"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-calendar-event-fill" style="color: var(--bs-accordion-bg);">
                                <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4V.5zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2zm-3.5-7h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5z"></path>
                            </svg><span>&nbsp;Evènements</span></a><a class="nav-link active" href="Contact.php"><i class="fas fa-file-contract" style="color: var(--bs-accordion-bg);"></i><span>Contact</span></a><a class="nav-link" href="Profil.php"><i class="fas fa-user" style="color: var(--bs-accordion-bg);"></i><span>Profil</span></a></li>
                    <li class="nav-item"></li>
                    <li class="nav-item"></li>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button" style="border-width: 0px;border-color: rgb(0,0,0);"></button></div>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <div class="d-sm-flex justify-content-between align-items-center mb-4" style="margin-bottom: 25px;margin-top: 30px;">
                    <h3 class="text-dark mb-0" style="margin-left: 30px;">Contacts</h3>
                </div>
                <div class="container-fluid">
                    <!-- Tableau pour afficher les éléments de contact -->
                    <div class="card shadow">
                        <div class="card-header py-3" style="border-style: solid;border-width: 2px;border-radius: 15px;border-color: black;" >
                        <div class="card-body">
                            <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                                <table class="table dataTable my-0" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Nom du Client</th>
                                            <th>Prénom</th>
                                            <th>Téléphone</th>
                                            <th>E-mail</th>
                                            <th>Message</th>
                                            <th>Date et heure</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($contacts as $contact) : ?>
                                            <tr>
                                                <td><?php echo $contact['id']; ?></td>    
                                                <td><?php echo $contact['name']; ?></td>
                                                <td><?php echo $contact['prenom']; ?></td>
                                                <td><?php echo $contact['telephone']; ?></td>
                                                <td><?php echo $contact['email']; ?></td>
                                                <td><?php echo $contact['message']; ?></td>
                                                <td><?php echo $contact['date_created']; ?></td>
                                                <td>
                                                    <a href="javascript:void(0);" onclick="confirmerSuppression(<?php echo $contact['id_client']; ?>);" class="btn btn-danger">Supprimer</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/Table-With-Search.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>
