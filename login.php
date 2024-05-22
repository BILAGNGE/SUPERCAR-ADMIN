<?php
session_start();
require_once('admin_database.php'); // Inclure le fichier de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Préparer la requête SQL pour vérifier les informations d'authentification de l'admin
    $sql = "SELECT * FROM admin WHERE username_admin = '$username' AND motdepasse_admin = '$password'";

    // Exécuter la requête SQL
    $result = $mysqli->query($sql);

    if ($result->num_rows == 1) {
        // L'admin est authentifié avec succès
        $_SESSION["admin_username"] = $username;

        // Mettre à jour la valeur de "acces" en 1
        $sql_update_acces = "UPDATE admin SET acces = 1 WHERE username_admin = '$username'";
        $mysqli->query($sql_update_acces);

        // Requête pour récupérer le nom de l'administrateur
        $sql_nom_admin = "SELECT nom_admin FROM admin WHERE username_admin = '$username'";
        $result_nom_admin = $mysqli->query($sql_nom_admin);

        if ($result_nom_admin->num_rows == 1) {
            $row = $result_nom_admin->fetch_assoc();
            $nom_admin = $row["nom_admin"];
            $_SESSION["nom_admin"] = $nom_admin; // Stockez le nom de l'administrateur dans la session
        }

        header("Location: Accueil.php"); // Rediriger vers la page du tableau de bord de l'admin
        exit();
    } else {
        // Échec de l'authentification, afficher un message d'erreur
        $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
    }

}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login - Admin</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <!-- Ajoutez d'autres liens CSS nécessaires -->
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-12 col-xl-10">
                <div class="card shadow-lg o-hidden border-0 my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col">
                                <div class="text-center p-5"><img src="assets/img/logo.jpg" style="width: 186px;">
                                    <div class="text-center">
                                        <h4 class="text-dark mb-4">Bienvenue sur l'Admin !</h4>
                                    </div>
                                    <form class="user" method="POST" action="">
                                        <div class="mb-3"><input class="form-control form-control-user" type="text" id="username" aria-describedby="usernameHelp" placeholder="Nom d'utilisateur" name="username" style="border-radius: 0;font-size: 14px;"></div>
                                        <div class="mb-3"><input class="form-control form-control-user" type="password" id="password" placeholder="Mot de passe" name="password" style="border-radius: 0;font-size: 14px;"></div>
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox small"></div>
                                        </div>
                                        <hr><button class="btn btn-primary d-block btn-user w-100" type="submit" style="border-radius: 0;background: #021c4f;">Connexion</button>
                                        <hr>
                                    </form>
                                    <?php
                                    if (isset($error_message)) {
                                        echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <!-- Ajoutez d'autres liens JavaScript nécessaires -->
</body>

</html>
