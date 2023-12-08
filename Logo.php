<?php
session_start();

// Vérifier si l'administrateur est connecté, sinon rediriger vers la page de connexion
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

// Inclure le fichier de connexion à la base de données
include('admin_database.php');

// Récupérer la liste des logos depuis la base de données
$query = "SELECT * FROM marque";
$result = $mysqli->query($query);

// Vérifier s'il y a des résultats
if ($result->num_rows > 0) {
    $logos = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $logos = [];
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
    <title>Liste des Logos</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/Bold-BS4-Image-Caption-Hover-Effect-5.css">
    <link rel="stylesheet" href="assets/css/Clients-UI.css">
    <link rel="stylesheet" href="assets/css/Table-With-Search-search-table.css">
    <link rel="stylesheet" href="assets/css/Table-With-Search.css">
    <script>
    // Fonction pour confirmer la suppression
    function confirmerSuppression(idMarque, nomMarque) {
        if (confirm("Voulez-vous vraiment supprimer la marque \"" + nomMarque + "\" ?")) {
            // Si l'utilisateur confirme, redirigez vers la page de suppression
            window.location.href = "SupprimerLogo.php?id=" + idMarque;
        }
    }
    </script>
    <style>
        .btn-success {
    --bs-btn-color: #000;
    --bs-btn-bg: #ffa419;
    border-width: 2px;
    --bs-btn-border-color: #000000;
    --bs-btn-hover-color: #000;
    --bs-btn-hover-bg: #b9bbba;
    --bs-btn-hover-border-color: #000000;
    --bs-btn-focus-shadow-rgb: 24, 170, 117;
    --bs-btn-active-color: #000;
    --bs-btn-active-bg: #ffa419;
    --bs-btn-active-border-color: #000000;
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    --bs-btn-disabled-color: #000;
    --bs-btn-disabled-bg: #1cc88a;
    --bs-btn-disabled-border-color: #1cc88a;
}

.btn-danger {
    --bs-btn-color: #fff;
    --bs-btn-bg: #e74a3b;
    --bs-btn-border-color: #e74a3b;
    --bs-btn-hover-color: #fff;
    --bs-btn-hover-bg: #c43f32;
    --bs-btn-hover-border-color: #b93b2f;
    --bs-btn-focus-shadow-rgb: 235, 101, 88;
    margin-left: 0px;
    --bs-btn-active-color: #fff;
    --bs-btn-active-bg: #b93b2f;
    --bs-btn-active-border-color: #ad382c;
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    --bs-btn-disabled-color: #fff;
    --bs-btn-disabled-bg: #e74a3b;
    --bs-btn-disabled-border-color: #e74a3b;
    margin-top: -4px;
    font-size: 16px;

}


.mb-3 {
    margin-bottom: 1rem !important;
    margin-left: 20px;
    margin-right: 20px;
    margin-top: 5px;
}

.div-personnalise {
  border: 2px solid black;
  border-radius: 5px;
  margin-bottom: 10px;
  padding-left: 40px;
  margin-right: 60px;
  overflow-x: auto; /* Ajoute une barre de défilement horizontale si nécessaire */
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


.table {
  width: 100%; /* La table prendra toute la largeur du conteneur */
}

th, td {
  padding: 8px;
  text-align: left;
}

/* Ajoutez des styles spécifiques pour les écrans de petite taille */
@media (max-width: 768px) {
  .table {
    font-size: 14px; /* Ajustez la taille de la police si nécessaire */
  }
}



    </style>
</head>

<body>

    <div id="wrapper" class="d-flex">
        <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0" style="background: #ff5200;">
            <div class="container-fluid d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="Accueil.php">
                    <div class="sidebar-brand-icon rotate-n-15" bis_skin_checked="1"><img src="assets\img\Logo.png" alt="Logo" style="color: rgb(0,0,0); width: 85px; height: 70px;"></div>
                    <div class="sidebar-brand-text mx-3" bis_skin_checked="1"><span>SUPERCAR</span></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar" style="margin-top: 19px;">
                    <li class="nav-item"><a class="nav-link" href="Accueil.php"><i class="fas fa-home" style="color: var(--bs-accordion-bg);border-color: var(--bs-accordion-bg);"></i><span>Acceuil</span></a></li>
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
                    <h3 class="text-dark mb-1" style="margin-top: 20px; margin-left: 27px;">Logos</h3>

                    <br>
                    <!-- Ajoutez le bouton "Ajouter un nouveau logo" ici -->
                    <a href="AjouterLogo.php" class="btn btn-success" style="margin-bottom: 20px; margin-left: 24px;">Ajouter un nouveau logo</a>

                    <div class="container" style="margin-top: 10px;">

                        <!-- Afficher un message d'erreur s'il y en a un -->
                        <?php if (!empty($errorMessage)) {
                            showError($errorMessage);
                        } ?>
                        <div class="div-personnalise">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom de la Marque</th>
                                    <th>Logo</th>
                                    <th>Actions</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($logos as $logo) { ?>
                                    <tr>
                                        <td><?php echo $logo['id_marque']; ?></td>
                                        <td><?php echo $logo['nom_marque']; ?></td>
                                        <td><img src="../assets/img/<?php echo $logo['logo']; ?>" alt="Logo de la marque" style="max-width: 70px;"></td>
                                        <td>
                                            <a href="ModifierLogo.php?id=<?php echo $logo['id_marque']; ?>" class="btn btn-primary">Modifier</a>
                                            <button onclick="confirmerSuppression(<?php echo $logo['id_marque']; ?>, '<?php echo addslashes($logo['nom_marque']); ?>');" class="btn btn-danger">Supprimer</button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
