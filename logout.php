<?php
session_start();
session_destroy(); // Détruit la session en cours

// Redirige vers la page de connexion
header("Location: login.php");
exit();
?>
