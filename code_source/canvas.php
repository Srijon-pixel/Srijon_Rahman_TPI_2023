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
    <title>Canvas</title>
</head>

<body>
    <?php

    //Permet d'utiliser les fonctions du fichier 
    require_once './fonctions/fonction_utilisateur.php';
    require_once './fonctions/fonction_session.php';


    $user = GetUserFromSession();
    $userName = 'invité';
    $btnDirection = '/identification.php';
    $btnText = 'Connexion';
    $btnParametre = '';

    if ($user != false) {
        $userName = $user->pseudo;
        $btnDirection = '/logout.php';
        $btnText = 'Déconnexion';
        $btnParametre = '<button class="btn"><a href="./account.php?id=' . $user->idUtilisateur . '">Compte</a></button>';
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
                    <li class="nav-item"><a class="nav-link" href="./editGame.php">Éditer un jeu</a></li>
                    <li class="nav-item"><a class="nav-link" href="./identification.php"> Identification </a></li>
                    <li class="nav-item"><a class="nav-link" href="./inscription.php"> Inscription </a></li>
                    <li class="nav-item"><a class="nav-link" href="./profil.php">Profile</a></li>
                </ul>
                <div class="card d-flex flex-column align-items-center">
                    <div class="card-body">
                        <h5 class="card-title"><?= $userName ?></h5>
                        <?= $btnParametre ?>
                        <a href="<?= $btnDirection ?>" class="btn btn-primary"><?= $btnText ?></a>
                    </div>
                </div>
            </div>
        </nav>

    </header>
    <main>


    </main>
    <footer>
        &copy;Fait par Mofassel Haque Srijon Rahman <br>
        Contact : srijon.rhmn@eduge.ch
    </footer>

</body>

</html>