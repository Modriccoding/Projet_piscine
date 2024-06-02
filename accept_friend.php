<?php
session_start();
include 'connexion.php';

// Activer les messages d'erreur pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    die("Erreur: Utilisateur non connecté.");
}

$receiver_id = $_SESSION['utilisateur_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['sender_id'])) {
        $sender_id = $_POST['sender_id'];

        // Ajouter la relation d'amitié
        $query = "INSERT INTO amis (user_id, friend_id) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Erreur de préparation de la requête: " . $conn->error);
        }
        $stmt->bind_param("ii", $sender_id, $receiver_id);

        if ($stmt->execute()) {
            // Supprimer la demande d'ami
            $delete_request = "DELETE FROM demandes_amis WHERE demandeur_id = ? AND destinataire_id = ?";
            $delete_stmt = $conn->prepare($delete_request);
            $delete_stmt->bind_param("ii", $sender_id, $receiver_id);
            $delete_stmt->execute();

            $message = "Demande d'ami acceptée.";
        } else {
            $message = "Erreur lors de l'acceptation de la demande d'ami : " . $stmt->error;
        }
    } else {
        $message = "Erreur: Informations manquantes.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accepter une demande d'ami</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Accepter une demande d'ami</h1>
        <?php
        if ($message != '') {
            echo "<div class='alert alert-info'>$message</div>";
        }
        ?>
        <a href="index.php" class="btn btn-primary">Retour à l'accueil</a>
    </div>
</body>
</html>
