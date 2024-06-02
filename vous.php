<?php
session_start();
include 'connexion.php';

// Vérifiez si l'utilisateur ou l'administrateur est connecté
if (!isset($_SESSION['utilisateur_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les informations de l'utilisateur ou de l'administrateur connecté
if (isset($_SESSION['utilisateur_id'])) {
    $utilisateur_id = $_SESSION['utilisateur_id'];
    $query_user = "SELECT pseudo, email, nom, bio, photo_profil, photo_mur FROM utilisateurs WHERE id = ?";
    $stmt_user = $conn->prepare($query_user);
    $stmt_user->bind_param("i", $utilisateur_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $utilisateur = $result_user->fetch_assoc();
    $pseudo = $utilisateur['pseudo'] ?? '';
    $email = $utilisateur['email'] ?? '';
    $nom = $utilisateur['nom'] ?? '';
    $bio = $utilisateur['bio'] ?? '';
    $photo_profil = $utilisateur['photo_profil'] ?? '';
    $photo_mur = $utilisateur['photo_mur'] ?? '';
} elseif (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $query_admin = "SELECT pseudo, email, nom, bio, photo_profil, photo_mur FROM administrateurs WHERE id = ?";
    $stmt_admin = $conn->prepare($query_admin);
    $stmt_admin->bind_param("i", $admin_id);
    $stmt_admin->execute();
    $result_admin = $stmt_admin->get_result();
    $admin = $result_admin->fetch_assoc();
    $pseudo = $admin['pseudo'] ?? '';
    $email = $admin['email'] ?? '';
    $nom = $admin['nom'] ?? '';
    $bio = $admin['bio'] ?? '';
    $photo_profil = $admin['photo_profil'] ?? '';
    $photo_mur = $admin['photo_mur'] ?? '';
}

// Gérer la mise à jour des informations et le téléchargement de la photo de profil et de l'image du mur
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $nom = $_POST['nom'];
    $bio = $_POST['bio'];

    if (isset($_SESSION['utilisateur_id'])) {
        // Mettre à jour les informations de l'utilisateur
        $query_update = "UPDATE utilisateurs SET pseudo = ?, email = ?, nom = ?, bio = ? WHERE id = ?";
        $stmt_update = $conn->prepare($query_update);
        $stmt_update->bind_param("ssssi", $pseudo, $email, $nom, $bio, $utilisateur_id);
        $stmt_update->execute();
    } elseif (isset($_SESSION['admin_id'])) {
        // Mettre à jour les informations de l'administrateur
        $query_update = "UPDATE administrateurs SET pseudo = ?, email = ?, nom = ?, bio = ? WHERE id = ?";
        $stmt_update = $conn->prepare($query_update);
        $stmt_update->bind_param("ssssi", $pseudo, $email, $nom, $bio, $admin_id);
        $stmt_update->execute();
    }

    // Gérer le téléchargement de la photo de profil
    if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["photo_profil"]["name"]);
        move_uploaded_file($_FILES["photo_profil"]["tmp_name"], $target_file);

        // Mettre à jour le chemin de la photo de profil dans la base de données
        if (isset($_SESSION['utilisateur_id'])) {
            $query_update_photo = "UPDATE utilisateurs SET photo_profil = ? WHERE id = ?";
            $stmt_update_photo = $conn->prepare($query_update_photo);
            $stmt_update_photo->bind_param("si", $target_file, $utilisateur_id);
            $stmt_update_photo->execute();
        } elseif (isset($_SESSION['admin_id'])) {
            $query_update_photo = "UPDATE administrateurs SET photo_profil = ? WHERE id = ?";
            $stmt_update_photo = $conn->prepare($query_update_photo);
            $stmt_update_photo->bind_param("si", $target_file, $admin_id);
            $stmt_update_photo->execute();
        }
    }

    // Gérer le téléchargement de l'image du mur
    if (isset($_FILES['photo_mur']) && $_FILES['photo_mur']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["photo_mur"]["name"]);
        move_uploaded_file($_FILES["photo_mur"]["tmp_name"], $target_file);

        // Mettre à jour le chemin de l'image du mur dans la base de données
        if (isset($_SESSION['utilisateur_id'])) {
            $query_update_wall = "UPDATE utilisateurs SET photo_mur = ? WHERE id = ?";
            $stmt_update_wall = $conn->prepare($query_update_wall);
            $stmt_update_wall->bind_param("si", $target_file, $utilisateur_id);
            $stmt_update_wall->execute();
        } elseif (isset($_SESSION['admin_id'])) {
            $query_update_wall = "UPDATE administrateurs SET photo_mur = ? WHERE id = ?";
            $stmt_update_wall = $conn->prepare($query_update_wall);
            $stmt_update_wall->bind_param("si", $target_file, $admin_id);
            $stmt_update_wall->execute();
        }
    }

    // Gérer le téléchargement des photos de l'album
    if (isset($_FILES['album_photos'])) {
        foreach ($_FILES['album_photos']['tmp_name'] as $index => $tmp_name) {
            if ($_FILES['album_photos']['error'][$index] === UPLOAD_ERR_OK) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["album_photos"]["name"][$index]);
                move_uploaded_file($tmp_name, $target_file);

                // Insérer le chemin de la photo dans la table albums
                if (isset($_SESSION['utilisateur_id'])) {
                    $query_album_insert = "INSERT INTO albums (utilisateur_id, photo) VALUES (?, ?)";
                    $stmt_album_insert = $conn->prepare($query_album_insert);
                    $stmt_album_insert->bind_param("is", $utilisateur_id, $target_file);
                } elseif (isset($_SESSION['admin_id'])) {
                    $query_album_insert = "INSERT INTO albums (admin_id, photo) VALUES (?, ?)";
                    $stmt_album_insert = $conn->prepare($query_album_insert);
                    $stmt_album_insert->bind_param("is", $admin_id, $target_file);
                }
                $stmt_album_insert->execute();
            }
        }
    }

    // Rediriger pour éviter la resoumission du formulaire
    header("Location: vous.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Profil - ECE In</title>
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
        .profile-card {
            background-color: #fff;
            color: #3b5998;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        .profile-img {
            max-width: 150px;
            border-radius: 50%;
        }
        .wall-img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-top: 20px;
        }
        .album-img {
            width: 100px;
            height: 100px;
            margin: 10px;
            object-fit: cover;
            border-radius: 10px;
            position: relative;
        }
        .delete-photo-form {
            position: absolute;
            top: 5px;
            right: 5px;
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
                <?php if (isset($_SESSION['admin_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="admin_only.php">ADMIN ONLY</a></li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="navbar-text">
                        Connecté en tant que <?= htmlspecialchars($pseudo) ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Se déconnecter</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="profile-card">
            <h1>Votre Profil</h1>
            <form action="vous.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="pseudo">Pseudo :</label>
                    <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?= htmlspecialchars($pseudo) ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                </div>
                <div class="form-group">
                    <label for="nom">Nom :</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($nom) ?>" required>
                </div>
                <div class="form-group">
                    <label for="bio">Bio :</label>
                    <textarea class="form-control" id="bio" name="bio" required><?= htmlspecialchars($bio) ?></textarea>
                </div>
                <div class="form-group">
                    <label for="photo_profil">Photo de Profil :</label>
                    <?php if ($photo_profil): ?>
                        <img src="<?= htmlspecialchars($photo_profil) ?>" alt="Photo de profil" class="profile-img">
                    <?php endif; ?>
                    <input type="file" class="form-control-file" id="photo_profil" name="photo_profil">
                </div>
                <div class="form-group">
                    <label for="photo_mur">Image de Mur :</label>
                    <?php if ($photo_mur): ?>
                        <img src="<?= htmlspecialchars($photo_mur) ?>" alt="Image de mur" class="wall-img">
                    <?php endif; ?>
                    <input type="file" class="form-control-file" id="photo_mur" name="photo_mur">
                </div>
                <div class="form-group">
                    <label for="album_photos">Album Photos :</label>
                    <input type="file" class="form-control-file" id="album_photos" name="album_photos[]" multiple>
                </div>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
        <div class="album mt-5">
            <h2>Mon Album</h2>
            <div class="album-photos d-flex flex-wrap">
                <?php
                if (isset($_SESSION['utilisateur_id'])) {
                    $query_album = "SELECT id, photo FROM albums WHERE utilisateur_id = ?";
                    $stmt_album = $conn->prepare($query_album);
                    $stmt_album->bind_param("i", $utilisateur_id);
                } elseif (isset($_SESSION['admin_id'])) {
                    $query_album = "SELECT id, photo FROM albums WHERE admin_id = ?";
                    $stmt_album = $conn->prepare($query_album);
                    $stmt_album->bind_param("i", $admin_id);
                }
                $stmt_album->execute();
                $result_album = $stmt_album->get_result();
                while ($row = $result_album->fetch_assoc()):
                ?>
                    <div class="position-relative">
                        <img src="<?= htmlspecialchars($row['photo']) ?>" alt="Photo de l'album" class="album-img">
                        <form class="delete-photo-form" action="delete_photo.php" method="post">
                            <input type="hidden" name="photo_id" value="<?= $row['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

