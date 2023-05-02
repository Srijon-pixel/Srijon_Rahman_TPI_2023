<!DOCTYPE html>
<html lang="en">
<!--
    Auteur: Srijon Rahman
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
    <title>Profil</title>
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
    $utilisateur = RecupereUtilisateurParSession();
    $nomUtilisateur = 'invité';
    $boutonDirection = '/identification.php';
    $boutonTexte = 'Connexion';
    $boutonParametre = '';

    if ($utilisateur != false) {
        $nomUtilisateur = $utilisateur[0]->pseudo;
        $boutonDirection = '/logout.php';
        $boutonTexte = 'Déconnexion';
        $boutonParametre = '<button class="btn"><a href="./profil.php?id=' . $utilisateur[0]->idUtilisateur . '">Compte</a></button>';
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
                        <a href="<?= $boutonDirection ?>" class="btn btn-primary"><?= $boutonTexte ?></a>
                    </div>
                </div>
            </div>
        </nav>

    </header>
    <main>
        <form action="#" method="POST">
            <?php foreach ($enregistrements as $utilisateur) {

                echo "<label for=\"nom\">Votre nom d'utilisateur :</label><br>";
                echo "<input type=\"text\" name=\"nom\" value=\"" . $utilisateur->nom . "\"><br>";
                echo "<label for=\"prenom\">Votre prénom d'utilisateur :</label><br>";
                echo "<input type=\"text\" name=\"prenom\" value=\"" . $utilisateur->prenom . "\"><br>";
                echo "<label for=\"pseudo\">Votre pseudo :</label><br>";
                echo "<input type=\"text\" name=\"pseudo\" value=\"" . $utilisateur->pseudo . "\"><br>";
                echo "<label for=\"email\">Votre email : </label><br>";
                echo "<input type=\"email\" name=\"email\" value=\"" . $utilisateur->email . "\"><br>";
                echo "<label for=\"statut\" >Votre statut : </label><br>";
                if($utilisateur->statut == 0){
                    echo "<input type=\"text\" name=\"statut\" value=\"simple utilisateur\"><br>";
                }else{
                    echo "<input type=\"text\" name=\"statut\" value=\"administrateur\"><br>";

                }
                ;
            } ?>
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