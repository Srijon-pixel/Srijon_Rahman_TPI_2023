<!DOCTYPE html>
<html lang="en">
<!--
    Auteur: Mofassel Haque Srijon Rahman
    Date: 02.05.2023
    Projet: TPI video game club
    Détail: Page affichant un formulaire permettant à l'utilisateur de modifier ses données
-->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/base.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <title>Modifier le profil</title>
</head>

<body>
    <?php

    //Permet d'utiliser les fonctions du fichier 
    require_once './fonctions/fonction_utilisateur.php';
    require_once './fonctions/fonction_session.php';


    if (!DebutSession()) {
        // Pas de session, donc redirection à l'acceuil
        header('Location: /index.php');
        exit;
    }

    const ERREUR = "red";

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
    $nameConnexionDeconnexion = "connexion";

    if ($utilisateur != false) {
        $nomUtilisateur = $utilisateur[0]->pseudo;
        $nameConnexionDeconnexion = "deconnexion";
        $boutonTexte = 'Déconnexion';
        $boutonParametre = '<button class="btn"><a href="./profil.php?id=' . $utilisateur[0]->idUtilisateur . '">Compte</a></button>';
    } else {
        // Pas connecté, donc redirection à la page de connection
        header('Location: identification.php');
        exit;
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

    if (isset($_POST['modifierCompte'])) {
        $idModify = $utilisateur[0]->idUtilisateur;
        $idModify = intval($idModify);

        $nom = filter_input(INPUT_POST, 'nom');
        if ($nom == false || $nom == "") {
            $erreurNom = ERREUR;
        }

        $prenom = filter_input(INPUT_POST, 'prenom');
        if ($prenom == false || $prenom == "") {
            $erreurPrenom = ERREUR;
        }

        $pseudo = filter_input(INPUT_POST, 'pseudo');
        if ($pseudo == false || $pseudo == "" || VerifiePseudoSimilaire($pseudo) == $pseudo) {
            $erreurPseudo = ERREUR;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        if ($email == false || $email == "" || VerifieEmailSimilaire($email) == $email) {
            $erreurEmail = ERREUR;
        }

        if ($idModify > 0 && $erreurNom != ERREUR && $erreurPrenom != ERREUR && $erreurPseudo != ERREUR && $erreurEmail != ERREUR) {
            if (modifierUtilisateur($idModify, $nom, $prenom, $pseudo, $email)) {
                header('Location: profil.php');
                exit;
            }
        } else {
            echo '<script>alert("Pas possible il vous manque des valeurs ou des valeurs existent déjà chez d\'autre compte")</script>';
        }
    }

    $enregistrements = RecuperationDonneeUtilisateur($utilisateur[0]->idUtilisateur);
    if ($enregistrements === false) {
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
            <?php foreach ($enregistrements as $utilisateur) {

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