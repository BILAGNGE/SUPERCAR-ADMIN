<?php
session_start();

// Vérifier si l'administrateur est connecté, sinon rediriger vers la page de connexion
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

// Inclure le fichier de connexion à la base de données
include('admin_database.php');

// Récupérer les voitures depuis la base de données
$query = "SELECT id_voiture, image1, image2, image3, image4, id_voiture, marque, type, modele, annee, description, prix, rating FROM voiture";
$result = $mysqli->query($query);

// Vérifier s'il y a des résultats
if ($result->num_rows > 0) {
    $voitures = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $voitures = [];
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
    <title>Liste des voitures</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/Bold-BS4-Image-Caption-Hover-Effect-5.css">
    <link rel="stylesheet" href="assets/css/Clients-UI.css">
    <link rel="stylesheet" href="assets/css/Table-With-Search-search-table.css">
    <link rel="stylesheet" href="assets/css/Table-With-Search.css">
    <script>
    function confirmerSuppression(idVoiture) {
        if (confirm("Êtes-vous sûr de vouloir supprimer cette voiture ?")) {
            // Si l'utilisateur confirme, redirigez vers la page de suppression
            window.location.href = "SupprimerVoiture.php?id=" + idVoiture;
        }
    }
    </script>
    <style>
        .btn-success {
    --bs-btn-color: #000;
    --bs-btn-bg: #ffa419;
    --bs-btn-border-color: #000000;
    --bs-btn-hover-color: #000;
    --bs-btn-hover-bg: #b8c2bf;
    --bs-btn-hover-border-color: #000000;
    border-width: 2px;
    --bs-btn-focus-shadow-rgb: 24, 170, 117;
    --bs-btn-active-color: #000;
    --bs-btn-active-bg: #a4a4a4;
    --bs-btn-active-border-color: #000000;
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    --bs-btn-disabled-color: #000;
    --bs-btn-disabled-bg: #1cc88a;
    --bs-btn-disabled-border-color: #1cc88a;
}

.btn {
    --bs-btn-padding-x: 0.75rem;
    --bs-btn-padding-y: 0.375rem;
    --bs-btn-font-family: ;
    --bs-btn-font-size: 1rem;
    --bs-btn-font-weight: 400;
    --bs-btn-line-height: 1.5;
      margin-left: 24px;
    --bs-btn-color: #ffffff;
    --bs-btn-bg: #ffa419;
    --bs-btn-hover-bg: #838683;
    --bs-btn-hover-color: white;
    --bs-btn-active-bg: #838683;
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

.btn-primary {
    color: #fff;
    --bs-btn-border-color: none;
    --bs-btn-border-radius: 5px;
    width: 102px;
    margin-bottom: 10px;
    --bs-btn-hover-bg: gray;
    --bs-btn-active-bg: gray;
    --bs-btn-hover-border-color: black;
}

.table {
    border: 2px solid black; /* Ajoute une bordure solide noire de 2 pixels */
    border-radius: 10px; /* Ajoute un rayon de bordure de 5 pixels */
    border-collapse: separate;
  }
    </style>

</head>

<body>

    <div id="wrapper" class="d-flex">
        <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0" style="background: #ff5200;">
            <div class="container-fluid d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                    <div class="sidebar-brand-icon rotate-n-15"><img src="assets\img\Logo.png" alt="Logo" style="color: rgb(0,0,0); width: 85px; height: 70px;">

                    </div>
                    <div class="sidebar-brand-text mx-3"><span>SUPERCAR</span></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar" style="margin-top: 19px;">
                    <li class="nav-item"><a class="nav-link" href="Accueil.php"><i class="fas fa-home" style="color: var(--bs-accordion-bg);border-color: var(--bs-accordion-bg);"></i><span>Tableau de bord</span></a></li>
                    <li class="nav-item"><a class="nav-link active" href="Voiture.php"><i class="fas fa-car-side" style="color: var(--bs-accordion-bg);"></i><span>Voitures</span></a><a class="nav-link" href="Clients.php"><i class="fas fa-hands-helping" style="color: var(--bs-accordion-bg);"></i><span>Clients</span></a><a class="nav-link" href="Demandes%20d_essai.php"><i class="far fa-comments" style="color: var(--bs-accordion-bg);"></i><span>Demandes d'essai</span></a><a class="nav-link" href="Evenement.php"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-calendar-event-fill" style="color: var(--bs-accordion-bg);">
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
                <div class="container-fluid">
                    <h3 class="text-dark mb-1" style="margin-top: 20px; margin-left: 26px;">Voitures</h3>

                    <br>
                    <!-- Ajoutez le bouton "Ajouter une nouvelle voiture" ici -->
                    <a href="AjouterVoiture.php" class="btn btn-success" style="margin-bottom: 20px;">Ajouter une nouvelle voiture</a>

                    <div class="container" style="margin-top: 10px;">

                        <!-- Afficher un message d'erreur s'il y en a un -->
                        <?php if (!empty($errorMessage)) {
                            showError($errorMessage);
                        } ?>
                        <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>id_voiture</th>
                                    <th>Image1</th>
                                    <th>Image2</th>
                                    <th>Image3</th>
                                    <th>Image4</th>
                                    <th>Marque</th>
                                    <th>Type</th>
                                    <th>Modèle</th>
                                    <th>Année</th>
                                    <th>Description</th>
                                    <th>Prix</th>
                                    <th>Rating</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($voitures as $voiture) { ?>
                                    <tr>
                                        <td><?php echo $voiture['id_voiture']; ?></td>
                                        <td><img src="../assets/img/VOITURE/<?php echo $voiture['image1']; ?>" alt="Image de la voiture" style="max-width: 100px;"></td>
                                        <td><img src="../assets/img/VOITURE/<?php echo $voiture['image2']; ?>" alt="Image 2 de la voiture" style="max-width: 100px;"></td>
                                        <td><img src="../assets/img/VOITURE/<?php echo $voiture['image3']; ?>" alt="Image 3 de la voiture" style="max-width: 100px;"></td>
                                        <td><img src="../assets/img/VOITURE/<?php echo $voiture['image4']; ?>" alt="Image 4 de la voiture" style="max-width: 100px;"></td>
                                        <td><?php echo $voiture['marque']; ?></td>
                                        <td><?php echo $voiture['type']; ?></td>
                                        <td><?php echo $voiture['modele']; ?></td>
                                        <td><?php echo $voiture['annee']; ?></td>
                                        <td><?php echo (strlen($voiture['description']) > 100) ? substr($voiture['description'], 0, 100) . '...' : $voiture['description']; ?></td>
                                        <td><?php echo $voiture['prix']; ?></td>
                                        <td><?php echo $voiture['rating']; ?></td>
                                        <td>
                                            <a href="ModifierVoiture.php?id=<?php echo $voiture['id_voiture']; ?>" class="btn btn-primary">Modifier</a>
                                            <a href="javascript:void(0);" onclick="confirmerSuppression(<?php echo $voiture['id_voiture']; ?>);" class="btn btn-danger">Supprimer</a>
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
