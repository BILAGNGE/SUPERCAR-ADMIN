<?php
require_once 'admin_database.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_evenement'])) {
    $id_evenement = $_GET['id_evenement'];
    
    // Exécuter la requête pour supprimer un événement
    $sql = "DELETE FROM evenement WHERE id_evenement = $id_evenement";
    $result = $mysqli->query($sql);

    if ($result) {
        header("Location: Evenements.php");
    } else {
        echo "Erreur lors de la suppression de l'événement.";
    }
}
?>
