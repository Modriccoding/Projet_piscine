<?php
include 'connexion.php';
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $utilisateur_id = $_SESSION['utilisateur_id'];
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $nom = $_POST['nom'];
    $bio = $_POST['bio'];
    
    $sql = "UPDATE utilisateurs SET pseudo = '$pseudo', email = '$email', nom = '$nom', bio = '$bio' WHERE id = $utilisateur_id";

    if ($conn->query($sql) === TRUE) {
        echo "Profil mis à jour avec succès.";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }
}

header("Location: vous.php");
exit();

$conn->close();
?>
