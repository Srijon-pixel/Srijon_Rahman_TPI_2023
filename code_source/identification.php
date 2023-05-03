<!DOCTYPE html>
<html lang="en">
<!--
    Auteur: Mofassel Haque Srijon Rahman
    Date: 02.05.2023
    Projet: TPI video game club
    Détail: Page affichant un formulaire pour l'utilisateur afin de se connecter si ce dernier possède déjà un compte enceint du site
-->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/base.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <title>Identification</title>
</head>

<body>
    <?php
    session_start();
    //Permet d'utiliser les fonctions du fichier 
    require_once './fonctions/fonction_utilisateur.php';
    require_once './fonctions/fonction_session.php';

    //variables
    const ERREUR = "red";

    $email = "";
    $motDePasse = "";

    $erreurEmail = "";
    $erreurMotDePasse = "";

    $utilisateur = RecupereUtilisateurParSession();
    $nomUtilisateur = 'invité';
    $boutonDirection = '/identification.php';
    $boutonTexte = 'Connexion';
    $boutonParametre = '';
    $nameConnexionDeconnexion = "connexion";
    
    if ($utilisateur != false) {
        $nomUtilisateur = $utilisateur[0]->pseudo;
        $nameConnexionDeconnexion = "deconnexion";
        $boutonTexte = 'Déconnexion';
        $boutonParametre = '<button class="btn"><a href="./profil.php?id=' . $utilisateur[0]->idUtilisateur . '">Compte</a></button>';
    }

    if (isset($_POST[$nameConnexionDeconnexion])) {
        if ($nameConnexionDeconnexion == "connexion") {
            header("location: identification.php");
            exit;
        } else {
            session_destroy();
            header("location: index.php");
        }
    }


    if (isset($_POST['identification'])) {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        if ($email == false) {
            $erreurEmail = ERREUR;
        }

        $motDePasse = filter_input(INPUT_POST, 'motDePasse');
        if ($motDePasse == false) {
            $erreurMotDePasse = ERREUR;
        }

        if ($erreurMotDePasse != ERREUR && $erreurEmail != ERREUR) {
            if (VerifieUtilisateurExiste($email, $motDePasse)) {
                $_SESSION['idUtilisateur'] = RecupereUtilisateurParEmail($email);
                header("location: profil.php");
                exit();
            }
        } else {
            echo '<script>alert("Pas possible il vous manque des valeurs ou des valeurs existent déjà chez d\'autre compte")</script>';
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
                    <li class="nav-item"><a class="nav-link" href="./editerJeu.php">Éditer un jeu vidéo</a></li>
                </ul>
                <div class="card d-flex flex-column align-items-center">
                    <div class="card-body">
                        <h5 class="card-title"><?= $nomUtilisateur ?></h5>
                        <?= $boutonParametre ?>
                        <form action="" method="POST">
                            <input type="submit" name="<?= $nameConnexionDeconnexion ?>" class="btn btn-primary" value="<?= $boutonTexte ?>">
                        </form>
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