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
 * Insère l'utilisateur dans la base de donnée
 *
 * @return bool true si l'insertion a été correctement effectué, sinon false 
 */
function ajouterNote(
    $note,
    $utilisateur,
    $jeu
) {
    $sql = "INSERT INTO `video_game_club`.`notation` (`note`, `version`,`dateSortie`) 
	VALUES(:n,:u,:j)";
    $statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":n" => $note, ":u" => $utilisateur, ":j" => $jeu));
    } catch (PDOException $e) {
        return false;
    }
    // Done
    return true;
}



/**
 * Modifie les données du commentaire dans la base de donnée
 *
 * @return bool true si la requête a été correctement effectué, sinon false 
 */
function modifierNote(
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
