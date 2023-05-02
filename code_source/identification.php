<!DOCTYPE html>
<html lang="en">
<!--
    Auteur: Srijon Rahman
    Date: 27.04.2023
    Projet: TPI video game club
    Détail: Modèle de vue pour les autres pages du site
-->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/base.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <title>Canvas</title>
</head>

<body>
    <?php
    session_start();
    //Permet d'utiliser les fonctions du fichier 
    require_once './fonctions/fonction_utilisateur.php';
    require_once './fonctions/fonction_session.php';

    //variables
    const COL_ERROR = "red";

    $email = "";
    $motDePasse = "";

    $erreurEmail = "";
    $erreurMotDePasse = "";

    $utilisateur = GetUserFromSession();
    $nomUtilisateur = 'invité';
    $boutonDirection = '/identification.php';
    $boutonTexte = 'Connexion';
    $boutonParametre = '';

    if ($utilisateur != false) {
        $nomUtilisateur = $utilisateur->pseudo;
        $boutonDirection = '/logout.php';
        $boutonTexte = 'Déconnexion';
        $boutonParametre = '<button class="btn"><a href="./account.php?id=' . $utilisateur->idUtilisateur . '">Compte</a></button>';
    }


    if (isset($_POST['identification'])) {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        if ($email == false) {
            $erreurEmail = COL_ERROR;
        }

        $motDePasse = filter_input(INPUT_POST, 'motDePasse');
        if ($motDePasse == false) {
            $erreurMotDePasse = COL_ERROR;
        }

        if ($erreurMotDePasse != COL_ERROR && $erreurEmail != COL_ERROR) {
            if (VerifieUtilisateurExiste($email, $motDePasse)) {
                $_SESSION['idUtilisateur'] = RecupereUtilisateurParEmail($email);
                header("location: profil.php");
                exit();
            }
        } else {
            echo '<script>alert("Pas possible il vous manque des valeurs ou des valeurs sont fausses")</script>';
        }
    }

    ?>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link active" href="./index.php">
                            <h2>Video game club</h2>
                        </a></li>
                    <li class="nav-item"><a class="nav-link" href="./index.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="./editerJeu.php">Éditer un jeu</a></li>
                    <li class="nav-item"><a class="nav-link" href="./identification.php"> Identification </a></li>
                    <li class="nav-item"><a class="nav-link" href="./inscription.php"> Inscription </a></li>
                    <li class="nav-item"><a class="nav-link" href="./profil.php">Profile</a></li>
                </ul>
                <div class="card d-flex flex-column align-items-center">
                    <div class="card-body">
                        <h5 class="card-title"><?= $nomUtilisateur ?></h5>
                        <?= $boutonParametre ?>
                        <a href="<?= $boutonDirection ?>" class="btn btn-primary"><?= $boutonTexte ?></a>
                    </div>
                </div>
            </div>
        </nav>

    </header>
    <main>

        <form action="" method="POST">
            <label for="email" style="color:<?php echo $erreurEmail; ?>">Email:</label><br>
            <input type="email" name="email" value="<?php echo $email; ?>"><br>
            <label for="motDePasse" style="color:<?php echo $erreurMotDePasse; ?>">Mot de passe : </label><br>
            <input type="password" name="motDePasse" value="<?php echo $motDePasse; ?>"><br>
            <input type="submit" name="identification" value="S'identifier" class="btn btn-primary"><br>
            <a href="./inscription.php">Pas de compte ?</a>
        </form>

    </main>
    <footer>
        &copy;Fait par Mofassel Haque Srijon Rahman <br>
        Contact : srijon.rhmn@eduge.ch
    </footer>

</body>

</html>