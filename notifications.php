<?php
session_start();
include 'connexion.php';

// Vérifiez si l'utilisateur ou l'administrateur est connecté
if (!isset($_SESSION['utilisateur_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$is_user = isset($_SESSION['utilisateur_id']);
$is_admin = isset($_SESSION['admin_id']);

// Récupérer les notifications
$query = "SELECT n.type, n.date, u.pseudo, a.photo, c.commentaire
          FROM notifications n
          LEFT JOIN utilisateurs u ON n.utilisateur_id = u.id
          LEFT JOIN albums a ON n.photo_id = a.id
          LEFT JOIN commentaires c ON n.commentaire_id = c.id
          ORDER BY n.date DESC";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - ECE In</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #3b5998;
            color: #fff;
        }
        .navbar {
            background-color: #3b5998;
        }
        .navbar-brand img {
            height: 40px;
        }
        .navbar-nav .nav-link {
            color: #3b5998 !important;
        }
        .navbar-text {
            color: #3b5998;
        }
        .notification {
            background-color: #fff;
            color: #3b5998;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">
            <img src="LOGOfecebook.jpg" alt="Logo FECEBOOK">
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
                <?php if ($is_admin): ?>
                    <li class="nav-item"><a class="nav-link" href="admin_only.php">ADMIN ONLY</a></li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="navbar-text">
                        Connecté en tant que <?= htmlspecialchars($_SESSION['pseudo']) ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Se déconnecter</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-3">
        <h1>Notifications</h1>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="notification">
                <?php if ($row['type'] == 'like'): ?>
                    <p><?= htmlspecialchars($row['pseudo']) ?> a liké une photo.</p>
                    <img src="<?= htmlspecialchars($row['photo']) ?>" alt="Photo" style="max-width: 100px;">
                <?php elseif ($row['type'] == 'comment'): ?>
                    <p><?= htmlspecialchars($row['pseudo']) ?> a commenté une photo:</p>
                    <p>"<?= htmlspecialchars($row['commentaire']) ?>"</p>
                    <img src="<?= htmlspecialchars($row['photo']) ?>" alt="Photo" style="max-width: 100px;">
                <?php endif; ?>
                <small>Le <?= htmlspecialchars($row['date']) ?></small>
            </div>
        <?php endwhile; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
