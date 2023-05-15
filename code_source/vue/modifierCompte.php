<?php
/**
* Auteur: Mofassel Haque Srijon Rahman
* Date: 27.04.2023
* Projet: TPI video game club
* Détail: Page affichant un formulaire permettant à l'utilisateur de modifier ses données
*/
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/base.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <title>Modifier le profil</title>
</head>

<body>
    <?php

    //Permet d'utiliser les fonctions du fichier 
    require_once $_SERVER['DOCUMENT_ROOT'] . '/fonctions/fonction_utilisateur.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/fonctions/fonction_session.php';


    if (!DebutSession()) {
        // Pas de session, donc redirection à l'acceuil
        header('Location: /index.php');
        exit;
    }

    const COULEUR_MESSAGE_ERREUR = "red";

    $nom = "";
    $prenom = "";
    $pseudo = "";
    $email = "";

    $erreurNom = "";
    $erreurPrenom = "";
    $erreurPseudo = "";
    $erreurEmail = "";

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
        $boutonParametre = '<button class="btn btn-link"><a href="./profil.php?id=' . $utilisateur[0]->idUtilisateur . '">Compte</a></button>';
    } else {
        // Pas connecté, donc redirection à la page de connection
        header('Location: identification.php');
        exit;
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

    if (isset($_POST['modifierCompte'])) {
        $idUtilisateur = intval($utilisateur[0]->idUtilisateur);
        $nom = filter_input(INPUT_POST, 'nom');
        $nom = antiInjectionXSS($nom);
        if ($nom == "" || preg_match('/[a-zA-Z]/', $nom) == false) {
            echo '<script>alert("Veuillez écrire votre nom.")</script>';
            $erreurNom = COULEUR_MESSAGE_ERREUR;
        }

        $prenom = filter_input(INPUT_POST, 'prenom');
        $prenom = antiInjectionXSS($prenom);
        if ($prenom == "" || preg_match('/[a-zA-Z]/', $prenom) == false) {
            echo '<script>alert("Veuillez écrire votre prénom.")</script>';
            $erreurPrenom = COULEUR_MESSAGE_ERREUR;
        }

        modifierEmailPseudo($idUtilisateur);
        $pseudo = filter_input(INPUT_POST, 'pseudo');
        $pseudo = antiInjectionXSS($pseudo);
        if ($pseudo == "" || preg_match('/[a-zA-Z]/', $pseudo) == false) {
            echo '<script>alert("Veuillez écrire votre pseudo.")</script>';
            $erreurPseudo = COULEUR_MESSAGE_ERREUR;
        } else if (VerifiePseudoSimilaire($pseudo) == $pseudo) {
            echo '<script>alert("Ce pseudo existe déjà chez un autre compte. Veuillez écrire un autre")</script>';
            $erreurPseudo = COULEUR_MESSAGE_ERREUR;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $email = strip_tags($email);
        $email = addslashes($email);
        if ($email == false || $email == "") {
            echo '<script>alert("Veuillez écrire votre email.")</script>';
            $erreurEmail = COULEUR_MESSAGE_ERREUR;
        } else if (VerifieEmailSimilaire($email) == $email) {
            echo '<script>alert("Cet email existe déjà chez un autre compte. Veuillez écrire un autre")</script>';
            $erreurEmail = COULEUR_MESSAGE_ERREUR;
        }

        if ($idUtilisateur >= 1 && $erreurNom != COULEUR_MESSAGE_ERREUR && $erreurPrenom != COULEUR_MESSAGE_ERREUR 
        && $erreurPseudo != COULEUR_MESSAGE_ERREUR && $erreurEmail != COULEUR_MESSAGE_ERREUR) {
            if (modifierUtilisateur($idUtilisateur, $nom, $prenom, $pseudo, $email)) {
                header('Location: profil.php');
                exit;
            }
        } else {
            echo '<script>alert("Pas possible il vous manque des valeurs ou des valeurs existent déjà chez un autre compte")</script>';
        }
    }

    $donneesUtilisateur = RecuperationDonneeUtilisateur($utilisateur[0]->idUtilisateur);
    if ($donneesUtilisateur === false) {
        echo "Les données de l'utilisateur ne peuvent être affichées. Une erreur s'est produite.";
        exit;
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
                    <?php if ($utilisateur) {
                        echo "<li class=\"nav-item\"><a class=\"nav-link\" href=\"./editerJeu.php\">Éditer un jeu vidéo</a></li>";
                    }
                    ?>
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
            <?php foreach ($donneesUtilisateur as $utilisateur) {

                echo "<label for=\"nom\" style=\"" . $erreurNom . "\">Votre nom :</label><br>";
                echo "<input type=\"text\" name=\"nom\" value=\"" . $utilisateur->nom . "\"><br>";

                echo "<label for=\"prenom\" style=\"" . $erreurPrenom . "\">Votre prénom :</label><br>";
                echo "<input type=\"text\" name=\"prenom\" value=\"" . $utilisateur->prenom . "\"><br>";

                echo "<label for=\"pseudo\" style=\"" . $erreurPseudo . "\">Votre pseudo :</label><br>";
                echo "<input type=\"text\" name=\"pseudo\" value=\"" . $utilisateur->pseudo . "\"><br>";

                echo "<label for=\"email\" style=\"" . $erreurEmail . "\">Votre email : </label><br>";
                echo "<input type=\"email\" name=\"email\" value=\"" . $utilisateur->email . "\"><br>";
            } ?>
            <input type="submit" name="modifierCompte" class="btn btn-primary" value="Modifier le compte">
        </form>
    </main>
    <footer>
        &copy;Fait par Mofassel Haque Srijon Rahman <br>
        Contact : srijon.rhmn@eduge.ch
    </footer>

</body>

</html>