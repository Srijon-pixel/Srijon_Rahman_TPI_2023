<?php

/**
 * Auteur: Mofassel Haque Srijon Rahman
 * Date: 04.05.2023
 * Projet: TPI video game club
 * Détail: Regroupe toutes les fonctionnalités pour les jeux vidéo du sites
 */
require_once './bd/base_de_donnee.php';
require_once './classe/jeuVideo.php';
require_once './classe/genre.php';
require_once './classe/plateforme.php';
require_once './classe/pegi.php';



/**
 * Récupère touts les jeux vidéo de la base de donnée
 *
 * @return array|bool Un tableau des EJeuVideo
 *                    False si une erreur
 */
function RecupereToutLesJeuxVideo()
{
	$arr = array();

	$sql = "SELECT `jeuVideo`.`idJeuVideo`, `jeuVideo`.`titre`, `jeuVideo`.`version`, `jeuVideo`.`dateSortie`, 
	`jeuVideo`.`datePublication`,  `jeuVideo`.`imageEncode`, `jeuVideo`.`description`, `jeuVideo`.`trancheAge`,
	 `jeuVideo`.`proposition`, 

	GROUP_CONCAT(DISTINCT `genre`.`nomGenre` SEPARATOR \", \") AS `genres`, 
	GROUP_CONCAT(DISTINCT `plateforme`.`nomPlateforme` SEPARATOR \", \") AS `plateformes`,
	GROUP_CONCAT(DISTINCT `pegi`.`contenuSensible` SEPARATOR \", \") AS `contenu sensible`
    FROM `jeuVideo` 

    
    JOIN `liaison_genre_jeu` ON `jeuvideo`.`idJeuVideo` = `liaison_genre_jeu`.`idJeuVideo`
    JOIN `genre` ON `liaison_genre_jeu`.`idGenre` = `genre`.`idGenre` 
    
    JOIN `liaison_plateforme_jeu` ON `jeuvideo`.`idJeuVideo` = `liaison_plateforme_jeu`.`idJeuVideo`
    JOIN `plateforme` ON `liaison_plateforme_jeu`.`idPLateforme` = `plateforme`.`idPLateforme` 
    
	JOIN `liaison_pegi_jeu` ON `jeuvideo`.`idJeuVideo` = `liaison_pegi_jeu`.`idJeuVideo`
    JOIN `pegi` ON `liaison_pegi_jeu`.`idPegi` = `pegi`.`idPegi` 

	WHERE `jeuVideo`.`proposition` = 0
    GROUP BY `jeuvideo`.`idJeuVideo`
	ORDER BY `jeuVideo`.`dateSortie` DESC LIMIT 20;";
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
			$row['genres'],
			$row['plateformes'],
			$row['trancheAge'],
			$row['contenu sensible']

		);
		// On place l'objet EJeuVideo créé dans le tableau
		array_push($arr, $c);
	}

	// Done
	return $arr;
}

/**
 * Récupère le jeu vidéo à l'aide de son identifiant de la base de donnée
 *
 * @return array|bool Un tableau des EJeuVideo
 *                    False si une erreur
 */
function RecupereJeuVideoParId($idJeuVideo)
{
	$arr = array();

	$sql = "SELECT `jeuVideo`.`idJeuVideo`, `jeuVideo`.`titre`, `jeuVideo`.`version`, `jeuVideo`.`dateSortie`, 
	`jeuVideo`.`datePublication`,  `jeuVideo`.`imageEncode`, `jeuVideo`.`description`, `jeuVideo`.`trancheAge`,
	 `jeuVideo`.`proposition`,

	GROUP_CONCAT(DISTINCT `genre`.`nomGenre` SEPARATOR \", \") AS `genres`, 
	GROUP_CONCAT(DISTINCT `plateforme`.`nomPlateforme` SEPARATOR \", \") AS `plateformes`,
	GROUP_CONCAT(DISTINCT `pegi`.`contenuSensible` SEPARATOR \", \") AS `contenu sensible`
    FROM `jeuVideo` 
    
    JOIN `liaison_genre_jeu` ON `jeuvideo`.`idJeuVideo` = `liaison_genre_jeu`.`idJeuVideo`
    JOIN `genre` ON `liaison_genre_jeu`.`idGenre` = `genre`.`idGenre` 
    
    JOIN `liaison_plateforme_jeu` ON `jeuvideo`.`idJeuVideo` = `liaison_plateforme_jeu`.`idJeuVideo`
    JOIN `plateforme` ON `liaison_plateforme_jeu`.`idPLateforme` = `plateforme`.`idPLateforme` 
    
	JOIN `liaison_pegi_jeu` ON `jeuvideo`.`idJeuVideo` = `liaison_pegi_jeu`.`idJeuVideo`
    JOIN `pegi` ON `liaison_pegi_jeu`.`idPegi` = `pegi`.`idPegi` 

    WHERE `jeuVideo`.`proposition` = 0 AND `jeuvideo`.`idJeuVideo` = :ij;";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(':ij' => $idJeuVideo));
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
			$row['genres'],
			$row['plateformes'],
			$row['trancheAge'],
			$row['contenu sensible']

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
 * @return int|false Retourne l'identifiant du dernier jeu ajouté, sinon false 
 */
function AjouterJeu(
	$titre,
	$version,
	$dateSortie,
	$datePublication,
	$imageEncode,
	$description,
	$proposition
) {
	$sql = "INSERT INTO `jeuVideo` (`titre`, `version`,`dateSortie`,`datePublication`,
	`imageEncode`,`description`,`proposition`,`idPegi`,`idGenre`,`idPlateforme`) 
	VALUES(:t,:v,:ds,:dp,ie,:d,:p,:pe,:ig,:pl)";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(
			":t" => $titre, ":v" => $version, ":ds" => $dateSortie,
			":dp" => $datePublication, ":ie" => $imageEncode,
			":d" => $description, ":p" => $proposition
		));
	} catch (PDOException $e) {
		return false;
	}
	// Retourner l'identifiant du jeu vidéo ajouté
	return EBaseDonnee::lastInsertId();
}


/**
 * Insère les données supllémentaires dans les tables de liasons dans la base de donnée
 *
 * @return bool true si l'insertion a été correctement effectué, sinon false 
 */
function AjouterJeuAvecLiaisons(
	$idJeuVideo,
	$idPegi,
	$idGenre,
	$idPlateforme
) {
	$sqlPegi = "INSERT INTO `liaison_pegi_jeu` (`idJeuVideo`,`idPegi`) 
	VALUES(:ij,:pe)";
	$sqlGenre = "INSERT INTO `liaison_genre_jeu` (`idJeuVideo`,`idGenre`) 
	VALUES(:ij,:ig)";
	$sqlPlateforme = "INSERT INTO `liaison_genre_plateforme` (`idJeuVideo`,`idPlateforme`) 
	VALUES(:ij,:pl)";

	$statementPegi = EBaseDonnee::prepare($sqlPegi, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$statementGenre = EBaseDonnee::prepare($sqlGenre, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$statementPlateforme = EBaseDonnee::prepare($sqlPlateforme, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

	try {
		$statementPegi->execute(array(
			":ij" => $idJeuVideo, ":pe" => $idPegi
		));
		$statementGenre->execute(array(
			":ij" => $idJeuVideo, ":ig" => $idGenre
		));
		$statementPlateforme->execute(array(
			":ij" => $idJeuVideo, ":pl" => $idPlateforme
		));
	} catch (PDOException $e) {
		return false;
	}

	return true;
}

/** 
 * Supprime définitivement le jeu vidéo de la base de donnée 
 * @param int $idJeuvideo identifiant du jeu
 */
function SupprimerJeu($idJeuvideo)
{
	$sql = "DELETE FROM `jeuVideo` WHERE `jeuVideo`.`idJeuvideo` = :ij";
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
 * Modifie les données du jeu vidéo dans la base de données
 *
 * @return bool true si la requête a été correctement effectué, sinon false 
 */
function ModifierJeu(
	$idJeuVideo,
	$titre,
	$version,
	$dateSortie,
	$datePublication,
	$imageEncode,
	$description,
	$proposition
) {
	$sql = "UPDATE `jeuVideo`
	SET `jeuVideo`.`titre` = :t, `jeuVideo`.`version` = :v, `jeuVideo`.`dateSortie` = :ds, 
	`jeuVideo`.`datePublication` = :dp, `jeuVideo`.`imageEncode` = :ie, 
	`jeuVideo`.`description` = :d, `jeuVideo`.`proposition` = :p WHERE `jeuVideo`.`idJeuVideo` = :ij";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(
			":ij" => $idJeuVideo, ":t" => $titre, ":v" => $version, ":ds" => $dateSortie,
			":dp" => $datePublication, ":ie" => $imageEncode,
			":d" => $description, ":p" => $proposition
		));
	} catch (PDOException $e) {
		return false;
	}
	// Done
	return true;
}

/**
 * Modifie les données supplémentaires dans les tables de liaisons du jeu vidéo dans la base de données
 *
 * @return bool true si la requête a été correctement effectué, sinon false 
 */
function ModifierJeuAvecLiaisons(
	$idJeuVideo,
	$idPegi,
	$idGenre,
	$idPlateforme
) {
	$sqlPegi = "UPDATE `liaison_pegi_jeu`
	SET `jeuVideo`.`idPegi` = :pe, WHERE `jeuVideo`.`idJeuVideo` = :ij";
	$sqlGenre = "UPDATE `video_game_club`.`liaison_pegi_genre`
	SET `jeuVideo`.`idGenre` = :ig, WHERE `jeuVideo`.`idJeuVideo` = :ij";
	$sqlPlateforme = "UPDATE `video_game_club`.`liaison_pegi_plateforme`
	SET `jeuVideo`.`idPlateforme` = :pl, WHERE `jeuVideo`.`idJeuVideo` = :ij";

	$statementPegi = EBaseDonnee::prepare($sqlPegi, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$statementGenre = EBaseDonnee::prepare($sqlGenre, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$statementPlateforme = EBaseDonnee::prepare($sqlPlateforme, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statementPegi->execute(array(
			":ij" => $idJeuVideo, ":pe" => $idPegi
		));
		$statementGenre->execute(array(
			":ij" => $idJeuVideo, ":ig" => $idGenre
		));
		$statementPlateforme->execute(array(
			":ij" => $idJeuVideo, ":pl" => $idPlateforme
		));
	} catch (PDOException $e) {
		return false;
	}
	// Done
	return true;
}

/**
 * Fonction sur la recherche
 *
 * @param string $motCle
 * @return array|bool Un tableau des EJeuVideo
 *                    False si une erreur
 */
function RechercherJeu($motCle, $genre, $plateforme, $ageMin, $ageMax)
{
	$arr = array();
	$sql = "SELECT `jeuVideo`.`idJeuVideo`, `jeuVideo`.`titre`, `jeuVideo`.`version`, `jeuVideo`.`dateSortie`, 
	`jeuVideo`.`datePublication`, `jeuVideo`.`imageEncode`, `jeuVideo`.`description`, `jeuVideo`.`trancheAge`,
	 `jeuVideo`.`proposition`, 

	GROUP_CONCAT(DISTINCT `genre`.`nomGenre` SEPARATOR \", \") AS `genres`, 
	GROUP_CONCAT(DISTINCT `plateforme`.`nomPlateforme` SEPARATOR \", \") AS `plateformes`,
	GROUP_CONCAT(DISTINCT `pegi`.`contenuSensible` SEPARATOR \", \") AS `contenu sensible`

    FROM `jeuVideo` 

    
    JOIN `liaison_genre_jeu` ON `jeuvideo`.`idJeuVideo` = `liaison_genre_jeu`.`idJeuVideo`
    JOIN `genre` ON `liaison_genre_jeu`.`idGenre` = `genre`.`idGenre` 
    
    JOIN `liaison_plateforme_jeu` ON `jeuvideo`.`idJeuVideo` = `liaison_plateforme_jeu`.`idJeuVideo`
    JOIN `plateforme` ON `liaison_plateforme_jeu`.`idPLateforme` = `plateforme`.`idPLateforme` 
    
	JOIN `liaison_pegi_jeu` ON `jeuvideo`.`idJeuVideo` = `liaison_pegi_jeu`.`idJeuVideo`
    JOIN `pegi` ON `liaison_pegi_jeu`.`idPegi` = `pegi`.`idPegi` 

    WHERE `jeuvideo`.`titre` LIKE '%$motCle%' AND `genre`.`nomGenre` LIKE '%$genre%' AND `plateforme`.`nomPlateforme` LIKE '%$plateforme%'
	AND `jeuvideo`.`trancheAge` <= $ageMax AND `jeuvideo`.`trancheAge` >= $ageMin AND `jeuVideo`.`proposition` = 0
	
	GROUP BY `jeuvideo`.`idJeuVideo`
	ORDER BY `jeuVideo`.`dateSortie` DESC LIMIT 20;";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute();
	} catch (PDOException $e) {
		return false;
	}
	while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
		$c = new EJeuVideo(
			intval($row['idJeuVideo']),
			$row['titre'],
			$row['version'],
			$row['dateSortie'],
			$row['datePublication'],
			$row['imageEncode'],
			$row['description'],
			$row['proposition'],
			$row['genres'],
			$row['plateformes'],
			$row['trancheAge'],
			$row['contenu sensible']

		);
		array_push($arr, $c);
	}
	return $arr;
}

/**
 * Fonction pour récupéré tout les genres dans un tableau
 *
 * @return array|bool Un tableau des EGenre
 *                    False si une erreur
 */
function RecupereGenre()
{
	$arr = array();
	$sql = "SELECT `genre`.`idGenre`, `genre`.`nomGenre` FROM `genre`";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute();
	} catch (PDOException $e) {
		return false;
	}
	while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
		$c = new EGenre(
			intval($row['idGenre']),
			$row['nomGenre']
		);
		array_push($arr, $c);
	}
	return $arr;
}

/**
 * Fonction pour récupéré toute les plateformes dans un tableau
 *
 * @return array|bool Un tableau des EPlateforme
 *                    False si une erreur
 */
function RecuperePlateforme()
{
	$arr = array();
	$sql = "SELECT `plateforme`.`idPlateforme`, `plateforme`.`nomPlateforme` FROM `plateforme`";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute();
	} catch (PDOException $e) {
		return false;
	}
	while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
		$c = new EPlateforme(
			intval($row['idPlateforme']),
			$row['nomPlateforme']
		);
		array_push($arr, $c);
	}
	return $arr;
}

/**
 * Fonction pour récupéré toute les contenus sensibles et les mettres dans un tableau
 *
 * @return array|bool Un tableau des EPlateforme
 *                    False si une erreur
 */
function RecupereContenuSensible()
{
	$arr = array();
	$sql = "SELECT `pegi`.`idPegi`, `pegi`.`contenuSensible` FROM `pegi`";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute();
	} catch (PDOException $e) {
		return false;
	}
	while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
		$c = new EPegi(
			intval($row['idPegi']),
			$row['contenuSensible']
		);
		array_push($arr, $c);
	}
	return $arr;
}