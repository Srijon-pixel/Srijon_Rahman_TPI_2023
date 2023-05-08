<?php

/**
 * Auteur: Mofassel Haque Srijon Rahman
 * Date: 27.04.2023
 * Projet: TPI video game club
 * Détail: Regroupe toutes les fonctionnalités pour les utilisateurs du sites
 */
require_once './bd/base_de_donnee.php';
require_once './classe/commentaire.php';



/**
 * Récupère touts les jeux vidéo de la base de donnée
 *
 * @return array|bool Un tableau des ECommentaire
 *                    False si une erreur
 */
function RecupereCommentaireJeu($idJeuVideo)
{
    $arr = array();

    $sql = "SELECT `commentaire`.`idCommentaire`, `commentaire`.`commentaire`, `commentaire`.`dateCommentaire`,
     `commentaire`.`idUtilisateur`, `commentaire`.`idJeuVideo`  FROM commentaire WHERE idJeuVideo = :ij";
    $statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(':ij' => $idJeuVideo));
    } catch (PDOException $e) {
        return false;
    }
    // On parcoure les enregistrements 
    while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        // On crée l'objet ECommentaire en l'initialisant avec les données provenant
        // de la base de données
        $c = new ECommentaire(
            intval($row['idCommentaire']),
            $row['commentaire'],
            $row['dateCommentaire'],
            $row['idUtilisateur'],
            $row['idJeuVideo']

        );
        // On place l'objet ECommentaire créé dans le tableau
        array_push($arr, $c);
    }

    // Done
    return $arr;
}



/**
 * Insère le commentaire d'un utilisateur dans la base de donnée
 *
 * @return bool true si l'insertion a été correctement effectué, sinon false 
 */
function AjouterCommentaire(
    $commentaire,
    $dateCommentaire,
    $idUtilisateur,
    $idJeuvideo
) {
    $sql = "INSERT INTO `video_game_club`.`jeuVideo` (`commentaire`, `dateCommentaire`,`idUtilisateur`,`idJeuvideo`) 
	VALUES(:c,:dc,:iu,:ij)";
    $statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":c" => $commentaire, ":dc" => $dateCommentaire, ":iu" => $idUtilisateur, ":ij" => $idJeuvideo));
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
function ModifierCommentaire(
    $idCommentaire,
    $commentaire,
    $dateCommentaire
) {
    $sql = "UPDATE `video_game_club`.`commentaire`
    SET `commentaire`.`commentaire` = :c, `commentaire`.`dateCommentaire` = :dc
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
