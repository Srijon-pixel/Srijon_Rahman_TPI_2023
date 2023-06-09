<?php

/**
 * Auteur: Mofassel Haque Srijon Rahman
 * Date: 27.04.2023
 * Projet: TPI video game club
 * Détail: Page d'accueil du site
 */
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/jeu.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <title>Accueil</title>
</head>

<body>
    <?php

    //Permet d'utiliser les fonctions du fichier 
    require_once $_SERVER['DOCUMENT_ROOT'] . '/fonctions/fonction_utilisateur.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/fonctions/fonction_session.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/fonctions/fonction_jeuVideo.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/fonctions/fonction_notation.php';

    //Constantes
    const DETAIL_BUTTON = "Détail";
    const COULEUR_MESSAGE_ERREUR = "red";

    //Variables
    $titreCle = "";
    $plateforme = array();
    $genre = array();
    $plateformes = "";
    $genres = "";
    $ageMin = 0;
    $ageMax = 0;

    $erreurAgeMin = "";
    $erreurAgeMax = "";

    $coche = "";
    $idDetailJeu = -1;


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


    
    if (isset($_POST['DET'])) {
        $idDetailJeu = intval(filter_input(INPUT_POST, "jeu"));
        $_SESSION['idJeu'] = $idDetailJeu;
        header('Location: detailJeu.php');
    }


    if (isset($_POST["rechercher"])) {

        //Filtarge + Traitement des données
        $titreCle = filter_input(INPUT_POST, "titreCle");
        $titreCle = antiInjectionXSS($titreCle);
        $ageMin = filter_input(INPUT_POST, "ageMin", FILTER_SANITIZE_NUMBER_INT);
        $ageMax = filter_input(INPUT_POST, "ageMax", FILTER_SANITIZE_NUMBER_INT);

        if ($ageMin <= -1 || $ageMin >= 19) {
            $erreurAgeMin = COULEUR_MESSAGE_ERREUR;
        }
        if ($ageMax <= -1 || $ageMax >= 19) {
            $erreurAgeMax = COULEUR_MESSAGE_ERREUR;
        }

        // Récupère les valeurs sélectionnées pour la catégorie genre
        if (isset($_POST['genre'])) {
            $genre = $_POST['genre'];
            $genres = implode(",", $genre); // Met toutes les valeurs dans une chaîne de caractères séparée par une virgule
        }

        // Récupère les valeurs sélectionnées pour la catégorie plateforme
        if (isset($_POST['plateforme'])) {
            $plateforme = $_POST['plateforme'];
            $plateformes = implode(",", $plateforme);  // Met toutes les valeurs dans une chaîne de caractères séparée par une virgule
        }

        if ($erreurAgeMin != COULEUR_MESSAGE_ERREUR && $erreurAgeMax != COULEUR_MESSAGE_ERREUR) {
            $registreJeu = RechercherJeu($titreCle, $genres, $plateformes, $ageMin, $ageMax);
            if ($registreJeu === false) {
                echo '<script>alert("Le jeu ne peut être affichée. Une erreur s\'est produite.")</script>';
            }
        } else {

            echo '<script>alert("Veuillez remplire les champs correctement s\'il vous plaît")</script>';
            $registreJeu = RecupereJeuxVideoPublie();
            if ($registreJeu === false) {
                echo '<script>alert("Les données des jeux ne peuvent être affichées. Une erreur s\'est produite.")</script>';
            }
        }
    } else {

        $registreJeu = RecupereJeuxVideoPublie();
        if ($registreJeu === false) {
            echo '<script>alert("Les données des jeux ne peuvent être affichées. Une erreur s\'est produite.")</script>';
        }
    }

    //Récupération des données sur les genres et les plateformes
    $registreGenre = RecupereGenre();
    if ($registreGenre === false) {
        echo '<script>alert("Les genres de jeu vidéo ne peuvent être affichées. Une erreur s\'est produite.")</script>';
    }
    $registrePlateforme = RecuperePlateforme();
    if ($registrePlateforme === false) {
        echo '<script>alert("Les plateformes des jeux vidéo ne peuvent être affichées. Une erreur s\'est produite.")</script>';
    }

    
    $nombreJeux = RecupereNombreJeux();
    $nombreJeux = $nombreJeux[0][0];
    $nombreJeuxRecherche = count(RechercherJeu($titreCle, $genres, $plateformes, $ageMin, $ageMax));
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
        <aside>
            <p>Nombre de jeux vidéo: <?= $nombreJeux; ?></p><br>
            <p>Nombre de jeux vidéo trouvé: <?= $nombreJeuxRecherche; ?> </p><br>

            <form action="" method="POST">
                <label for="ageMin" style="color:<?= $erreurAgeMin; ?>">Âge minimum: </label><br>
                <input type="number" name="ageMin" min="0" max="18" value="<?= $ageMin ?>"><br>
                <label for="ageMax" style="color:<?= $erreurAgeMax; ?>">Âge maximum: </label><br>
                <input type="number" name="ageMax" min="0" max="18" value="<?= $ageMax ?>">
                <p>Plateformes:</p>
                <?php
                //Parcours le tableau et affiche c'est données.
                //Coche une de ses données si déjà séléctionné auparavant
                foreach ($registrePlateforme as $plateforme) {
                    if (isset($_POST["plateforme"]) && in_array($plateforme->nomPlateforme, $_POST["plateforme"])) {
                        $coche = "checked";
                    } else {
                        $coche = "";
                    }
                    echo "<input type=\"checkbox\" name=\"plateforme[]\" value=\"$plateforme->nomPlateforme\" $coche>$plateforme->nomPlateforme<br>";
                }
                ?>

                <p>Genres:</p>
                <?php
                //Parcours le tableau et affiche c'est données.
                //Coche une de ses données si déjà séléctionné auparavant

                foreach ($registreGenre as $genre) {
                    if (isset($_POST["genre"]) && in_array($genre->nomGenre, $_POST["genre"])) {
                        $coche = "checked";
                    } else {
                        $coche = "";
                    }
                    echo "<input type=\"checkbox\" name=\"genre[]\" value=\"$genre->nomGenre\" $coche>$genre->nomGenre<br>";
                }
                ?>

                <div class="search-container">

                    <label for="titreCle"> Rechercher</label><br>
                    <input type="text" placeholder="Rechercher..." name="titreCle" value="<?= $titreCle ?>">

                </div><br>
                <input type="submit" name="rechercher" class="btn btn-primary" value="Rechercher"><br>
                <button class="btn btn-warning"><a href="./index.php">Réinitialiser la recherche</a></button>

            </form>
        </aside>

        <div class="main">
            <?php
            foreach ($registreJeu as $jeu) {
                $tableauNoteJeu = RecupereNoteJeuParId($jeu->idJeuVideo);
                $note = $tableauNoteJeu[0]->note;
                echo "<div class=\"card\">";
                echo "<div class=\"container\">";
                echo "<h3><b>$jeu->titre</b></h3>";
                echo "<p> Genres: $jeu->genre</p>";
                echo "<p> Âge PEGI: $jeu->trancheAge</p>";
                echo "<p> Contenu sensible: $jeu->contenuSensible</p>";
                echo "<p>Plateformes: $jeu->plateforme</p> ";
                echo "<p>Date de sortie: $jeu->dateSortie</p>";
                echo "<p>Note: $note</p>";
                echo '<form action="" method="post"><input type="hidden" name="jeu" value="' . $jeu->idJeuVideo . '">';
                echo '<input type="submit" name="DET" class="btn btn-info" value="' . DETAIL_BUTTON . '"></form>';
                echo "</div></div>";
            }

            ?>
        </div>
    </main>
    <footer>
        &copy;Fait par Mofassel Haque Srijon Rahman <br>
        Contact : srijon.rhmn@eduge.ch
    </footer>

</body>

</html>