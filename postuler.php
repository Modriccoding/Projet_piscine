<?php
session_start();
include 'connexion.php';

// Activer les messages d'erreur pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: login.html");
    exit();
}

$offre_id = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['offre_id']) && isset($_SESSION['utilisateur_id']) && isset($_FILES['lettre_motivation'])) {
        $offre_id = $_POST['offre_id'];
        $utilisateur_id = $_SESSION['utilisateur_id'];
        $file = $_FILES['lettre_motivation'];

        // Vérifier si une candidature existe déjà
        $check_query = "SELECT * FROM postulations WHERE utilisateur_id = ? AND emploi_id = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("ii", $utilisateur_id, $offre_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Vous avez déjà postulé pour cette offre.";
        } else {
            // Gérer le téléchargement du fichier
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $upload_file = $upload_dir . basename($file['name']);

            if (move_uploaded_file($file['tmp_name'], $upload_file)) {
                // Insérer la candidature dans la base de données
                $query = "INSERT INTO postulations (utilisateur_id, emploi_id, lettre_motivation, date_postulation) VALUES (?, ?, ?, NOW())";
                $stmt = $conn->prepare($query);
                if ($stmt === false) {
                    die("Erreur de préparation de la requête: " . $conn->error);
                }
                $stmt->bind_param("iis", $utilisateur_id, $offre_id, $upload_file);

                if ($stmt->execute()) {
                    echo "Candidature envoyée avec succès.";
                } else {
                    echo "Erreur lors de l'envoi de la candidature : " . $stmt->error;
                }
            } else {
                echo "Erreur lors du téléchargement de la lettre de motivation.";
            }
        }
    } else {
        echo "Erreur: Informations manquantes.";
    }
} else {
    if (isset($_GET['id'])) {
        $offre_id = $_GET['id'];
    } else {
        die("Erreur: Offre d'emploi non spécifiée.");
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postuler à une offre</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #3b5998; /* Couleur de fond similaire au logo */
            color: #fff; /* Texte blanc pour contraste */
        }
        .navbar {
            background-color: #3b5998; /* Couleur de la barre de navigation */
        }
        .navbar-brand img {
            height: 40px;
        }
        .navbar-nav .nav-link {
            color: #3b5998 !important; /* Couleur du texte de navigation */
        }
        .navbar-text {
            color: #3b5998; /* Couleur du texte "Connecté en tant que" */
        }
        .btn-primary {
            background-color: #4267B2; /* Couleur bleue primaire pour les boutons */
            border-color: #4267B2;
        }
        .btn-secondary {
            background-color: #8b9dc3; /* Couleur secondaire pour les boutons */
            border-color: #8b9dc3;
        }
        .carousel-inner img {
            width: 100%;
            height: auto;
        }
        .profile-img-nav {
            height: 30px;
            width: 30px;
            border-radius: 50%;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">
            <img src="LOGOfecebook.jpg" alt="Logo FECEBOOK" style="height: 40px;">
        </a>
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
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="navbar-text">
                        Connecté en tant que <?= htmlspecialchars($_SESSION['pseudo'] ?? 'Utilisateur') ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Se déconnecter</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h1>Postuler à une offre</h1>
        <form action="postuler.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="offre_id" value="<?= htmlspecialchars($offre_id) ?>">
            <div class="form-group">
                <label for="lettre_motivation">Lettre de motivation :</label>
                <input type="file" class="form-control" id="lettre_motivation" name="lettre_motivation" required>
            </div>
            <button type="submit" class="btn btn-primary">Valider la postulation</button>
            <a href="emplois.php" class="btn btn-secondary">Retour à Emplois</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
