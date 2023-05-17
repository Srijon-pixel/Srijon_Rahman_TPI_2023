<?php

/**
 * Auteur: Mofassel Haque Srijon Rahman
 * Date: 11.05.2023
 * Projet: TPI video game club
 * Détail: Page permettant à l'utilisateur de proposer un jeu à afficher dans le site et à l'administrateur de valider ou modifier la proposition
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
    <title>Éditer un jeu vidéo</title>
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

    //Constante
    const COULEUR_MESSAGE_ERREUR = "red";

    //Variables
    $titre = "";
    $version = "";
    $imageNom = "";
    $description = "";
    $dateSortie = "";
    $datePublication = "";
    $listeTranchesAge = array(3, 7, 12, 16, 18);
    $contenuSensibleChoisies = array();
    $plateformesChoisies = array();
    $genreChoisies = array();
    $trancheAge = array();

    $erreurTitre = "";
    $erreurVersion = "";
    $erreurImageNom = "";
    $erreurDescription = "";
    $erreurDateSortie = "";
    $erreurDatePublication = "";
    $erreurTrancheAge = "";
    $erreurContenuSensible = "";
    $erreurPlateforme = "";
    $erreurGenre = "";

    $utilisateur = RecupereUtilisateurParSession(); //Récupère les données de l'utilisateur s'il est connecté
    $nomUtilisateur = 'invité';
    $boutonDirection = '/identification.php';
    $boutonTexte = 'Connexion';
    $boutonParametre = '';
    $nomConnexionDeconnexion = "connexion";

    $donneeJeuProposer = RecupereJeuVideoProposer();
    if ($donneeJeuProposer) {
        $idJeuProposer = $donneeJeuProposer[0]->idJeuVideo;
    }

    //S'il est connecté
    if ($utilisateur != false) {
        $nomUtilisateur = $utilisateur[0]->pseudo;
        $nomConnexionDeconnexion = "deconnexion";
        $boutonTexte = 'Déconnexion';
        $boutonParametre = '<button class="btn" btn-link><a href="./profil.php?id=' . $utilisateur[0]->idUtilisateur . '">Compte</a></button>';
    } else { //Sinon
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

    //Récupération des données des genres, des plateformes et des contenu sensibles
    $registreGenre = RecupereGenre();
    if ($registreGenre === false) {
        echo '<script>alert("Les genres de jeu vidéo ne peuvent être affichées. Une erreur s\'est produite.")</script>';
    }
    $registrePlateforme = RecuperePlateforme();
    if ($registrePlateforme === false) {
        echo '<script>alert("Les plateformes des jeux vidéo ne peuvent être affichées. Une erreur s\'est produite.")</script>';
    }
    $registreContenuSensible = RecupereContenuSensible();
    if ($registreContenuSensible === false) {
        echo '<script>alert("Les contenus sensibles des jeux vidéo ne peuvent être affichées. Une erreur s\'est produite.")</script>';
    }


    if (isset($_POST["proposer"])) {
        //Filtarge + Traitement des données
        $imageNom = $_FILES['imageEncode']['name'];
        $titre = filter_input(INPUT_POST, 'titre');
        $titre = antiInjectionXSS($titre);
        if ($titre == "" || preg_match('/[a-zA-Z]/', $titre) == false) {
            echo '<script>alert("Veuillez écrire le titre de votre jeu.")</script>';
            $erreurTitre = COULEUR_MESSAGE_ERREUR;
        }

        $version = filter_input(INPUT_POST, 'version', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $version = antiInjectionXSS($version);
        if ($version == null || preg_match('/[a-zA-Z]/', $version)) {
            echo '<script>alert("Veuillez écrire la version du jeu.")</script>';
            $erreurVersion = COULEUR_MESSAGE_ERREUR;
        }

        $dateSortie = filter_input(INPUT_POST, 'dateSortie');
        $dateSortie = antiInjectionXSS($dateSortie);
        if ($dateSortie == "") {
            echo '<script>alert("Veuillez séléctionner la date de sortie du jeu vidéo.")</script>';
            $erreurDateSortie = COULEUR_MESSAGE_ERREUR;
        }

        if (isset($_POST['plateforme'])) {
            $plateformesChoisies = $_POST['plateforme'];
        } else {
            echo "<script>alert(\"Veuillez cocher au moins une plateforme.\")</script>";
            $erreurPlateforme = COULEUR_MESSAGE_ERREUR;
        }

        //Si l'image existe et qu'il a été téléverser
        if (
            !isset($_FILES['imageEncode']) || !is_uploaded_file($_FILES['imageEncode']['tmp_name'])
            || $_FILES['imageEncode']['tmp_name'] == ""
        ) {
            $erreurImageNom = COULEUR_MESSAGE_ERREUR;
            echo "<script>alert(\"Problème de transfert de l'image\")</script>";
        } else if (filesize($_FILES['imageEncode']['tmp_name']) > 1000000) {
            $erreurImageNom = COULEUR_MESSAGE_ERREUR;
            echo "<script>alert(\"Fichier trop volumineux, choisissez un autre\")</script>";
        }

        if (isset($_POST['genre'])) {
            $genreChoisies = $_POST['genre'];
        } else {
            echo "<script>alert(\"Veuillez cocher au moins un genre.\")</script>";
            $erreurGenre = COULEUR_MESSAGE_ERREUR;
        }

        if (isset($_POST['contenuSensible'])) {
            $contenuSensibleChoisies = $_POST['contenuSensible'];
        } else {
            echo "<script>alert(\"Veuillez cocher au moins un contenu sensible.\")</script>";
            $erreurContenuSensible = COULEUR_MESSAGE_ERREUR;
        }

        if (isset($_POST['trancheAge'])) {
            $trancheAge = $_POST['trancheAge'];
        } else {
            echo "<script>alert(\"Veuillez séléctionner la tranche d'âge.\")</script>";
            $erreurTrancheAge = COULEUR_MESSAGE_ERREUR;
        }

        $description = filter_input(INPUT_POST, 'description');
        $description = antiInjectionXSS($description);
        if ($description == "" || preg_match('/[a-zA-Z]/', $description) == false) {
            echo '<script>alert("Veuillez écrire la description du jeu.")</script>';
            $erreurDescription = COULEUR_MESSAGE_ERREUR;
        }
        //S'il n'y a pas eu d'erreur alors on ajoute le jeu dans la base de donnée
        if (
            $erreurTitre != COULEUR_MESSAGE_ERREUR && $erreurVersion != COULEUR_MESSAGE_ERREUR
            && $erreurDescription != COULEUR_MESSAGE_ERREUR && $erreurDateSortie != COULEUR_MESSAGE_ERREUR
            && $erreurTrancheAge != COULEUR_MESSAGE_ERREUR && $erreurContenuSensible != COULEUR_MESSAGE_ERREUR
            && $erreurGenre != COULEUR_MESSAGE_ERREUR && $erreurPlateforme != COULEUR_MESSAGE_ERREUR
        ) {
            $idJeuVideo = AjouterJeu(
                $titre,
                $version,
                $dateSortie,
                file_get_contents($_FILES['imageEncode']['tmp_name']),
                $_FILES['imageEncode']['type'],
                $description,
                $trancheAge
            );
            $idJeuVideo = intval($idJeuVideo);
            if (AjouterJeuAvecLiaisons(
                $idJeuVideo,
                $contenuSensibleChoisies,
                $genreChoisies,
                $plateformesChoisies
            )) {
                $_SESSION["idJeuProposer"] = $idJeuVideo;
                header("location: index.php");
                exit;
            } else {
                echo '<script>alert("Jeu impossible à ajouter. Une erreur s\'est produite.")</script>';
            }
        }
    }

    if (isset($_POST["modifier"])) {
        //Filtarge + traitements des données
        $imageNom = $_FILES['imageEncode']['name'];
        $titre = filter_input(INPUT_POST, 'titre');
        $titre = antiInjectionXSS($titre);
        if ($titre == "" || preg_match('/[a-zA-Z]/', $titre) == false) {
            echo '<script>alert("Veuillez écrire le titre de votre jeu.")</script>';
            $erreurTitre = COULEUR_MESSAGE_ERREUR;
        }

        $version = filter_input(INPUT_POST, 'version');
        $version = antiInjectionXSS($version);
        if ($version == "" || preg_match('/[a-zA-Z]/', $version)) {
            echo '<script>alert("Veuillez écrire la version du jeu.")</script>';
            $erreurVersion = COULEUR_MESSAGE_ERREUR;
        }

        $dateSortie = filter_input(INPUT_POST, 'dateSortie');
        $dateSortie = antiInjectionXSS($dateSortie);
        if ($dateSortie == "") {
            echo '<script>alert("Veuillez séléctionner la date de sortie du jeu vidéo.")</script>';
            $erreurDateSortie = COULEUR_MESSAGE_ERREUR;
        }
        $datePublication = filter_input(INPUT_POST, 'datePublication');
        $datePublication = antiInjectionXSS($datePublication);
        if ($datePublication == "") {
            echo '<script>alert("Veuillez séléctionner la date de publication du jeu vidéo.")</script>';
            $erreurDatePublication = COULEUR_MESSAGE_ERREUR;
        }

        if (isset($_POST['plateforme'])) {
            $plateformesChoisies = $_POST['plateforme'];
        } else {
            echo "<script>alert(\"Veuillez cocher au moins une plateforme.\")</script>";
            $erreurPlateforme = COULEUR_MESSAGE_ERREUR;
        }

        //Si l'image existe et qu'il a été téléverser
        if (
            !isset($_FILES['imageEncode']) || !is_uploaded_file($_FILES['imageEncode']['tmp_name'])
            || $_FILES['imageEncode']['tmp_name'] == ""
        ) {
            $erreurImageNom = COULEUR_MESSAGE_ERREUR;
            echo "<script>alert(\"Problème de transfert de l'image\")</script>";
        } else if (filesize($_FILES['imageEncode']['tmp_name']) > 1000000) {
            $erreurImageNom = COULEUR_MESSAGE_ERREUR;
            echo "<script>alert(\"Fichier trop volumineux, choisissez un autre\")</script>";
        }

        if (isset($_POST['genre'])) {
            $genreChoisies = $_POST['genre'];
        } else {
            echo "<script>alert(\"Veuillez cocher au moins un genre.\")</script>";
            $erreurGenre = COULEUR_MESSAGE_ERREUR;
        }

        if (isset($_POST['contenuSensible'])) {
            $contenuSensibleChoisies = $_POST['contenuSensible'];
        } else {
            echo "<script>alert(\"Veuillez cocher au moins un contenu sensible.\")</script>";
            $erreurContenuSensible = COULEUR_MESSAGE_ERREUR;
        }

        if (isset($_POST['trancheAge'])) {
            $trancheAge = $_POST['trancheAge'];
        } else {
            echo "<script>alert(\"Veuillez séléctionner la tranche d'âge.\")</script>";
            $erreurTrancheAge = COULEUR_MESSAGE_ERREUR;
        }

        $description = filter_input(INPUT_POST, 'description');
        $description = antiInjectionXSS($description);
        if ($description == "" || preg_match('/[a-zA-Z]/', $description) == false) {
            echo '<script>alert("Veuillez écrire la description du jeu.")</script>';
            $erreurDescription = COULEUR_MESSAGE_ERREUR;
        }

        //S'il n'y a pas eu d'erreur alors on modife les données du jeu dans la base de donnée
        // et le rend affichable dans la page d'accueil
        if (
            $erreurTitre != COULEUR_MESSAGE_ERREUR && $erreurVersion != COULEUR_MESSAGE_ERREUR
            && $erreurDescription != COULEUR_MESSAGE_ERREUR && $erreurDateSortie != COULEUR_MESSAGE_ERREUR
            && $erreurTrancheAge != COULEUR_MESSAGE_ERREUR && $erreurContenuSensible != COULEUR_MESSAGE_ERREUR
            && $erreurGenre != COULEUR_MESSAGE_ERREUR && $erreurPlateforme != COULEUR_MESSAGE_ERREUR
        ) {
            $idJeuVideo = ModifierJeu(
                $idJeuProposer,
                $titre,
                $version,
                $dateSortie,
                $datePublication,
                file_get_contents($_FILES['imageEncode']['tmp_name']),
                $_FILES['imageEncode']['type'],
                $description,
                $trancheAge
            );
            $idJeuVideo = intval($idJeuVideo);
            if (ModifierJeuAvecLiaisons(
                $idJeuProposer,
                $contenuSensibleChoisies,
                $genreChoisies,
                $plateformesChoisies
            )) {
                header("location: index.php");
                exit;
            } else {
                echo "<script>alert(\"Jeu non modifiable. Une erreur s'est produite\")</script>";
            }
        }
    }

    if (isset($_POST["supprimer"])) {
        //Supprime le jeu de la base de donnée
        if (SupprimerJeu($idJeuProposer)) {
            header("location: index.php");
            exit;
        } else {
            echo "<script>alert(\"Jeu non supprimable. Une erreur s'est produite\")</script>";
        }
    }

    if (isset($_POST["valider"])) {
        //Rend le jeu affichable dans la page d'accueil
        if (ValiderJeu($idJeuProposer)) {
            header("location: index.php");
            exit;
        } else {
            echo "<script>alert(\"Jeu non validable. Une erreur s'est produite\")</script>";
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

        <?php if ($utilisateur[0]->statut == 0 || $donneeJeuProposer == false) { ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <label for="titre" style="color:<?= $erreurTitre; ?>">Titre:</label><br>
                <input type="text" name="titre" value="<?= $titre; ?>"><br>

                <label for="version" style="color:<?= $erreurVersion; ?>">Version du jeu : </label><br>
                <input type="number" name="version" step="0.1" value="<?= $version; ?>"><br>

                <label for="dateSortie" style="color:<?= $erreurDateSortie; ?>">Date de sortie : </label><br>
                <input type="date" name="dateSortie" value="<?= $dateSortie; ?>"><br>

                <label for="imageEncode" style="color:<?= $erreurImageNom; ?>">Image</label><br>
                <input type="file" name="imageEncode" id="imageEncode" multiple accept="image/png, image/jpeg" value="<?= $imageNom ?>"> <br>

                <label for="plateforme" style="color:<?= $erreurPlateforme; ?>">Plateformes: </label><br>
                <?php
                //Parcours le tableau et affiche c'est données.
                //Coche une de ses données si déjà séléctionné auparavant
                foreach ($registrePlateforme as $plateforme) {
                    if (isset($_POST["plateforme"]) && in_array($plateforme->idPlateforme, $_POST["plateforme"])) {
                        $coche = "checked";
                    } else {
                        $coche = "";
                    }
                    echo "<input type=\"checkbox\" name=\"plateforme[]\" value=\"$plateforme->idPlateforme\" $coche>$plateforme->nomPlateforme<br>";
                }
                ?>

                <label for="genre" style="color:<?= $erreurGenre; ?>">Genres: </label><br>
                <?php
                //Parcours le tableau et affiche c'est données.
                //Coche une de ses données si déjà séléctionné auparavant
                foreach ($registreGenre as $genre) {
                    if (isset($_POST["genre"]) && in_array($genre->idGenre, $_POST["genre"])) {
                        $coche = "checked";
                    } else {
                        $coche = "";
                    }
                    echo "<input type=\"checkbox\" name=\"genre[]\" value=\"$genre->idGenre\" $coche>$genre->nomGenre<br>";
                }
                ?>
                <p>PEGI:</p>
                <label for="contenuSensible" style="color:<?= $erreurContenuSensible; ?>">Contenu sensible: </label><br>
                <?php
                //Parcours le tableau et affiche c'est données.
                //Coche une de ses données si déjà séléctionné auparavant
                foreach ($registreContenuSensible as $contenuSensible) {
                    if (isset($_POST["contenuSensible"]) && in_array($contenuSensible->idPegi, $_POST["contenuSensible"])) {
                        $coche = "checked";
                    } else {
                        $coche = "";
                    }
                    echo "<input type=\"checkbox\" name=\"contenuSensible[]\" value=\"$contenuSensible->idPegi\" $coche>$contenuSensible->contenuSensible<br>";
                }
                ?>

                <label for="trancheAge" style="color:<?= $erreurTrancheAge; ?>">Tranche d'âge: </label><br>
                <select name="trancheAge">
                    <?php
                    //Parcours le tableau et affiche c'est données.
                    //Coche une de ses données si déjà séléctionné auparavant
                    foreach ($listeTranchesAge as $tranchesAge) {

                        echo "<option value=\"" . $tranchesAge . "\"" . (isset($_POST['trancheAge']) && $_POST['trancheAge'] == $tranchesAge ? "selected" : false) . ">";
                        echo $tranchesAge;
                        echo '</option>';
                    }
                    ?>
                </select><br>

                <label for="description" style="color:<?= $erreurDescription; ?>">Description du jeu : </label><br>
                <textarea name="description" cols="30" rows="10"><?= $description ?></textarea>
                <input type="submit" name="proposer" class="btn btn-primary" value="Proposer"><br>

            </form>
            <?php
        } else {
            foreach ($donneeJeuProposer as $jeuProposer) { ?>
                <form action="" method="POST" enctype="multipart/form-data">
                    <label for="titre" style="color:<?= $erreurTitre; ?>">Titre:</label><br>
                    <input type="text" name="titre" value="<?= $jeuProposer->titre; ?>"><br>

                    <label for="version" style="color:<?= $erreurVersion; ?>">Version du jeu : </label><br>
                    <input type="number" name="version" step="0.1" value="<?= $jeuProposer->version; ?>"><br>

                    <label for="dateSortie" style="color:<?= $erreurDateSortie; ?>">Date de sortie : </label><br>
                    <input type="date" name="dateSortie" value="<?= $jeuProposer->dateSortie; ?>"><br>

                    <label for="datePublication" style="color:<?= $erreurDatePublication; ?>">Date de publication : </label><br>
                    <input type="date" name="datePublication" value="<?= $jeuProposer->datePublication; ?>"><br>

                    <label for="imageEncode" style="color:<?= $erreurImageNom; ?>">Image</label><br>
                    <input type="file" name="imageEncode" id="imageEncode" multiple accept="image/png, image/jpeg" value="<?= $jeuProposer->imageEncode ?>"> <br>

                    <label for="plateforme" style="color:<?= $erreurPlateforme; ?>">Plateformes: </label><br>
                    <?php
                    //Parcours le tableau et affiche c'est données.
                    //Coche une de ses données si déjà séléctionné auparavant
                    foreach ($registrePlateforme as $plateforme) {
                        if (isset($_POST["plateforme"]) && in_array($plateforme->idPlateforme, $_POST["plateforme"])) {
                            $coche = "checked";
                        } else {
                            $coche = "";
                        }
                        echo "<input type=\"checkbox\" name=\"plateforme[]\" value=\"$plateforme->idPlateforme\" $coche>$plateforme->nomPlateforme<br>";
                    }
                    ?>

                    <label for="genre" style="color:<?= $erreurGenre; ?>">Genres: </label><br>
                    <?php
                    //Parcours le tableau et affiche c'est données.
                    //Coche une de ses données si déjà séléctionné auparavant
                    foreach ($registreGenre as $genre) {
                        if (isset($_POST["genre"]) && in_array($genre->idGenre, $_POST["genre"])) {
                            $coche = "checked";
                        } else {
                            $coche = "";
                        }
                        echo "<input type=\"checkbox\" name=\"genre[]\" value=\"$genre->idGenre\" $coche>$genre->nomGenre<br>";
                    }
                    ?>
                    <p>PEGI:</p>
                    <label for="contenuSensible" style="color:<?= $erreurContenuSensible; ?>">Contenu sensible: </label><br>
                    <?php
                    //Parcours le tableau et affiche c'est données.
                    //Coche une de ses données si déjà séléctionné auparavant
                    foreach ($registreContenuSensible as $contenuSensible) {
                        if (isset($_POST["contenuSensible"]) && in_array($contenuSensible->idPegi, $_POST["contenuSensible"])) {
                            $coche = "checked";
                        } else {
                            $coche = "";
                        }
                        echo "<input type=\"checkbox\" name=\"contenuSensible[]\" value=\"$contenuSensible->idPegi\" $coche>$contenuSensible->contenuSensible<br>";
                    }
                    ?>

                    <label for="trancheAge" style="color:<?= $erreurTrancheAge; ?>">Tranche d'âge: </label><br>
                    <select name="trancheAge">
                        <?php
                        //Parcours le tableau et affiche c'est données.
                        //Coche une de ses données si déjà séléctionné auparavant
                        foreach ($listeTranchesAge as $tranchesAge) {

                            echo "<option value=\"" . $tranchesAge . "\"" . (isset($_POST['trancheAge']) && $_POST['trancheAge'] == $tranchesAge ? "selected" : false) . ">";
                            echo $tranchesAge;
                            echo '</option>';
                        }
                        ?>
                    </select><br>

                    <label for="description" style="color:<?= $erreurDescription; ?>">Description du jeu : </label><br>
                    <textarea name="description" cols="30" rows="10"><?= $jeuProposer->description ?></textarea>
                    <input type="submit" name="valider" class="btn btn-success" value="Valider">
                    <input type="submit" name="modifier" class="btn btn-warning" value="Modifier">
                    <input type="submit" name="supprimer" class="btn btn-danger" value="Supprimer">

                </form>
        <?php  }
        }
        ?>
    </main>
    <footer>
        &copy;Fait par Mofassel Haque Srijon Rahman <br>
        Contact : srijon.rhmn@eduge.ch
    </footer>

</body>

</html>