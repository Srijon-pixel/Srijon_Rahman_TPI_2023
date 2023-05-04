<?php

/**
 * Auteur: Mofassel Haque Srijon Rahman
 * Date: 27.04.2023
 * Projet: TPI video game club
 * Détail: Regroupe toutes les fonctionnalités pour les utilisateurs du sites
 */
require_once './bd/base_de_donnee.php';
require_once './classe/jeu_video.php';



/**
 * Récupère touts les jeux vidéo de la base de donnée
 *
 * @return array|bool Un tableau des EJeuVideo
 *                    False si une erreur
 */
function RecupereToutLesJeuVideo()
{
	$arr = array();

	$sql = "SELECT `jeuVideo`.`idJeuVideo`, `jeuVideo`.`titre`, `jeuVideo`.`version`, `jeuVideo`.`dateSortie`, 
	`jeuVideo`.`datePublication`,  `jeuVideo`.`imageEncode`, `jeuVideo`.`description`, `jeuVideo`.`proposition`,
	`jeuVideo`.`idPegi`, `jeuVideo`.`idGenre`, `jeuVideo`.`idPlateforme` FROM jeuVideo";
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
		$c = new EJeuVideo(
			intval($row['idJeuVideo']),
			$row['titre'],
			$row['version'],
			$row['dateSortie'],
			$row['datePublication'],
			$row['imageEncode'],
			$row['description'],
			$row['proposition'],
			$row['idPegi'],
			$row['idGenre'],
			$row['idPlateforme']

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
function AjouterJeu(
	$titre,
	$version,
	$dateSortie,
	$datePublication,
	$imageEncode,
	$description,
	$proposition,
	$idPegi,
	$idGenre,
	$idPlateforme
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
 * Supprime définitivement le jeu vidéo de la base de donnée 
 * @param int $idJeuvideo identifiant du jeu
 */
function SupprimerJeu($idJeuvideo)
{
	$sql = "DELETE FROM `video_game_club`.`jeuVideo` WHERE `jeuVideo`.`idJeuvideo` = :ij";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(":ij" => $idJeuvideo));
	} catch (PDOException $e) {
		return false;
	}
	// Fini
	return true;
}

/**
 * Modifie les données du jeu vidéo dans la base de donnée
 *
 * @return bool true si la requête a été correctement effectué, sinon false 
 */
function modifierJeu(
	$idJeuVideo,
	$titre,
	$version,
	$dateSortie,
	$datePublication,
	$imageEncode,
	$description,
	$proposition,
	$idPegi,
	$idGenre,
	$idPlateforme,
	$idCommentaire,
	$idNotation
) {
	$sql = "UPDATE `video_game_club`.`jeuVideo`

	JOIN pegi ON jeuVideo.idPegi = pegi.idPegi 
	JOIN genre ON jeuVideo.idGenre = genre.idGenre 
	JOIN plateforme ON jeuVideo.idPlateforme = plateforme.idPlateforme 
	JOIN commentaire ON jeuVideo.idCommentaire = commentaire.idCommentaire 
	JOIN notation ON jeuVideo.idNotation = notation.idNotation 

	SET `jeuVideo`.`titre` = :t, `jeuVideo`.`version` = :v, `jeuVideo`.`dateSortie` = :ds, 
	`jeuVideo`.`datePublication` = :dp, `jeuVideo`.`imageEncode` = :ie, `jeuVideo`.`description` = :d,
	`jeuVideo`.`proposition` = :p, `jeuVideo`.`datePublication` = :dp, `jeuVideo`.`datePublication` = :dp,

	WHERE `jeuVideo`.`idJeuVideo` = :ij";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(":i" => $idJeuVideo, ":n" => $nom, ":pr" => $prenom, ":ps" => $pseudo, ":e" => $email));
	} catch (PDOException $e) {
		return false;
	}
	// Done
	return true;
}
