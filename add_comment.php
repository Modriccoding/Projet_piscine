<?php
session_start();
include 'connexion.php';

if (!isset($_SESSION['utilisateur_id'])) {
    die("Utilisateur non connecté.");
}

$utilisateur_id = $_SESSION['utilisateur_id'];
$photo_id = intval($_POST['photo_id']);
$commentaire = $_POST['commentaire'];

// Ajouter le commentaire
$query = "INSERT INTO commentaires (utilisateur_id, photo_id, commentaire, date_commentaire) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($query);
$stmt->bind_param("iis", $utilisateur_id, $photo_id, $commentaire);
$stmt->execute();

// Ajouter une notification
$commentaire_id = $stmt->insert_id;
$query = "INSERT INTO notifications (utilisateur_id, type, photo_id, commentaire_id) VALUES (?, 'comment', ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $utilisateur_id, $photo_id, $commentaire_id);
$stmt->execute();

header("Location: index.php");
exit();
?>
