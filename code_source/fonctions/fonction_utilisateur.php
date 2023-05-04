<?php

/**
 * Auteur: Mofassel Haque Srijon Rahman
 * Date: 27.04.2023
 * Projet: TPI video game club
 * Détail: Regroupe toutes les fonctionnalités pour les utilisateurs du sites
 */
require_once './bd/base_de_donnee.php';
require_once './classe/utilisateur.php';



/**
 * Récupère toutes les utilisateurs de la base de donnée
 *
 * @return array|bool Un tableau des EUtilisateur
 *                    False si une erreur
 */
function RecupereToutLesUtilisateurs()
{
	$arr = array();

	$sql = "SELECT `utilisateur`.`idUtilisateur`, `utilisateur`.`nom`, `utilisateur`.`prenom`, `utilisateur`.`pseudo`, 
	`utilisateur`.`email`,  `utilisateur`.`statut`, `utilisateur`.`motDePasse` FROM utilisateur";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute();
	} catch (PDOException $e) {
		return false;
	}
	// On parcoure les enregistrements 
	while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
		// On crée l'objet EUtilisateur en l'initialisant avec les données provenant
		// de la base de données
		$c = new EUtilisateur(
			intval($row['idUtilisateur']),
			$row['nom'],
			$row['prenom'],
			$row['pseudo'],
			$row['email'],
			$row['statut'],
			$row['motDePasse']

		);
		// On place l'objet EUtilisateur créé dans le tableau
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
function AjouterUtilisateur($nom, $prenom, $pseudo, $email, $motDePasse)
{
	$sql = "INSERT INTO `video_game_club`.`utilisateur` (`nom`, `prenom`, `pseudo`, `email`, `statut`, `motDePasse`) VALUES(:n,:pr,:ps,:e,0,:m)";
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
 * Supprime définitivement l'utilisateur de la base de donnée 
 * @param int $idUtilisateur identifiant unique de l'utilisateur
 */
function SupprimerUtilisateur($idUtilisateur)
{
	$sql = "DELETE FROM `video_game_club`.`utilisateur` WHERE `utilisateur`.`idUtilisateur` = :i";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(":i" => $idUtilisateur));
	} catch (PDOException $e) {
		return false;
	}
	// Fini
	return true;
}

/**
 * Modifie les données de l'utilisateur dans la base de donnée
 *
 * @return bool true si la requête a été correctement effectué, sinon false 
 */
function modifierUtilisateur($idUtilisateur, $nom, $prenom, $pseudo, $email)
{
	$sql = "UPDATE `video_game_club`.`utilisateur` SET `utilisateur`.`nom` = :n, `utilisateur`.`prenom` = :pr, 
	`utilisateur`.`pseudo` = :ps, `utilisateur`.`email` = :e WHERE `utilisateur`.`idUtilisateur` = :i";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(":i" => $idUtilisateur, ":n" => $nom, ":pr" => $prenom, ":ps" => $pseudo, ":e" => $email));
	} catch (PDOException $e) {
		return false;
	}
	// Done
	return true;
}

/**
 * Modifie le mot de passe de l'utilisateur dans la base de donnée
 *
 * @return bool true si la requête a été correctement effectué, sinon false 
 */
function modifierMotDePasse($idUtilisateur, $motDePasse)
{
	$sql = "UPDATE `video_game_club`.`utilisateur` SET `utilisateur`.`motDePasse` = :m WHERE `utilisateur`.`idUtilisateur` = :i";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(":i" => $idUtilisateur, ":m" => password_hash($motDePasse, PASSWORD_BCRYPT)));
	} catch (PDOException $e) {
		return false;
	}
	// Done
	return true;
}

/**
 * Modifie le mot de passe de l'utilisateur dans la base de donnée
 *
 * @return bool true si la requête a été correctement effectué, sinon false 
 */
function modifierEmailPseudo($idUtilisateur)
{
	$sql = "UPDATE `video_game_club`.`utilisateur` SET `utilisateur`.`pseudo` = \"\", `utilisateur`.`email` = \"\"
	 WHERE `utilisateur`.`idUtilisateur` = :i";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(":i" => $idUtilisateur));
	} catch (PDOException $e) {
		return false;
	}
	// Done
	return true;
}
/**
 * Vérifie si le mot de passe répond aux critères pour la syntax
 *
 * @param string $motDePasse le mot de passe de l'utilisateur
 * @return bool true si le mot de passe répond à tous les critères, sinon false 
 */
function motDePasseSyntax($motDePasse)
{
	$majuscule = preg_match('@[A-Z]@', $motDePasse);
	$minuscule = preg_match('@[a-z]@', $motDePasse);
	$nombre    = preg_match('@[0-9]@', $motDePasse);

	if ($majuscule && $minuscule && $nombre && strlen($motDePasse) >= 8) {
		return $motDePasse;
	}
	return false;
}

/**
 * Récupère toutes les données d'un utilisateur de la base de donnée
 *
 * @param integer $idUtilisateur L'identifiant de l'utilisateur
 * @return array|bool Un tableau des EUtilisateur
 *                    False si une erreur
 */
function RecuperationDonneeUtilisateur($idUtilisateur)
{
	$arr = array();
	$sql = "SELECT `utilisateur`.`idUtilisateur`, `utilisateur`.`nom`, `utilisateur`.`prenom`, `utilisateur`.`pseudo`, 
	`utilisateur`.`email`,  `utilisateur`.`statut`, `utilisateur`.`motDePasse` FROM utilisateur
    WHERE utilisateur.idUtilisateur = :i";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(':i' => $idUtilisateur));
	} catch (PDOException $e) {
		return false;
	}
	// On parcoure les enregistrements 
	while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
		$c = new EUtilisateur(
			intval($row['idUtilisateur']),
			$row['nom'],
			$row['prenom'],
			$row['pseudo'],
			$row['email'],
			$row['statut'],
			$row['motDePasse']

		);
		array_push($arr, $c);
	}
	return $arr;
}

function RecuperationDonneeUtilisateurParEmail($email)
{
	$arr = array();
	$sql = "SELECT `utilisateur`.`idUtilisateur`, `utilisateur`.`nom`, `utilisateur`.`prenom`, `utilisateur`.`pseudo`, 
	`utilisateur`.`email`,  `utilisateur`.`statut`, `utilisateur`.`motDePasse` FROM utilisateur
    WHERE utilisateur.email = :e";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(':e' => $email));
	} catch (PDOException $e) {
		return false;
	}
	// On parcoure les enregistrements 
	while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
		$c = new EUtilisateur(
			intval($row['idUtilisateur']),
			$row['nom'],
			$row['prenom'],
			$row['pseudo'],
			$row['email'],
			$row['statut'],
			$row['motDePasse']

		);
		array_push($arr, $c);
	}
	return $arr;
}

/**
 * Récupère l'identifiant de l'utilisateur à partir de son email
 *
 * @param string $email l'email de l'utilisateur
 * @return array|bool true si la requête a été correctement effectué, sinon false 
 */
function RecupereUtilisateurParEmail($email)
{
	$sql = "SELECT idUtilisateur FROM video_game_club.utilisateur  WHERE utilisateur.email = :e";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(':e' => $email));
	} catch (PDOException $e) {
		return false;
	}
	$row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
	return $row['idUtilisateur'];
}


function VerifieEmailSimilaire($email)
{
	$sql = "SELECT email FROM video_game_club.utilisateur  WHERE utilisateur.email = :e";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(':e' => $email));
	} catch (PDOException $e) {
		return false;
	}
	if ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
		return $row['email'];
	} else {
		return false;
	}
}

/**
 * 
 *
 * @param string $pseudo
 * @return array|bool true si la requête a été correctement effectué, sinon false 
 */
function VerifiePseudoSimilaire($pseudo)
{
	$sql = "SELECT pseudo FROM video_game_club.utilisateur  WHERE utilisateur.pseudo = :ps";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(':ps' => $pseudo));
	} catch (PDOException $e) {
		return false;
	}
	if ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
		return $row['pseudo'];
	} else {
		return false;
	}
}

/**
 * Vérifie si l'utilisateur existe dans la base de donnée
 *
 * @param string $emailUtilisateur l'email de l'utilisateur
 * @param string $motDePasseUtilisateur le mot de passe de l'utilisateur
 * @return bool true si l'utilisateur est bel et bien présent dans la base de donnée, sinon false
 */
function VerifieUtilisateurExiste($emailUtilisateur, $motDePasseUtilisateur)
{
	$registreUtilisateur = RecuperationDonneeUtilisateurParEmail($emailUtilisateur);
	foreach ($registreUtilisateur as $utilisateur) {
		if ($emailUtilisateur == $utilisateur->email) {
			if (password_verify($motDePasseUtilisateur, $utilisateur->motDePasse)) {
				return true;
			}
		}
	}
	return false;
}
/**
 *
 *
 * @param string $string chaîne de charactère récupèré
 * @return string
 */
function antiInjectionXSS($string)
{
	$string = htmlspecialchars($string, ENT_QUOTES);
	$string = strip_tags($string);
	$string = addslashes($string);
	return $string;
}