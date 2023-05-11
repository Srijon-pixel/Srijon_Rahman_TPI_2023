<?php

/**
 * Auteur: Mofassel Haque Srijon Rahman
 * Date: 27.04.2023
 * Projet: TPI video game club
 * Détail: Modèle de vue pour les autres pages du site
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
    <title>Canevas</title>
</head>

<body>
    <?php

    //Permet d'utiliser les fonctions du fichier 
    require_once './fonctions/fonction_utilisateur.php';
    require_once './fonctions/fonction_session.php';

    const COULEUR_MESSAGE_ERREUR = "red";

    $titre = "";
    $version = "";
    $imageEncode = "";
    $description = "";
    $dateSortie = "";
    $tranchesAge = array(3, 7, 12, 16, 18);
    $contenuSensible = array();
    $plateforme = array();
    $genre = array();


    $erreurTitre = "";
    $erreurDescription = "";
    $erreurDateSortie = "";
    $erreurTrancheAge = "";
    $erreurContenuSensible = "";
    $erreurPlateforme = "";
    $erreurGenre = "";

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

    if (isset($_POST["proposer"])) {

        $titre = filter_input(INPUT_POST, 'titre');
        $titre = antiInjectionXSS($titre);
        if ($titre == "" || preg_match('/[a-zA-Z]/', $titre) == false) {
            echo '<script>alert("Veuillez écrire le titre de votre jeu.")</script>';
            $erreurTitre = COULEUR_MESSAGE_ERREUR;
        }

        $version = filter_input(INPUT_POST, 'version');
        $version = antiInjectionXSS($version);
        if ($version == "" || preg_match('/[a-zA-Z]/', $version) == false) {
            echo '<script>alert("Veuillez écrire la version du jeu.")</script>';
            $erreurVersion = COULEUR_MESSAGE_ERREUR;
        }

        $description = filter_input(INPUT_POST, 'description');
        $description = antiInjectionXSS($description);
        if ($description == "" || preg_match('/[a-zA-Z]/', $description) == false) {
            echo '<script>alert("Veuillez écrire la version du jeu.")</script>';
            $erreurDescription = COULEUR_MESSAGE_ERREUR;
        }

        $dateSortie = filter_input(INPUT_POST, 'dateSortie');
        $dateSortie = antiInjectionXSS($dateSortie);
        if ($dateSortie == "") {
            $erreurDateSortie = COULEUR_MESSAGE_ERREUR;
        }

        if (
            $erreurTitre != COULEUR_MESSAGE_ERREUR && $erreurVersion != COULEUR_MESSAGE_ERREUR
            && $erreurDescription != COULEUR_MESSAGE_ERREUR && $erreurDateSortie != COULEUR_MESSAGE_ERREUR
            && $erreurTrancheAge != COULEUR_MESSAGE_ERREUR && $erreurContenuSensible != COULEUR_MESSAGE_ERREUR
            && $erreurGenre != COULEUR_MESSAGE_ERREUR && $erreurPlateforme != COULEUR_MESSAGE_ERREUR
        ) {
            if (AjouterJeu(
                $titre,
                $version,
                $dateSortie,
                $datePublication,
                $imageEncode,
                $description,
                $proposition
            )) {
                header('Location: index.php');
                exit;
            }
        }
    }

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
            <label for="titre" style="color:<?= $erreurTitre; ?>">Titre:</label><br>
            <input type="text" name="titre" value="<?= $titre; ?>"><br>

            <label for="version">Version du jeu : </label><br>
            <input type="text" name="version" value="<?= $version; ?>"><br>

            <label for="dateSortie">Date de sortie : </label><br>
            <input type="date" name="dateSortie" value="<?= $dateSortie; ?>"><br>

            <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
            <label for="imageEncode">Image</label><br>
            <input type="file" name="imageEncode" id="imageEncode" multiple accept="image/png, image/jpeg"> <br>

            <label for="plateforme" style="color:<?= $erreurPlateforme; ?>">Plateformes: </label><br>
            <?php
            foreach ($registrePlateforme as $plateforme) {
                if (isset($_POST["plateforme"]) && in_array($plateforme->nomPlateforme, $_POST["plateforme"])) {
                    $coche = "checked";
                } else {
                    $coche = "";
                }
                echo "<input type=\"checkbox\" name=\"plateforme[]\" value=\"$plateforme->nomPlateforme\" $coche>$plateforme->nomPlateforme<br>";
            }
            ?>

            <label for="genre" style="color:<?= $erreurGenre; ?>">Genres: </label><br>
            <?php
            foreach ($registreGenre as $genre) {
                if (isset($_POST["genre"]) && in_array($genre->nomGenre, $_POST["genre"])) {
                    $coche = "checked";
                } else {
                    $coche = "";
                }
                echo "<input type=\"checkbox\" name=\"genre[]\" value=\"$genre->nomGenre\" $coche>$genre->nomGenre<br>";
            }
            ?>
            <p>PEGI:</p>
            <label for="contenuSensible" style="color:<?= $erreurContenuSensible; ?>">Contenu sensible: </label><br>
            <?php
            foreach ($registreContenuSensible as $contenuSensible) {
                if (isset($_POST["contenuSensible"]) && in_array($contenuSensible->contenuSensible, $_POST["contenuSensible"])) {
                    $coche = "checked";
                } else {
                    $coche = "";
                }
                echo "<input type=\"checkbox\" name=\"contenuSensible[]\" value=\"$contenuSensible->contenuSensible\" $coche>$contenuSensible->contenuSensible<br>";
            }
            ?>

            <label for="trancheAge" style="color:<?= $erreurTrancheAge; ?>">Tranche d'âge: </label><br>
            <select name="trancheAge">
                <?php
                foreach ($tranchesAge as $trancheAge) {

                    echo "<option value=\"" . $trancheAge . "\"" . (isset($_POST['trancheAge']) && $_POST['trancheAge'] == $trancheAge ? "selected" : false) . ">";
                    echo $trancheAge;
                    echo '</option>';
                }
                ?>
            </select><br>
            <label for="description" style="color:<?= $erreurDescription; ?>">Description du jeu : </label><br>
            <textarea name="description" cols="30" rows="10"></textarea>
            <input type="submit" name="proposer" class="btn btn-primary" value="Proposer"><br>

        </form>
    </main>
    <footer>
        &copy;Fait par Mofassel Haque Srijon Rahman <br>
        Contact : srijon.rhmn@eduge.ch
    </footer>

</body>

</html>