<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ece_in";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}
?>
