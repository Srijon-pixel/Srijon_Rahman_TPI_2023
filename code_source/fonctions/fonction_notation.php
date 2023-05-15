<?php

/**
 * Auteur: Mofassel Haque Srijon Rahman
 * Date: 04.05.2023
 * Projet: TPI video game club
 * Détail: Regroupe toutes les fonctionnalités pour les notes sur les jeux vidéo du sites
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/bd/base_de_donnee.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classe/notation.php';


/**
 * Récupère la note du jeu vidéo à partir de son identifiant
 *
 * @param int $idJeuVideo l'identifiant du jeu vidéo
 * @return array|bool true si la requête a été correctement effectué, sinon false 
 */
function RecupereNoteJeuParId($idJeuVideo)
{
    $arr = array();
    $sql = "SELECT ROUND(AVG(`notation`.`note`), 1) AS `Note`, COUNT(`notation`.`idUtilisateur`) AS `nombreNotePersonne`
    FROM `notation` WHERE `notation`.`idJeuVideo` = :ij; ";
    $statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(':ij' => $idJeuVideo));
    } catch (PDOException $e) {
        return false;
    }
    while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $c = new ENotation(
            $row['idNotation'] = null,
            $row['Note'],
            $row['nombreNotePersonne'],
            $row['idUtilisateur'] = null,
            $row['idJeuVideo'] = null
        );
        array_push($arr, $c);
    }

    // Done
    return $arr;
}

/**
 * Récupère la note du jeu vidéo à partir de son identifiant
 *
 * @param int $idJeuVideo l'identifiant du jeu vidéo
 * @return array|bool true si la requête a été correctement effectué, sinon false 
 */
function RecupereNoteParIdUtilisateur($idUtilisateur, $idJeuVideo)
{
    $arr = array();
    $sql = "SELECT `notation`.`idNotation`, `notation`.`note`, `notation`.`idUtilisateur`, 
    `notation`.`idJeuVideo`FROM `notation` WHERE `notation`.`idUtilisateur` = :iu AND 
    `notation`.`idJeuVideo` = :ij; ";
    $statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(':iu' => $idUtilisateur, ':ij' => $idJeuVideo));
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
            $row['nombreNotePersonne'] = null,
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
    $sql = "INSERT INTO `notation` (`note`, `idUtilisateur`,`idJeuVideo`) 
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
    $sql = "UPDATE `notation`
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
