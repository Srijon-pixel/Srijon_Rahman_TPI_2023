<?php
/**
* Auteur: Mofassel Haque Srijon Rahman
* Date: 27.04.2023
* Projet: TPI video game club
* Détail: Page affichant les données d'un jeu vidéo
*/
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/jeu.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <title>Détail du jeu vidéo</title>
</head>

<body>
    <?php

    //Permet d'utiliser les fonctions du fichier 
    require_once './fonctions/fonction_utilisateur.php';
    require_once './fonctions/fonction_session.php';
    require_once './fonctions/fonction_jeuVideo.php';
    require_once './fonctions/fonction_commentaire.php';
    require_once './fonctions/fonction_notation.php';

    if (!DebutSession()) {
        // Pas de session, donc redirection à l'acceuil
        header('Location: /index.php');
        exit;
    }
    //Variables
    const COULEUR_MESSAGE_ERREUR = "red";

    $idJeu = $_SESSION['idJeu'];
    $note = 0;
    $commentaire = "";
    $dateCommentaire = "";
    $erreurNote = "";
    $erreurCommentaire = "";

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

    if (isset($_POST['poster'])) {

        $dateCommentaire = date("Y-m-d");
        $commentaire = filter_input(INPUT_POST, "commentaire");
        $commentaire = antiInjectionXSS($commentaire);

        if ($commentaire == false || $commentaire == "") {
            $erreurCommentaire = COULEUR_MESSAGE_ERREUR;
            echo '<script>alert("Veuillez ajouter votre commentaire correctement s\'il vous plaît")</script>';
        } else {
            if (AjouterCommentaire($commentaire, $dateCommentaire, $utilisateur[0]->idUtilisateur, $idJeu)) {
                header('Location: detailJeu.php');
                exit;
            } else {
                echo '<script>alert("Votre commentaire n\'a pas pu être poster. Une erreur s\'est produite")</script>';
            }
        }
    }

    $jeuVideoSession = RecupereJeuParSession();

    if ($jeuVideoSession === false) {
        // Pas de jeu vidéo, donc redirection à la page d'accueil
        header('Location: index.php');
        exit;
    }

    $donneesJeu = RecupereJeuVideoParId($idJeu);
    if ($donneesJeu === false) {
        echo "Les casquettes ne peuvent être affichées. Une erreur s'est produite.";
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
        <form action="" method="post">
            <label for="commentaire" style="color:<?= $erreurCommentaire; ?>">Poster un commentaire ? : </label><br>
            <textarea name="commentaire" id="" cols="30" rows="10"></textarea>
            <input type="submit" name="poster" value="Poster" value="<?= $commentaire; ?>">
        </form><br>
        <form action="" method="post">
            <label for="note" style="color:<?= $erreurNote; ?>">La note selon vous ? : </label><br>
            <input type="number" name="note" min="0" max="10" value="<?= $note; ?>">
            <input type="submit" name="attribuer" value="Attribuer une note">
        </form><br>
        <?php
        foreach ($donneesJeu as $jeu) {
            echo "<div class=\"card\">";
            echo "<div class=\"container\">";
            echo "<h3><b>$jeu->titre</b></h3>";

            //Si le jeu ne possède pas d'image, 
            //alors on affiche une image par défaut
            if ($jeu->imageEncode != null) {
                echo "<img src=\"$jeu->imageEncode\" alt=\"$jeu->titre\">";
            } else {
                echo "<img src=\"./img/imageParDefaut.png\" alt=\"défaut\">";
            }
            if ($jeu->version != null) {
                echo "<p>Version: $jeu->version</p>";
            } 
            echo "<p>Description: $jeu->description</p>";
            echo "<p> Genres: $jeu->genre</p>";
            echo "<p> Âge PEGI: $jeu->trancheAge</p>";
            echo "<p> Contenu sensible:$jeu->contenuSensible</p>";
            echo "<p>Plateformes: $jeu->plateforme</p> ";
            echo "<p>Date de sortie: $jeu->dateSortie</p>";
            echo "<p>Note: $jeu->note</p>";
            echo "</div></div>";
        }
        ?>
    </main>
    <footer>
        &copy;Fait par Mofassel Haque Srijon Rahman <br>
        Contact : srijon.rhmn@eduge.ch
    </footer>

</body>

</html>