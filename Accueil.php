<?php
session_start();

// Vérifier si l'administrateur est connecté, sinon rediriger vers la page de connexion
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/Bold-BS4-Image-Caption-Hover-Effect-5.css">
    <link rel="stylesheet" href="assets/css/Clients-UI.css">
    <link rel="stylesheet" href="assets/css/Table-With-Search-search-table.css">
    <link rel="stylesheet" href="assets/css/Table-With-Search.css">
</head>

<body id="page-top">
    <div id="wrapper">
        <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0" style="background: #021c4f;">
            <div class="container-fluid d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#"><img style="padding-right: 0px;margin-top: 11px;" src="assets/img/logo.jpg" width="100">
                    <div class="sidebar-brand-icon rotate-n-15"></div>
                    <div class="sidebar-brand-text mx-3"></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar" style="margin-top: 19px;">
                    <li class="nav-item">
                        <a class="nav-link active" href="Accueil.php"><i class="fas fa-home" style="color: var(--bs-accordion-bg);border-color: var(--bs-accordion-bg);"></i><span>Tableau de bord</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Voiture.php"><i class="fas fa-car-side" style="color: var(--bs-accordion-bg);"></i><span>Voitures</span></a>
                        <a class="nav-link" href="Clients.php"><i class="fas fa-hands-helping" style="color: var(--bs-accordion-bg);"></i><span>Clients</span></a>
                        <a class="nav-link" href="Demandes%20d_essai.php"><i class="far fa-list-alt" style="color: var(--bs-accordion-bg);"></i><span>Demandes d'essai</span></a>
                        <a class="nav-link" href="Evenement.php"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-calendar-event-fill" style="color: var(--bs-accordion-bg);">
                                <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4V.5zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2zm-3.5-7h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5z"></path>
                            </svg><span>&nbsp;Évènements</span></a>
                        <a class="nav-link" href="Contact.php"><i class="fas fa-file-contract" style="color: var(--bs-accordion-bg);"></i><span>Contact</span></a>
                        <a class="nav-link" href="Profil.php"><i class="fas fa-user" style="color: var(--bs-accordion-bg);"></i><span>Profil</span></a></li>
                    <li class="nav-item"></li>
                    <li class="nav-item"></li>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button" style="border-width: 0px;border-color: rgb(0,0,0);"></button></div>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <div class="container-fluid">
                    <div class="d-sm-flex justify-content-between align-items-center mb-4" style="margin-bottom: 25px;margin-top: 30px;">
                        <h3 class="text-dark mb-0">Tableau de Bord&nbsp; ADMIN</h3>
                    </div>
                </div>
                <div class="container-fluid py-5">
                    <div class="row">
                        <div class="col-md-6 mb-lg-0" style="padding: 10px;">
                            <div class="hover hover-5 text-white rounded"><img src="https://res.cloudinary.com/mhmd/image/upload/v1570786269/hoverSet-10_ccl30n.jpg" alt="image">
                                <div class="hover-overlay" style="background: url(&quot;assets/img/BG-Accueil-2.jpg&quot;);background-size: cover;"></div>
                                <div class="hover-5-content">
                                    <h1 class="text-uppercase hover-5-title fw-light mb-0" style="font-weight: bold;">BIENVENUE&nbsp;</h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-lg-0" style="padding: 10px;">
                            <div class="hover hover-5 text-white rounded"><img src="https://res.cloudinary.com/mhmd/image/upload/v1570786269/hoverSet-10_ccl30n.jpg" alt="image">
                                <div class="hover-overlay" style="background: url(&quot;assets/img/44690324-0.jpeg&quot;);background-size: cover;"></div>
                                <div class="hover-5-content">
                                    <h1 class="text-uppercase text-end d-lg-flex justify-content-lg-center hover-5-title fw-light mb-0" style="font-weight: bold;"><strong class="fs-1 fw-bold text-white">SUPERCAR</strong>&nbsp;</h1>
                                </div>
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
