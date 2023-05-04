<!DOCTYPE html>
<html lang="en">
<!--
    Auteur: Mofassel Haque Srijon Rahman
    Date: 02.05.2023
    Projet: TPI video game club
    Détail: Page permettant à l'utilisateur de créer son compte enceint du site 
-->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/base.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <title>Inscription</title>
</head>

<body>
    <?php

    //Permet d'utiliser les fonctions du fichier 
    require_once './fonctions/fonction_utilisateur.php';
    require_once './fonctions/fonction_session.php';

    const ERREUR = "red";

    $nom = "";
    $prenom = "";
    $pseudo = "";
    $email = "";
    $motDePasse = "";

    $erreurNom = "";
    $erreurPrenom = "";
    $erreurPseudo = "";
    $erreurEmail = "";
    $erreurMotDePasse = "";

    $utilisateur = RecupereUtilisateurParSession();
    $nomUtilisateur = 'invité';
    $boutonDirection = '/identification.php';
    $boutonTexte = 'Connexion';
    $boutonParametre = '';
    $nomConnexionDeconnexion = "connexion";

    if ($utilisateur != false) {
        $nomUtilisateur = $utilisateur[0]->pseudo;
        $nomConnexionDeconnexion = "deconnexion";
        $boutonTexte = 'Déconnexion';
        $boutonParametre = '<button class="btn"><a href="./profil.php?id=' . $utilisateur[0]->idUtilisateur . '">Compte</a></button>';
    }

    if (isset($_POST[$nomConnexionDeconnexion])) {
        if ($nomConnexionDeconnexion == "connexion") {
            header("location: identification.php");
            exit;
        } else {
            session_destroy();
            header("location: index.php");
        }
    }



    if (isset($_POST['inscription'])) {

        $nom = filter_input(INPUT_POST, 'nom');
        $nom = antiInjectionXSS($nom);
        if ($nom == false || $nom == "") {
            $erreurNom = ERREUR;
        }

        $prenom = filter_input(INPUT_POST, 'prenom');
        $prenom = antiInjectionXSS($prenom);
        if ($prenom == false || $prenom == "") {
            $erreurPrenom = ERREUR;
        }

        $pseudo = filter_input(INPUT_POST, 'pseudo');
        $pseudo = antiInjectionXSS($pseudo);
        if ($pseudo == false || $pseudo == "") {
            $erreurPseudo = ERREUR;
        } elseif (VerifiePseudoSimilaire($pseudo) == $pseudo) {
            echo '<script>alert("Ce pseudo existe déjà chez un autre compte. Veuillez écrire un autre")</script>';
            $erreurPseudo = ERREUR;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $email = strip_tags($email);
        $email = addslashes($email);
        if ($email == false || $email == "") {
            $erreurEmail = ERREUR;
        } elseif (VerifieEmailSimilaire($email) == $email) {
            echo '<script>alert("Cet email existe déjà chez un autre compte. Veuillez écrire un autre")</script>';
            $erreurEmail = ERREUR;
        }

        $motDePasse = filter_input(INPUT_POST, 'motDePasse');
        $motDePasse = antiInjectionXSS($motDePasse);
        if (motDePasseSyntax($motDePasse) == false || $motDePasse == "") {
            $erreurMotDePasse = ERREUR;
        }

        if ($erreurNom != ERREUR && $erreurPrenom != ERREUR && $erreurPseudo != ERREUR && $erreurEmail != ERREUR && $erreurMotDePasse != ERREUR) {
            if (AjouterUtilisateur($nom, $prenom, $pseudo, $email, $motDePasse)) {
                header('Location: identification.php');
                exit;
            }
        } else {
            echo '<script>alert("Une erreur c\'est produite, il vous manque des valeurs ou alors des valeurs sont fausses")</script>';
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
                            <input type="submit" name="<?= $nomConnexionDeconnexion ?>" class="btn btn-primary" value="<?= $boutonTexte ?>">
                        </form>
                    </div>
                </div>
            </div>
        </nav>

    </header>
    <main>

        <form action="" method="POST">
            <label for="nom" style="color:<?php echo $erreurNom; ?>">Votre nom :</label><br>
            <input type="text" name="nom" value="<?php echo $nom; ?>"> <br>

            <label for="prenom" style="color:<?php echo $erreurPrenom; ?>">Votre prénom :</label><br>
            <input type="text" name="prenom" value="<?php echo $prenom; ?>"> <br>

            <label for="pseudo" style="color:<?php echo $erreurPseudo; ?>">Votre pseudo :</label><br>
            <input type="text" name="pseudo" value="<?php echo $pseudo; ?>"> <br>

            <label for="email" style="color:<?php echo $erreurEmail; ?>">Votre email:</label><br>
            <input type="email" name="email" value="<?php echo $email; ?>"><br>

            <label for="motDePasse" style="color:<?php echo $erreurMotDePasse; ?>">Votre mot de passe : </label><br>
            <input type="password" name="motDePasse" value="<?php echo $motDePasse; ?>" placeholder="Minimum une majuscule, une minuscule, un chiffre et 8 caractères" style="width:19%"><br>

            <input type="submit" name="inscription" value="S'inscrire" class="btn btn-primary">
        </form>

    </main>
    <footer>
        &copy;Fait par Mofassel Haque Srijon Rahman <br>
        Contact : srijon.rhmn@eduge.ch
    </footer>

</body>

</html>