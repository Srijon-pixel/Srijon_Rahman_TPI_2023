<?php

/**
 * Auteur: Mofassel Haque Srijon Rahman
 * Date: 27.04.2023
 * Projet: TPI video game club
 * Détail: Regroupe toutes les fonctionnalités pour les notes du sites
 */
require_once './bd/base_de_donnee.php';
require_once './classe/notation.php';


/**
 * Récupère la note d'un jeu vidéo de la base de donnée
 *
 * @return array|bool Un tableau des ENotation
 *                    False si une erreur
 */
function RecupereNoteJeu($idJeuVideo)
{
    $arr = array();

    $sql = "SELECT `notation`.`idNotation`, `notation`.`note`, `notation`.`idUtilisateur`,
     `notation`.`idJeuVideo`  FROM notation WHERE idJeuVideo = :ij";
    $statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(':ij' => $idJeuVideo));
    } catch (PDOException $e) {
        return false;
    }
    // On parcoure les enregistrements 
    while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        // On crée l'objet ENotation en l'initialisant avec les données provenant
        // de la base de données
        $c = new ENotation(
            intval($row['idNotation']),
            $row['note'],
            $row['idUtilisateur'],
            $row['idJeuVideo']

        );
        // On place l'objet ENotation créé dans le tableau
        array_push($arr, $c);
    }

    // Done
    return $arr;
}


/**
 * Insère la note du jeu vidéo dans la base de donnée
 *
 * @return bool true si l'insertion a été correctement effectué, sinon false 
 */
function AjouterNote(
    $note,
    $idUtilisateur,
    $idJeuVideo
) {
    $sql = "INSERT INTO `video_game_club`.`notation` (`note`, `idUtilisateur`,`idJeuVideo`) 
	VALUES(:nt,:iu,:ij)";
    $statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":nt" => $note, ":iu" => $idUtilisateur, ":ij" => $idJeuVideo));
    } catch (PDOException $e) {
        return false;
    }
    // Done
    return true;
}



/**
 * Modifie les données de la note du jeu dans la base de donnée
 *
 * @return bool true si la requête a été correctement effectué, sinon false 
 */
function modifierNote(
    $idNotation,
    $note
) {
    $sql = "UPDATE `video_game_club`.`notation`
    SET `notation`.`note` = :nt
	WHERE `notation`.`idNotation` = :dn";
    $statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":dn" => $idNotation, ":nt" => $note));
    } catch (PDOException $e) {
        return false;
    }
    // Done
    return true;
}
