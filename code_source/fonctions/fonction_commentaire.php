<?php

/**
 * Auteur: Mofassel Haque Srijon Rahman
 * Date: 04.05.2023
 * Projet: TPI video game club
 * Détail: Regroupe toutes les fonctionnalités pour les commentaires des jeux vidéo du site
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/bd/base_de_donnee.php'; // connection à la base de données
//La classe nécesssaire pour afficher ou ajouter les données.
require_once $_SERVER['DOCUMENT_ROOT'] . '/classe/commentaire.php';



/**
 * Récupère touts les commentaires d'un jeu vidéo de la base de donnée à partir de l'identifiant du jeu
 * @param int $idJeuvideo l'identifiant du jeu video
 * @return array|bool Un tableau des ECommentaire
 *                    False si une erreur
 */
function RecupereCommentaireJeuParId($idJeuVideo)
{
    $tableau = array();

    //Requête SQL a executé 
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
        array_push($tableau, $c);
    }

    // Done
    return $tableau;
}


/**
 * Insère le commentaire d'un utilisateur à un jeu dans la base de donnée
 * @param string $commentaire le commentaire de l'utilisateur
 * @param string $dateCommentaire la date commentaire écris par l'utilisateur
 * @param int $idUtilisateur l'identifiant de l'utilisateur
 * @param int $idJeuvideo l'identifiant du jeu video
 * 
 * @return bool true si l'insertion a été correctement effectué, sinon false 
 */
function AjouterCommentaire(
    $commentaire,
    $dateCommentaire,
    $idUtilisateur,
    $idJeuvideo
) {
    //Requête SQL a executé 
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

