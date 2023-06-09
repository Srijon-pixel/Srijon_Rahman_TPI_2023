<?php

/**
 * Auteur: Mofassel Haque Srijon Rahman
 * Date: 27.04.2023
 * Projet: TPI video game club
 * Détail: Page permettant à l'utilisateur de modifier son mot de passe
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
    <title>Modifier le mot de passe</title>
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

    $motDePasse = "";
    $erreurMotDePasse = "";

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

    //Si l'utilisateur veut modifier sont mot de passe
    if (isset($_POST['modifierMotDePasse'])) {
        //Récupère l'identifiant de l'utilisateur
        $idUtilisateur = intval($utilisateur[0]->idUtilisateur);
        //Filtrage + Traitements des données
        $motDePasse = filter_input(INPUT_POST, 'motDePasse');
        $motDePasse = antiInjectionXSS($motDePasse);
        if (MotDePasseSyntax($motDePasse) == false || $motDePasse == "") {
            echo '<script>alert("Veuillez écrire votre mot de passe correctement. 
            Il vous faut au minimum une majuscule, une minuscule, un chiffre et 8 caractères ")</script>';
            $erreurMotDePasse = COULEUR_MESSAGE_ERREUR;
        }

        if ($idUtilisateur > 0 &&  $erreurMotDePasse != COULEUR_MESSAGE_ERREUR) {
            if (ModifierMotDePasse($idUtilisateur, $motDePasse)) {
                header('Location: profil.php');
                exit;
            }
        } else {
            echo '<script>alert("Pas possible il vous manque des valeurs ou des valeurs existent déjà chez d\'autre compte")</script>';
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

                echo "<label for=\"motDePasse\" style=\"" . $erreurMotDePasse . "\">Votre mot de passe :</label><br>";
                echo "<input type=\"password\" name=\"motDePasse\" value=\"" . $utilisateur->motDePasse . "\"  placeholder=\"Minimum une majuscule, une minuscule, un chiffre et 8 caractères\" style=\"width:251%\"><br>";
            } ?>
            <input type="submit" name="modifierMotDePasse" class="btn btn-primary" value="Modifier le compte">
        </form>

    </main>
    <footer>
        &copy;Fait par Mofassel Haque Srijon Rahman <br>
        Contact : srijon.rhmn@eduge.ch
    </footer>

</body>

</html>