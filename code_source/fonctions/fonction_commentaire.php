<?php

/**
 * Auteur: Mofassel Haque Srijon Rahman
 * Date: 27.04.2023
 * Projet: TPI video game club
 * Détail: Regroupe toutes les fonctionnalités pour les utilisateurs du sites
 */
require_once './bd/base_de_donnee.php';
require_once './classe/jeu_video.php';
require_once './classe/commentaire.php';



/**
 * Récupère touts les jeux vidéo de la base de donnée
 *
 * @return array|bool Un tableau des EJeuVideo
 *                    False si une erreur
 */
function RecupereToutLesCommentaires()
{
    $arr = array();

    $sql = "SELECT `commentaire`.`idCommentaire`, `commentaire`.`titre`, `commentaire`.`dateSortie` FROM jeuVideo";
    $statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute();
    } catch (PDOException $e) {
        return false;
    }
    // On parcoure les enregistrements 
    while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        // On crée l'objet EJeuVideo en l'initialisant avec les données provenant
        // de la base de données
        $c = new ECommentaire(
            intval($row['idCommentaire']),
            $row['commentaire'],
            $row['dateCommentaire']

        );
        // On place l'objet EJeuVideo créé dans le tableau
        array_push($arr, $c);
    }

    // Done
    return $arr;
}



/**
 * Insère l'utilisateur dans la base de donnée
 *
 * @return bool true si l'insertion a été correctement effectué, sinon false 
 */
function AjouterCommentaire(
    $titre,
    $version,
    $dateSortie,
) {
    $sql = "INSERT INTO `video_game_club`.`jeuVideo` (`titre`, `version`,`dateSortie`,`datePublication`,
	`imageEncode`,`description`,`proposition`,`titre`,`titre`,`titre`,) 
	VALUES(:n,:pr,:ps,:e,0,:m)";
    $statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":n" => $nom, ":pr" => $prenom, ":ps" => $pseudo, ":e" => $email, ":m" => password_hash($motDePasse, PASSWORD_BCRYPT)));
    } catch (PDOException $e) {
        return false;
    }
    // Done
    return true;
}

/** 
 * Supprime définitivement le commentaire de la base de donnée 
 * @param int $idCommentaire identifiant du commentaire
 */
function SupprimerCommentaire($idCommentaire)
{
    $sql = "DELETE FROM `video_game_club`.`commentaire` WHERE `commentaire`.`idCommentaire` = :ic";
    $statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":ic" => $idCommentaire));
    } catch (PDOException $e) {
        return false;
    }
    // Fini
    return true;
}

/**
 * Modifie les données du commentaire dans la base de donnée
 *
 * @return bool true si la requête a été correctement effectué, sinon false 
 */
function modifierCommentaire(
    $idCommentaire,
    $commentaire,
    $dateCommentaire
) {
    $sql = "UPDATE `video_game_club`.`commentaire`
    SET `commentaire`.`commentaire` = :c, `jeuVideo`.`dateCommentaire` = :dc
	WHERE `commentaire`.`idCommentaire` = :ic";
    $statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":ic" => $idCommentaire, ":c" => $commentaire, ":dc" => $dateCommentaire));
    } catch (PDOException $e) {
        return false;
    }
    // Done
    return true;
}
