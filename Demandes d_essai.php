<?php
session_start();
require_once 'admin_database.php'; // Inclure le fichier de connexion à la base de données

// Vérifier si l'administrateur est connecté, sinon rediriger vers la page de connexion
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

// Requête pour récupérer les données de toutes les demandes d'essai
$sql = "SELECT demande_essai.id_demande, demande_essai.id_voiture, voiture.marque, voiture.modele, demande_essai.date_debut, demande_essai.heure, demande_essai.id_client, client.name, client.prenom, demande_essai.commentaire, demande_essai.statut FROM demande_essai INNER JOIN voiture ON demande_essai.id_voiture = voiture.id_voiture INNER JOIN client ON demande_essai.id_client = client.id";
$result = $mysqli->query($sql);

// Vérifier s'il y a des résultats
if ($result->num_rows > 0) {
    $demandesEssai = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $demandesEssai = array(); // Aucune demande d'essai trouvée
}

// Traitement des actions (Valider / En cours)
if (isset($_POST['valider_demande'])) {
    $id_demande = $_POST['id_demande'];
    
    // Mettez à jour le statut dans la base de données à "Valider"
    $sql = "UPDATE demande_essai SET statut = 'Validé' WHERE id_demande = $id_demande";
    $mysqli->query($sql);
}

if (isset($_POST['en_cours_demande'])) {
    $id_demande = $_POST['id_demande'];
    
    // Mettez à jour le statut dans la base de données à "En cours"
    $sql = "UPDATE demande_essai SET statut = 'En cours' WHERE id_demande = $id_demande";
    $mysqli->query($sql);
}


// Traitement de la suppression de la demande
if (isset($_POST['supprimer_demande'])) {
    $id_demande = $_POST['id_demande'];
    
    // Ajoutez une requête SQL pour supprimer la demande correspondante de la base de données
    $sql = "DELETE FROM demande_essai WHERE id_demande = $id_demande";
    if ($mysqli->query($sql)) {
        // Rediriger après la suppression vers la même page pour mettre à jour la liste
        header("Location: Demandes%20d_essai.php");
        exit();
    } else {
        // En cas d'erreur
        echo "Erreur lors de la suppression : " . $mysqli->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Demandes d'essai</title>
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
                    <li class="nav-item"><a class="nav-link" href="Accueil.php"><i class="fas fa-home" style="color: var(--bs-accordion-bg);border-color: var(--bs-accordion-bg);"></i><span>Tableau de bord</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="Voiture.php"><i class="fas fa-car-side" style="color: var(--bs-accordion-bg);"></i><span>Voitures</span></a><a class="nav-link" href="Clients.php"><i class="fas fa-hands-helping" style="color: var(--bs-accordion-bg);"></i><span>Clients</span></a><a class="nav-link active" href="Demandes%20d_essai.php"><i class="far fa-list-alt" style="color: var(--bs-accordion-bg);"></i><span>Demandes d'essai</span></a><a class="nav-link" href="Evenement.php"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-calendar-event-fill" style="color: var(--bs-accordion-bg);">
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
                    <h3 class="text-dark mb-0" style="margin-left: 30px;">Demandes d'essai</h3>
                </div>
                <div class="container-fluid">
                    <!-- Tableau pour afficher les Demandes d'essai -->
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID Demande</th>
                                            <th>ID Voiture</th>
                                            <th>Marque</th>
                                            <th>Modèle</th>
                                            <th>Date de début</th>
                                            <th>Heure</th>
                                            <th>ID Client</th>
                                            <th>Nom du Client</th>
                                            <th>Prénom du Client</th>
                                            <th>Commentaire</th>
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($demandesEssai as $demande) : ?>
                                            <tr>
                                                <td><?php echo $demande['id_demande']; ?></td>
                                                <td><?php echo $demande['id_voiture']; ?></td>
                                                <td><?php echo $demande['marque']; ?></td>
                                                <td><?php echo $demande['modele']; ?></td>
                                                <td><?php echo $demande['date_debut']; ?></td>
                                                <td><?php echo $demande['heure']; ?></td>
                                                <td><?php echo $demande['id_client']; ?></td>
                                                <td><?php echo $demande['name']; ?></td>
                                                <td><?php echo $demande['prenom']; ?></td>
                                                <td><?php echo $demande['commentaire']; ?></td>
                                                <td>
                                                    <?php if ($demande['statut'] == 'En cours') : ?>
                                                        <button class="btn btn-warning"><?php echo $demande['statut']; ?></button>
                                                    <?php else : ?>
                                                        <button class="btn btn-success"><?php echo $demande['statut']; ?></button>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <form method="post">
                                                        <input type="hidden" name="id_demande" value="<?php echo $demande['id_demande']; ?>">
                                                        <?php if ($demande['statut'] == 'En cours') : ?>
                                                            <button type="submit" name="valider_demande" class="btn btn-success">Valider</button>
                                                        <?php else : ?>
                                                            <button type="submit" name="en_cours_demande" class="btn btn-warning">En cours</button>
                                                        <?php endif; ?>
                                                    </form>

                                                    <form method="post" onsubmit="return confirm('Voulez-vous vraiment supprimer cette demande ?');">
                                                        <input type="hidden" name="id_demande" value="<?php echo $demande['id_demande']; ?>">
                                                        <button type="submit" name="supprimer_demande" class="btn btn-danger">Supprimer</button>
                                                    </form>
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
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/Table-With-Search.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>
