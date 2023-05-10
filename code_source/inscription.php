<?php
/**
* Auteur: Mofassel Haque Srijon Rahman
* Date: 27.04.2023
* Projet: TPI video game club
* Détail: Page permettant à l'utilisateur de créer son compte enceint du site
*/
?>
<!DOCTYPE html>
<html lang="fr">

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

    const COULEUR_MESSAGE_ERREUR = "red";

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
            exit;
        }
    }



    if (isset($_POST['inscription'])) {

        $nom = filter_input(INPUT_POST, 'nom');
        $nom = antiInjectionXSS($nom);
        if ($nom == false || $nom == "") {
            $erreurNom = COULEUR_MESSAGE_ERREUR;
        }

        $prenom = filter_input(INPUT_POST, 'prenom');
        $prenom = antiInjectionXSS($prenom);
        if ($prenom == false || $prenom == "") {
            $erreurPrenom = COULEUR_MESSAGE_ERREUR;
        }

        $pseudo = filter_input(INPUT_POST, 'pseudo');
        $pseudo = antiInjectionXSS($pseudo);
        if ($pseudo == false || $pseudo == "") {
            $erreurPseudo = COULEUR_MESSAGE_ERREUR;
        } elseif (VerifiePseudoSimilaire($pseudo) == $pseudo) {
            echo '<script>alert("Ce pseudo existe déjà chez un autre compte. Veuillez écrire un autre")</script>';
            $erreurPseudo = COULEUR_MESSAGE_ERREUR;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $email = strip_tags($email);
        $email = addslashes($email);
        if ($email == false || $email == "") {
            $erreurEmail = COULEUR_MESSAGE_ERREUR;
        } elseif (VerifieEmailSimilaire($email) == $email) {
            echo '<script>alert("Cet email existe déjà chez un autre compte. Veuillez écrire un autre")</script>';
            $erreurEmail = COULEUR_MESSAGE_ERREUR;
        }

        $motDePasse = filter_input(INPUT_POST, 'motDePasse');
        $motDePasse = antiInjectionXSS($motDePasse);
        if (motDePasseSyntax($motDePasse) == false || $motDePasse == "") {
            $erreurMotDePasse = COULEUR_MESSAGE_ERREUR;
        }

        if ($erreurNom != COULEUR_MESSAGE_ERREUR && $erreurPrenom != COULEUR_MESSAGE_ERREUR 
        && $erreurPseudo != COULEUR_MESSAGE_ERREUR && $erreurEmail != COULEUR_MESSAGE_ERREUR && $erreurMotDePasse != COULEUR_MESSAGE_ERREUR) {
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
            <label for="nom" style="color:<?= $erreurNom; ?>">Votre nom :</label><br>
            <input type="text" name="nom" value="<?= $nom; ?>"> <br>

            <label for="prenom" style="color:<?= $erreurPrenom; ?>">Votre prénom :</label><br>
            <input type="text" name="prenom" value="<?= $prenom; ?>"> <br>

            <label for="pseudo" style="color:<?= $erreurPseudo; ?>">Votre pseudo :</label><br>
            <input type="text" name="pseudo" value="<?= $pseudo; ?>"> <br>

            <label for="email" style="color:<?= $erreurEmail; ?>">Votre email:</label><br>
            <input type="email" name="email" value="<?= $email; ?>"><br>

            <label for="motDePasse" style="color:<?= $erreurMotDePasse; ?>">Votre mot de passe : </label><br>
            <input type="password" name="motDePasse" value="<?= $motDePasse; ?>" placeholder="Minimum une majuscule, une minuscule, un chiffre et 8 caractères" style="width:19%"><br>

            <input type="submit" name="inscription" value="S'inscrire" class="btn btn-primary">
        </form>

    </main>
    <footer>
        &copy;Fait par Mofassel Haque Srijon Rahman <br>
        Contact : srijon.rhmn@eduge.ch
    </footer>

</body>

</html>