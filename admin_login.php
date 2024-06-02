<?php
session_start();
include 'connexion.php'; // Inclure les paramètres de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Vérifiez si l'email existe
    $sql = "SELECT * FROM administrateurs WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();

        // Vérifiez le mot de passe
        if (password_verify($mot_de_passe, $admin['mot_de_passe'])) {
            // Connexion réussie
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['pseudo'] = $admin['pseudo']; // Ajoutez cette ligne pour enregistrer le pseudo
            header("Location: index.php"); // Rediriger vers le tableau de bord de l'administrateur
            exit();
        } else {
            // Mot de passe incorrect
            header("Location: admin_login.php?error=password");
            exit();
        }
    } else {
        // Email incorrect
        header("Location: admin_login.php?error=email");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administrateur - ECE In</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .header-img {
            width: 100%;
            height: auto;
        }
        .logo-img {
            display: block;
            margin: 0 auto;
            max-width: 200px;
            height: auto;
        }
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
            color: #fff !important;
        }
        .navbar-text {
            color: #fff;
        }
        .btn-primary {
            background-color: #4267B2;
            border-color: #4267B2;
        }
        .btn-secondary {
            background-color: #8b9dc3;
            border-color: #8b9dc3;
        }
        .admin-login-btn {
            background-color: #4267B2;
            border-color: #4267B2;
            color: #fff;
        }
        .container {
            background-color: #fff;
            color: #000;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        .form-group label {
            color: #3b5998;
        }
        .footer {
            background-color: #3b5998;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const error = urlParams.get('error');

            if (error) {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger';

                if (error === 'email') {
                    alertDiv.textContent = "Adresse email non reconnue.";
                } else if (error === 'password') {
                    alertDiv.textContent = "Mot de passe invalide.";
                }

                document.querySelector('.container').prepend(alertDiv);
            }
        });
    </script>
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
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="navbar-text">
                        la grande école du numérique 
                    </span>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <img src="CampusECE.jpg" alt="ECE Building" class="header-img">
        <img src="LOGOECE.jpg" alt="ECE Logo" class="logo-img">
        <h2>Connexion Administrateur</h2>
        <form action="admin_login.php" method="post">
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="mot_de_passe">Mot de passe :</label>
                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
    </div>
</body>
</html>
