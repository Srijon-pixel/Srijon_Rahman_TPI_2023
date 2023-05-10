<?php

/**
 * Auteur: Mofassel Haque Srijon Rahman
 * Date: 04.05.2023
 * Projet: TPI video game club
 * Détail: Regroupe toutes les fonctionnalités pour les notes sur les jeux vidéo du sites
 */
require_once './bd/base_de_donnee.php';
require_once './classe/notation.php';





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
