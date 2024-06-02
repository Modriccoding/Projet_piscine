<?php
session_start();
include 'connexion.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: login.html");
    exit();
}

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $utilisateur_id = $_SESSION['utilisateur_id'];
    $contenu = $_POST['contenu'];
    $date_publication = date('Y-m-d H:i:s');
    $visibilite = $_POST['visibilite'];

    $sql = "INSERT INTO statuts (utilisateur_id, contenu, date_publication, visibilite) VALUES ($utilisateur_id, '$contenu', '$date_publication', '$visibilite')";
    if ($conn->query($sql) === TRUE) {
        echo "Votre statut a été publié avec succès.";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publier - ECE In</title>
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
        <h2>Publier un statut</h2>
        <form method="post">
            <div class="form-group">
                <label for="contenu">Contenu :</label>
                <textarea class="form-control" id="contenu" name="contenu" required></textarea>
            </div>
            <div class="form-group">
                <label for="visibilite">Visibilité :</label>
                <select class="form-control" id="visibilite" name="visibilite">
                    <option value="public">Public</option>
                    <option value="privé">Privé</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Publier</button>
        </form>
    </main>
    <footer class="bg-light text-center py-3">
        <p>&copy; 2024 ECE In - Tous droits réservés</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
