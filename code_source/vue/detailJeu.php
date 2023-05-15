<?php

/**
 * Auteur: Mofassel Haque Srijon Rahman
 * Date: 10.05.2023
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
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/jeu.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <title>Détail du jeu vidéo</title>
</head>

<body>
    <?php




    //Permet d'utiliser les fonctions du fichier 
    require_once $_SERVER['DOCUMENT_ROOT'] . '/fonctions/fonction_utilisateur.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/fonctions/fonction_session.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/fonctions/fonction_commentaire.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/fonctions/fonction_jeuVideo.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/fonctions/fonction_notation.php';

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
    $noteUtilisateur = 0;
    $erreurNote = "";
    $erreurCommentaire = "";

    $nomAttribuerModifier = "attribuer";
    $boutonNoteTexte = "Attribuer une note";

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
        $tableauNoteUtilisateur = RecupereNoteParIdUtilisateur($utilisateur[0]->idUtilisateur, $idJeu);
        if ($tableauNoteUtilisateur != false) {
            $noteUtilisateur = $tableauNoteUtilisateur[0]->note;
            if ($tableauNoteUtilisateur != false) {
                $nomAttribuerModifier = "modifier";
                $boutonNoteTexte = "Modifier la note";
            }
        } else {
            $noteUtilisateur = 0;
        }
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



    $jeuVideoSession = RecupereJeuParSession();
    if ($jeuVideoSession === false) {
        // Pas de jeu vidéo, donc redirection à la page d'accueil
        echo "<script>alert(\"Veuillez séléctionnez un jeu vidéo s'il vous plaît.\")</script>";
        header('Location: index.php');
        exit;
    }

    $tableauNoteJeu = RecupereNoteJeuParId($idJeu);
    if ($tableauNoteJeu === false) {
        echo "<script>alert(\"Une erreur s'est produite. La note du jeu vidéo ne peut être affichée.\")</script>";
        header('Location: index.php');
        exit;
    } else {
        $noteJeu = $tableauNoteJeu[0]->note;
        if ($noteJeu == null) {
            $nomAttribuerModifier = "attribuer";
            $boutonNoteTexte = "Attribuer une note";
            $noteJeu = "non attribuée.";
        }
        $nombrePersonneNote = $tableauNoteJeu[0]->nombrePersonneNote;
    }


    $donneesJeu = RecupereJeuVideoParId($idJeu);
    if ($donneesJeu === false) {
        echo "<script>alert(\"Une erreur s'est produite. Les données du jeu vidéo ne peuvent être affichées.\")</script>";
        header('Location: index.php');
        exit;
    }

    $listeCommentaire = RecupereCommentaireJeuParId($idJeu);
    if ($listeCommentaire === false) {
        echo "<script>alert(\"Une erreur s'est produite. Les commentaire du jeu ne peuvent être affichées.\")</script>";
        header('Location: index.php');
        exit;
    }

    //Ajout d'un commentaire sur un jeu vidéo
    if (isset($_POST['poster'])) {

        $dateCommentaire = date("Y-m-d, h:i:s");
        $commentaire = filter_input(INPUT_POST, "commentaire");
        $commentaire = antiInjectionXSS($commentaire);

        if ($commentaire == "" || preg_match('/[a-zA-Z]/', $commentaire) == false) {
            echo '<script>alert("Veuillez ajouter votre commentaire correctement s\'il vous plaît")</script>';
            $erreurCommentaire = COULEUR_MESSAGE_ERREUR;
        } else {
            if (AjouterCommentaire($commentaire, $dateCommentaire, $utilisateur[0]->idUtilisateur, $idJeu)) {
                header('Location: detailJeu.php');
                exit;
            } else {
                echo '<script>alert("Une erreur s\'est produite. Votre commentaire n\'a pas pu être poster.")</script>';
            }
        }
    }


    //Traitement d'ajout ou de modification de  la note d'un jeu vidéo
    if (isset($_POST[$nomAttribuerModifier])) {
        $note = filter_input(INPUT_POST, "note", FILTER_SANITIZE_NUMBER_INT);
        $note  = antiInjectionXSS($note);
        if ($note <= -1 || preg_match('/[a-zA-Z]/', $note)) {
            $erreurNote = COULEUR_MESSAGE_ERREUR;
            echo '<script>alert("Veuillez ajouter votre note correctement s\'il vous plaît.")</script>';
        } else {
            if ($nomAttribuerModifier == "attribuer") {
                if (AjouterNote($note, $utilisateur[0]->idUtilisateur, $idJeu)) {
                    header('Location: detailJeu.php');
                    exit;
                } else {
                    echo '<script>alert(" Une erreur s\'est produite. Votre note n\'a pas pu être attribuer.")</script>';
                }
            } else if ($nomAttribuerModifier == "modifier") {
                if (modifierNote($tableauNoteUtilisateur[0]->idNotation, $note)) {
                    header('Location: detailJeu.php');
                    exit;
                } else {
                    echo '<script>alert(" Une erreur s\'est produite. Votre note n\'a pas pu être modifier.")</script>';
                }
            }
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
        <?php if ($utilisateur) { ?>
            <aside>
                <form action="" method="post">
                    <label for="commentaire" style="color:<?= $erreurCommentaire; ?>">Poster un commentaire ? : </label><br>
                    <textarea name="commentaire" cols="30" rows="10" value="<?= $commentaire; ?>"></textarea>
                    <input type="submit" name="poster" value="Poster">
                </form><br>
                <form action="" method="post">
                    <label for="note" style="color:<?= $erreurNote; ?>">La note selon vous ? : </label><br>
                    <input type="number" name="note" min="0" max="10" value="<?= $noteUtilisateur ?>">
                    <input type="submit" name="<?= $nomAttribuerModifier ?>" value="<?= $boutonNoteTexte ?>">
                </form><br>
            <?php } ?>
            <p>Les commentaires:</p>
            <?php
            foreach ($listeCommentaire as $commentaire) {
                echo "<div class=\"card\">";
                echo "<div class=\"container\">";
                echo "<p>Date et heure: $commentaire->dateCommentaire</p>";
                if ($utilisateur) {
                    echo "<p>Par: $commentaire->pseudoUtilisateur</p>";
                } else {
                    echo "<p>Anonyme: </p>";
                }
                echo "<p>$commentaire->commentaire</p>";
                echo "</div></div>";
            }
            ?>
            </aside>



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
                    echo "<img src=\"../img/imageParDefaut.png\" alt=\"défaut\">";
                }
                if ($jeu->version != null) {
                    echo "<p>Version: $jeu->version</p>";
                }
                echo "<p>Description: $jeu->description</p>";
                echo "<p> Genres: $jeu->genre</p>";
                echo "<p> Âge PEGI: $jeu->trancheAge</p>";
                echo "<p> Contenu sensible: $jeu->contenuSensible</p>";
                echo "<p>Plateformes: $jeu->plateforme</p> ";
                echo "<p>Date de sortie: $jeu->dateSortie</p>";
                echo "<p>Note: $noteJeu</p>";
                echo "<p>Nombre de personne ayant noter le jeu: $nombrePersonneNote</p>";
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