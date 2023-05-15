<?php

/**
 * Auteur: Mofassel Haque Srijon Rahman
 * Date: 04.05.2023
 * Projet: TPI video game club
 * Détail: Regroupe toutes les fonctionnalités pour les commentaires sur les jeux vidéo du sites
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/bd/base_de_donnee.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classe/commentaire.php';



/**
 * Récupère touts les jeux vidéo de la base de donnée
 *
 * @return array|bool Un tableau des ECommentaire
 *                    False si une erreur
 */
function RecupereCommentaireJeuParId($idJeuVideo)
{
    $arr = array();

    $sql = "SELECT `commentaire`.`idCommentaire`, `commentaire`.`commentaire`, `commentaire`.`dateCommentaire`, 
    `utilisateur`.`pseudo`
    FROM `commentaire`
    JOIN `utilisateur` ON `commentaire`.`idUtilisateur` = `utilisateur`.`idUtilisateur`
    WHERE `commentaire`.`idJeuVideo` = :ij
    ORDER BY `commentaire`.`dateCommentaire` DESC LIMIT 20;";
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
            $row['pseudo']

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
    $sql = "INSERT INTO `commentaire` (`commentaire`, `dateCommentaire`,`idUtilisateur`,`idJeuvideo`) 
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
 * Modifie les données du commentaire dans la base de donnée
 *
 * @return bool true si la requête a été correctement effectué, sinon false 
 */
function ModifierCommentaire(
    $idCommentaire,
    $commentaire,
    $dateCommentaire
) {
    $sql = "UPDATE `commentaire`
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
