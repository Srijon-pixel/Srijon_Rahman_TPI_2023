<?php

/**
 * Auteur: Mofassel Haque Srijon Rahman
 * Date: 27.04.2023
 * Projet: TPI video game club
 * Détail: Page affichant les données de l'utilisateur
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
    <title>Profil</title>
</head>

<body>
    <?php

    //Permet d'utiliser les fonctions du fichier 
    require_once $_SERVER['DOCUMENT_ROOT'] . '/fonctions/fonction_utilisateur.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/fonctions/fonction_session.php';

    $utilisateur = RecupereUtilisateurParSession(); //Récupère les données de l'utilisateur s'il est connecté
    $nomUtilisateur = 'invité';
    $boutonDirection = '/identification.php';
    $boutonTexte = 'Connexion';
    $boutonParametre = '';
    $nomConnexionDeconnexion = "connexion";

    //S'il est connecté
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
        <form action="#" method="POST">

            <?php
            //Parcours le tableau comportant les donnée de l'utilisateur connecté et les affiches
            foreach ($donneesUtilisateur as $utilisateur) {

                echo "<label for=\"nom\">Votre nom :</label><br>";
                echo "<input type=\"text\" name=\"nom\" value=\"" . $utilisateur->nom . "\" readonly><br>";

                echo "<label for=\"prenom\">Votre prénom :</label><br>";
                echo "<input type=\"text\" name=\"prenom\" value=\"" . $utilisateur->prenom . "\" readonly><br>";

                echo "<label for=\"pseudo\">Votre pseudo :</label><br>";
                echo "<input type=\"text\" name=\"pseudo\" value=\"" . $utilisateur->pseudo . "\" readonly><br>";

                echo "<label for=\"email\">Votre email : </label><br>";
                echo "<input type=\"email\" name=\"email\" value=\"" . $utilisateur->email . "\" readonly><br>";

                echo "<label for=\"statut\" >Votre statut : </label><br>";
                if ($utilisateur->statut == 0) {
                    echo "<input type=\"text\" name=\"statut\" value=\"simple utilisateur\" readonly><br>";
                } else {
                    echo "<input type=\"text\" name=\"statut\" value=\"administrateur\" readonly><br>";
                };
            }
            ?>
            <a href="./modifierMotDePasse.php" class="btn btn-primary">Modifier le mot de passe</a>
            <a href="./modifierCompte.php" class="btn btn-primary">Modifier le compte</a>
        </form>

    </main>
    <footer>
        &copy;Fait par Mofassel Haque Srijon Rahman <br>
        Contact : srijon.rhmn@eduge.ch
    </footer>

</body>

</html>