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
    require_once './functions/function_user.php';
    require_once './functions/function_session.php';
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