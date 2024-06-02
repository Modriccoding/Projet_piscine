<?php
session_start();
include 'connexion.php';

// Activer les messages d'erreur pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifiez si l'utilisateur ou l'administrateur est connecté
if (!isset($_SESSION['utilisateur_id']) && !isset($_SESSION['admin_id'])) {
    die("Erreur: Utilisateur non connecté.");
}

// Déterminez si c'est un utilisateur ou un administrateur
$is_user = isset($_SESSION['utilisateur_id']);
$sender_id = $is_user ? $_SESSION['utilisateur_id'] : $_SESSION['admin_id'];
$sender_type = $is_user ? 'utilisateur' : 'administrateur';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_friend'])) {
        if (isset($_POST['friend_username'])) {
            $friend_username = $_POST['friend_username'];

            // Rechercher l'ID de l'utilisateur correspondant au nom d'utilisateur fourni
            $query = "SELECT id FROM utilisateurs WHERE pseudo = ?";
            $stmt = $conn->prepare($query);
            if ($stmt === false) {
                die("Erreur de préparation de la requête: " . $conn->error);
            }
            $stmt->bind_param("s", $friend_username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $receiver_id = $row['id'];

                // Vérifier si l'utilisateur essaie de s'ajouter lui-même
                if ($sender_id == $receiver_id) {
                    $message = "Erreur: Vous ne pouvez pas vous ajouter vous-même en tant qu'ami.";
                } else {
                    // Vérifier si une relation d'amitié existe déjà
                    $check_friendship = "SELECT * FROM amis WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)";
                    $stmt = $conn->prepare($check_friendship);
                    if ($stmt === false) {
                        die("Erreur de préparation de la requête: " . $conn->error);
                    }
                    $stmt->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        // Les utilisateurs sont déjà amis
                        $message = "Erreur: Vous êtes déjà amis avec cet utilisateur.";
                    } else {
                        // Vérifier si une demande existe déjà
                        $check_request = "SELECT * FROM demandes_amis WHERE demandeur_id = ? AND destinataire_id = ?";
                        $stmt = $conn->prepare($check_request);
                        if ($stmt === false) {
                            die("Erreur de préparation de la requête: " . $conn->error);
                        }
                        $stmt->bind_param("ii", $sender_id, $receiver_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows == 0) {
                            // Insérer la demande dans la base de données
                            $query = "INSERT INTO demandes_amis (demandeur_id, destinataire_id) VALUES (?, ?)";
                            $stmt = $conn->prepare($query);
                            if ($stmt === false) {
                                die("Erreur de préparation de la requête: " . $conn->error);
                            }
                            $stmt->bind_param("ii", $sender_id, $receiver_id);

                            if ($stmt->execute()) {
                                $message = "Demande d'ami envoyée avec succès.";
                            } else {
                                $message = "Erreur lors de l'envoi de la demande d'ami : " . $stmt->error;
                            }
                        } else {
                            $message = "Erreur: Une demande d'ami a déjà été envoyée.";
                        }
                    }
                }
            } else {
                $message = "Erreur: Aucun utilisateur trouvé avec ce nom d'utilisateur.";
            }
        } else {
            $message = "Erreur: Nom d'utilisateur non spécifié.";
        }
    } else {
        $message = "Erreur: Bouton d'envoi de la demande non spécifié.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un ami</title>
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
                <?php if (isset($_SESSION['admin_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="admin_only.php">ADMIN ONLY</a></li>
                <?php endif; ?>
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
        <h1>Ajouter un ami</h1>
        <?php
        if ($message != '') {
            echo "<div class='alert alert-info'>$message</div>";
        }
        ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="friend_username">Nom d'utilisateur :</label>
                <input type="text" class="form-control" id="friend_username" name="friend_username" required>
            </div>
            <button type="submit" name="add_friend" class="btn btn-primary">Ajouter comme ami</button>
        </form>
        <a href="index.php" class="btn btn-secondary mt-3">Retour à l'accueil</a>
    </div>
</body>
</html>
