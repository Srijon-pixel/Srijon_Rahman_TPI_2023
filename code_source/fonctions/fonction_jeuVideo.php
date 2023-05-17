<?php

/**
 * Auteur: Mofassel Haque Srijon Rahman
 * Date: 04.05.2023
 * Projet: TPI video game club
 * Détail: Regroupe toutes les fonctionnalités pour les jeux vidéo du sites
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/bd/base_de_donnee.php'; // connection à la base de données
//Les classes nécesssaire pour afficher, ajouter, modifier ou supprimer les données.
require_once $_SERVER['DOCUMENT_ROOT'] . '/classe/jeuVideo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classe/genre.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classe/plateforme.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classe/pegi.php';



/**
 * Récupère touts les jeux vidéo de la base de donnée
 *
 * @return array|bool Un tableau des EJeuVideo
 *                    False si une erreur
 */
function RecupereJeuxVideoPublie()
{
	$tableau = array();

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

	WHERE `jeuVideo`.`proposition` = 0 AND `jeuVideo`.`datePublication` <= CURRENT_DATE()
    GROUP BY `jeuvideo`.`idJeuVideo`
	ORDER BY `jeuVideo`.`datePublication` DESC LIMIT 20;";
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
		array_push($tableau, $c);
	}

	// Done
	return $tableau;
}

/**
 * Récupère le nombre de jeux vidéo de la base de données
 *
 * @return array|bool Un tableau des EJeuVideo
 *                    False si une erreur
 */
function RecupereNombreJeux()
{
	$tableau = array();
	$sql = "SELECT COUNT(`jeuvideo`.`idJeuVideo`) AS `Nombre de jeu` FROM `jeuvideo`;";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute();
	} catch (PDOException $e) {
		return false;
	}
	// On parcoure les enregistrements 
	while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
		$c = array($row["Nombre de jeu"]);
		array_push($tableau, $c);
	}

	return $tableau;
}


/**
 * Récupère les données d'un jeu vidéo à l'aide de son identifiant
 * param int $idJeuVideo l'identifiant du jeu vidéo
 * @return array|bool Un tableau des EJeuVideo
 *                    False si une erreur
 */
function RecupereJeuVideoParId($idJeuVideo)
{
	$tableau = array();

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
		array_push($tableau, $c);
	}

	// Done
	return $tableau;
}


/**
 * Récupère le jeu vidéo proposé dans la base de données
 *
 * @return array|bool Un tableau des EJeuVideo
 *                    False si une erreur
 */
function RecupereJeuVideoProposer()
{
	$tableau = array();

	$sql = "SELECT `jeuVideo`.`idJeuVideo`, `jeuVideo`.`titre`, `jeuVideo`.`version`, `jeuVideo`.`dateSortie`, 
	`jeuVideo`.`datePublication`,  `jeuVideo`.`imageEncode`, `jeuVideo`.`description`, `jeuVideo`.`trancheAge`,
	 `jeuVideo`.`proposition`,

	GROUP_CONCAT(DISTINCT `genre`.`idGenre` SEPARATOR \", \") AS `genres`, 
	GROUP_CONCAT(DISTINCT `plateforme`.`idPlateforme` SEPARATOR \", \") AS `plateformes`,
	GROUP_CONCAT(DISTINCT `pegi`.`idPegi` SEPARATOR \", \") AS `contenu sensibles`
    FROM `jeuVideo` 
    
    JOIN `liaison_genre_jeu` ON `jeuvideo`.`idJeuVideo` = `liaison_genre_jeu`.`idJeuVideo`
    JOIN `genre` ON `liaison_genre_jeu`.`idGenre` = `genre`.`idGenre` 
    
    JOIN `liaison_plateforme_jeu` ON `jeuvideo`.`idJeuVideo` = `liaison_plateforme_jeu`.`idJeuVideo`
    JOIN `plateforme` ON `liaison_plateforme_jeu`.`idPLateforme` = `plateforme`.`idPLateforme` 
    
	JOIN `liaison_pegi_jeu` ON `jeuvideo`.`idJeuVideo` = `liaison_pegi_jeu`.`idJeuVideo`
    JOIN `pegi` ON `liaison_pegi_jeu`.`idPegi` = `pegi`.`idPegi` 

    WHERE `jeuVideo`.`proposition` = 1
	GROUP BY `jeuvideo`.`idJeuVideo`";
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
			$row['contenu sensibles']

		);
		// On place l'objet EJeuVideo créé dans le tableau
		array_push($tableau, $c);
	}

	// Done
	return $tableau;
}



/**
 * Insère le jeu vidéo dans la base de donnée
 * @param string $titre le titre du jeu
 * @param string $version la version du jeu
 * @param string $dateSortie la date de sortie du jeu
 * @param string $imageContenu le contenu de l'image du jeu
 * @param string $imageType le type de l'image du jeu
 * @param string $description la description du du jeu
 * @param string $trancheAge la tranche d'âge du jeu
 * @return int|false Retourne l'identifiant du dernier jeu ajouté, sinon false 
 */
function AjouterJeu(
	$titre,
	$version,
	$dateSortie,
	$imageContenu,
	$imageType,
	$description,
	$trancheAge
) {
	$sql = "INSERT INTO `jeuVideo` (`titre`, `version`,`dateSortie`,`datePublication`,
	`imageEncode`,`description`,`trancheAge`,`proposition`) 
	VALUES(:t,:v,:ds,null,:ie,:d,:ta,1)";
	$imageEncode = 'data:' . $imageType . ';base64,' . base64_encode($imageContenu);
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(
			":t" => $titre, ":v" => $version, ":ds" => $dateSortie,
			":ie" => $imageEncode, ":d" => $description, ":ta" => $trancheAge
		));
	} catch (PDOException $e) {
		return false;
	}
	// Retourner l'identifiant du jeu vidéo ajouté
	return EBaseDonnee::lastInsertId();
}


/**
 * Insère les données supllémentaires dans les tables de liasons dans la base de donnée

 * @param int $idJeuVideo l'identifiant du jeu
 * @param array $tableauIdPegi les différents identifiant pour les contenus sensible
 * @param array $tableauIdGenre les différents identifiant pour les genres
 * @param array $tableauIdPlateforme les différents identifiant pour les plateformes
 * @return bool true si l'insertion a été correctement effectué, sinon false 
 */
function AjouterJeuAvecLiaisons(
	$idJeuVideo,
	$tableauIdPegi,
	$tableauIdGenre,
	$tableauIdPlateforme
) {
	$sqlPegi = "INSERT INTO `liaison_pegi_jeu` (`idJeuVideo`,`idPegi`) 
	VALUES(:ij,:ipe)";
	$sqlGenre = "INSERT INTO `liaison_genre_jeu` (`idJeuVideo`,`idGenre`) 
	VALUES(:ij,:ig)";
	$sqlPlateforme = "INSERT INTO `liaison_plateforme_jeu` (`idJeuVideo`,`idPlateforme`) 
	VALUES(:ij,:ipl)";


	$statementPegi = EBaseDonnee::prepare($sqlPegi, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$statementGenre = EBaseDonnee::prepare($sqlGenre, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$statementPlateforme = EBaseDonnee::prepare($sqlPlateforme, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

	try {
		for ($i = 0; $i < count($tableauIdPegi); $i++) {
			$contenuSensible = intval($tableauIdPegi[$i]);
			$statementPegi->execute(array(":ij" => $idJeuVideo, ":ipe" => $contenuSensible));
		}
		for ($i = 0; $i < count($tableauIdGenre); $i++) {
			$nomGenre = intval($tableauIdGenre[$i]);
			$statementGenre->execute(array(":ij" => $idJeuVideo, ":ig" => $nomGenre));
		}
		for ($i = 0; $i < count($tableauIdPlateforme); $i++) {
			$nomPlateforme = intval($tableauIdPlateforme[$i]);
			$statementPlateforme->execute(array(":ij" => $idJeuVideo, ":ipl" => $nomPlateforme));
		}
	} catch (PDOException $e) {
		return false;
	}
	return true;
}

/** 
 * Supprime définitivement le jeu vidéo de la base de donnée 
 * @param int $idJeuvideo identifiant du jeu
 * @return bool true si la suppression a été correctement effectué, sinon false 
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
 * @param int $idJeuvideo identifiant du jeu
 * @param string $titre le titre du jeu
 * @param string $version la version du jeu
 * @param string $dateSortie la date de sortie du jeu
 * @param string $datePublication la date de publication du jeu
 * @param string $imageContenu le contenu de l'image du jeu
 * @param string $imageType le type de l'image du jeu
 * @param string $description la description du du jeu
 * @param string $trancheAge la tranche d'âge du jeu
 * @return bool true si la requête a été correctement effectué, sinon false 
 */
function ModifierJeu(
	$idJeuVideo,
	$titre,
	$version,
	$dateSortie,
	$datePublication,
	$imageContenu,
	$imageType,
	$description,
	$trancheAge
) {
	$sql = "UPDATE `jeuVideo`
	SET `jeuVideo`.`titre` = :t, `jeuVideo`.`version` = :v, `jeuVideo`.`dateSortie` = :ds, 
	`jeuVideo`.`datePublication` = :dp, `jeuVideo`.`imageEncode` = :ie, 
	`jeuVideo`.`description` = :d, `jeuVideo`.`trancheAge` = :ta, `jeuVideo`.`proposition` = 0 WHERE `jeuVideo`.`idJeuVideo` = :ij";
	$imageEncode = 'data:' . $imageType . ';base64,' . base64_encode($imageContenu);
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(
			":ij" => $idJeuVideo, ":t" => $titre, ":v" => $version, ":ds" => $dateSortie,
			":dp" => $datePublication, ":ie" => $imageEncode,
			":d" => $description, ":ta" => $trancheAge
		));
	} catch (PDOException $e) {
		return false;
	}
	return true;
}

/**
 * Modifie les données supplémentaires dans les tables de liaisons du jeu vidéo dans la base de données
 * @param int $idJeuVideo l'identifiant du jeu
 * @param array $tableauIdPegi les différents identifiant pour les contenus sensible
 * @param array $tableauIdGenre les différents identifiant pour les genres
 * @param array $tableauIdPlateforme les différents identifiant pour les plateformes
 * @return bool true si la requête a été correctement effectué, sinon false 
 */
function ModifierJeuAvecLiaisons(
	$idJeuVideo,
	$tableauIdPegi,
	$tableauIdGenre,
	$tableauIdPlateforme
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
		for ($i = 0; $i < count($tableauIdPegi); $i++) {
			$contenuSensible = intval($tableauIdPegi[$i]);
			$statementPegi->execute(array(":ij" => $idJeuVideo, ":ipe" => $contenuSensible));
		}
		for ($i = 0; $i < count($tableauIdGenre); $i++) {
			$nomGenre = intval($tableauIdGenre[$i]);
			$statementGenre->execute(array(":ij" => $idJeuVideo, ":ig" => $nomGenre));
		}
		for ($i = 0; $i < count($tableauIdPlateforme); $i++) {
			$nomPlateforme = intval($tableauIdPlateforme[$i]);
			$statementPlateforme->execute(array(":ij" => $idJeuVideo, ":ipl" => $nomPlateforme));
		}
	} catch (PDOException $e) {
		return false;
	}
	// Done
	return true;
}

/**
 * Valide le jeu et le rend affichable dans le site
 *
 * @param int $idJeuVideo l'identifiant du jeu
 * @return bool true si la requête a été correctement effectué, sinon false 
 */
function ValiderJeu($idJeuVideo)
{
	$sql = "UPDATE `jeuVideo`
	SET `jeuVideo`.`proposition` = 0 WHERE `jeuVideo`.`idJeuVideo` = :ij";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(
			":ij" => $idJeuVideo
		));
	} catch (PDOException $e) {
		return false;
	}
	return true;
}

/**
 * Fonction sur la recherche d'un jeu vidéo
 *
 * @param string $titreCle le titre recherché
 * @param string $genre le ou les genres recherché(s)
 * @param string $plateforme la ou les plateformes recherchée(s)
 * @param string $ageMin l'âge minimum recherché
 * @param string $ageMax l'âge maximum recherché
 * @return array|bool Un tableau des EJeuVideo
 *                    False si une erreur
 */
function RechercherJeu($titreCle, $genre, $plateforme, $ageMin, $ageMax)
{
	$tableau = array();
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

    WHERE `jeuvideo`.`titre` LIKE '%$titreCle%' AND `genre`.`nomGenre` LIKE '%$genre%' AND `plateforme`.`nomPlateforme` LIKE '%$plateforme%'
	AND `jeuvideo`.`trancheAge` <= $ageMax AND `jeuvideo`.`trancheAge` >= $ageMin AND `jeuVideo`.`proposition` = 0
	
	GROUP BY `jeuvideo`.`idJeuVideo`
	ORDER BY `jeuVideo`.`datePublication` DESC LIMIT 20;";
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
		array_push($tableau, $c);
	}
	return $tableau;
}

/**
 * Fonction pour récupéré tout les genres dans un tableau
 *
 * @return array|bool Un tableau des EGenre
 *                    False si une erreur
 */
function RecupereGenre()
{
	$tableau = array();
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
		array_push($tableau, $c);
	}
	return $tableau;
}

/**
 * Fonction pour récupéré toute les plateformes dans un tableau
 *
 * @return array|bool Un tableau des EPlateforme
 *                    False si une erreur
 */
function RecuperePlateforme()
{
	$tableau = array();
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
		array_push($tableau, $c);
	}
	return $tableau;
}

/**
 * Fonction pour récupéré toute les contenus sensibles et les mettres dans un tableau
 *
 * @return array|bool Un tableau des EPlateforme
 *                    False si une erreur
 */
function RecupereContenuSensible()
{
	$tableau = array();
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
		array_push($tableau, $c);
	}
	return $tableau;
}
