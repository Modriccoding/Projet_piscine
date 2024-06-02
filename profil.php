<?php
session_start();
include 'connexion.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: login.html");
    exit();
}

// Récupérer les informations de l'utilisateur
$utilisateur_id = $_GET['id'];
$sql = "SELECT * FROM utilisateurs WHERE id = $utilisateur_id";
$result = $conn->query($sql);
$utilisateur = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de <?php echo $utilisateur['nom']; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="index.php">ECE In</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="mon_reseau.php">Mon Réseau</a></li>
                    <li class="nav-item"><a class="nav-link" href="vous.php">Vous</a></li>
                    <li class="nav-item"><a class="nav-link" href="notifications.php">Notifications</a></li>
                    <li class="nav-item"><a class="nav-link" href="messagerie.php">Messagerie</a></li>
                    <li class="nav-item"><a class="nav-link" href="emplois.php">Emplois</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <main class="container">
        <h2>Profil de <?php echo $utilisateur['nom']; ?></h2>
        <p><strong>Email :</strong> <?php echo $utilisateur['email']; ?></p>
        <p><strong>Bio :</strong> <?php echo $utilisateur['bio']; ?></p>
        <p><strong>Date de naissance :</strong> <?php echo $utilisateur['date_naissance']; ?></p>
        <!-- Afficher d'autres informations pertinentes -->
    </main>
    <footer class="bg-light text-center py-3">
        <p>&copy; 2024 ECE In - Tous droits réservés</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
