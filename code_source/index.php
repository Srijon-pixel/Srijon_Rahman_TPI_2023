<!DOCTYPE html>
<html lang="en">
<!--
    Auteur: Mofassel Haque Srijon Rahman
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
    <title>Accueil</title>
</head>

<body>
    <?php

    //Permet d'utiliser les fonctions du fichier 
    require_once './fonctions/fonction_utilisateur.php';
    require_once './fonctions/fonction_session.php';
    require_once './fonctions/fonction_jeuVideo.php';

    $motCle = "";
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
    $registreJeu = RecupereToutLesJeuxVideo();
    if ($registreJeu === false) {
        echo "Les données de l'utilisateur ne peuvent être affichées. Une erreur s'est produite.";
        exit;
    }
    $resultatRecherche = RechercherJeu($motCle);
    if ($resultatRecherche === false) {
        echo "La casquette ne peut être affichée. Une erreur s'est produite.";
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
                            <input type="submit" name="<?= $nomConnexionDeconnexion ?>" class="btn btn-primary" value="<?= $boutonTexte ?>">
                        </form>
                    </div>
                </div>
            </div>
        </nav>

    </header>
    <main>
        <p>Nombre de jeux vidéo: </p>

        <form action="" method="POST">
            <label for="ageMin">Âge minimum: </label><br>
            <input type="number" name="ageMin" min="3" max="18"><br>
            <label for="ageMax">Âge maximum: </label><br>
            <input type="number" name="ageMax" min="3" max="18">
            <p>Plateformes:</p>
            <input type="checkbox">
            <input type="checkbox">
            <input type="checkbox">
            <input type="checkbox">
            <input type="checkbox">
            <input type="checkbox">
            <p>Genres:</p>
            <input type="checkbox">
            <input type="checkbox">
            <input type="checkbox">
            <input type="checkbox">
            <input type="checkbox">
            <input type="checkbox">
            <input type="checkbox">
            <input type="checkbox">
            <input type="checkbox">
            <input type="checkbox">
            <input type="checkbox">
            <div class="search-container">

                <label for="motCle"> Rechercher</label><br>
                <input type="text" placeholder="Rechercher..." name="motCle" value="<?php echo $motCle ?>">

            </div><br>
            <input type="submit" name="rechercher" value="Rechercher">

        </form>
        <?php
        if (!isset($_POST['rechercher'])) {
            foreach ($registreJeu as $jeu) {
                echo "<div class=\"card\">";
                echo "<div class=\"container\">";
                echo "<h3><b>";
                echo $jeu->titre;
                echo '</b></h3>';
                echo "<p>";
                echo $jeu->genre;
                echo '</p>';
                echo "<p>";
                echo $jeu->trancheAge;
                echo '</p>';
                echo "<p>";
                echo $jeu->contenuSensible;
                echo '</p>';
                echo "<p>";
                echo $jeu->plateforme;
                echo '</p>';
                echo "<p>";
                echo $jeu->dateSortie;
                echo '</p>';
                echo "</div>";
                echo "</div>";
            }
        } else {
            $motCle = filter_input(INPUT_POST, "motCle");
            antiInjectionXSS($motCle);
            asort($resultatRecherche);
            foreach ($resultatRecherche as $jeu) {
                echo "<div class=\"card\">";
                echo "<div class=\"container\">";
                echo "<h3><b>";
                echo $jeu->titre;
                echo '</b></h3>';
                echo "<p>";
                echo $jeu->genre;
                echo '</p>';
                echo "<p>";
                echo $jeu->trancheAge;
                echo '</p>';
                echo "<p>";
                echo $jeu->contenuSensible;
                echo '</p>';
                echo "<p>";
                echo $jeu->plateforme;
                echo '</p>';
                echo "<p>";
                echo $jeu->dateSortie;
                echo '</p>';
                echo "</div>";
                echo "</div>";
            }
        }

        ?>
    </main>
    <footer>
        &copy;Fait par Mofassel Haque Srijon Rahman <br>
        Contact : srijon.rhmn@eduge.ch
    </footer>

</body>

</html>