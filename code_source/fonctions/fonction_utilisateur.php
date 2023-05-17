<?php

/**
 * Auteur: Mofassel Haque Srijon Rahman
 * Date: 27.04.2023
 * Projet: TPI video game club
 * Détail: Regroupe toutes les fonctionnalités pour les utilisateurs du sites
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/bd/base_de_donnee.php'; // connection à la base de données
//La classe nécesssaire pour afficher, ajouter ou modifier les données.
require_once $_SERVER['DOCUMENT_ROOT'] . '/classe/utilisateur.php';




/**
 * Insère l'utilisateur dans la base de donnée
 * @param string $nom le nom de l'utilisateur
 * @param string $prenom le prenom de l'utilisateur
 * @param string $pseudo le pseudo de l'utilisateur
 * @param string $email l'email de l'utilisateur
 * @param string $motDePasse le mot de passe de l'utilisateur
 * @return bool true si l'insertion a été correctement effectué, sinon false 
 */
function AjouterUtilisateur($nom, $prenom, $pseudo, $email, $motDePasse)
{
	$sql = "INSERT INTO `utilisateur` (`nom`, `prenom`, `pseudo`, `email`, `statut`, `motDePasse`) VALUES(:n,:pr,:ps,:e,0,:m)";
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
 * Modifie les données de l'utilisateur dans la base de donnée
 * @param int $idUtilisateur l'identifiant de l'utilisateur
 * @param string $nom le nom de l'utilisateur
 * @param string $prenom le prenom de l'utilisateur
 * @param string $pseudo le pseudo de l'utilisateur
 * @param string $email l'email de l'utilisateur
 * @return bool true si la requête a été correctement effectué, sinon false 
 */
function ModifierUtilisateur($idUtilisateur, $nom, $prenom, $pseudo, $email)
{
	$sql = "UPDATE `utilisateur` SET `utilisateur`.`nom` = :n, `utilisateur`.`prenom` = :pr, 
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
 * @param int $idUtilisateur l'identifiant de l'utilisateur
 * @param string $motDePasse le mot de passe de l'utilisateur
 * @return bool true si la requête a été correctement effectué, sinon false 
 */
function ModifierMotDePasse($idUtilisateur, $motDePasse)
{
	$sql = "UPDATE `utilisateur` SET `utilisateur`.`motDePasse` = :m WHERE `utilisateur`.`idUtilisateur` = :i";
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
 * Supprime le pseudo et l'email de l'utilisateur dans la base de donnée à l'adie de son identifiant
 * @param int $idUtilisateur l'identifiant de l'utilisateur
 * @return bool true si la requête a été correctement effectué, sinon false 
 */
function ModifierEmailPseudo($idUtilisateur)
{
	$sql = "UPDATE `utilisateur` SET `utilisateur`.`pseudo` = \"\", `utilisateur`.`email` = \"\"
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
 * Vérifie si le mot de passe répond aux critères pour la syntax d'un mot de passe
 * @param string $motDePasse le mot de passe de l'utilisateur
 * @return bool true si le mot de passe répond à tous les critères, sinon false 
 */
function MotDePasseSyntax($motDePasse)
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
 * Récupère toutes les données d'un utilisateur de la base de donnée à l'aide de son identifiant
 *
 * @param integer $idUtilisateur L'identifiant de l'utilisateur
 * @return array|bool Un tableau des EUtilisateur
 *                    False si une erreur
 */
function RecuperationDonneeUtilisateur($idUtilisateur)
{
	$tableau = array();
	$sql = "SELECT `utilisateur`.`idUtilisateur`, `utilisateur`.`nom`, `utilisateur`.`prenom`, `utilisateur`.`pseudo`, 
	`utilisateur`.`email`,  `utilisateur`.`statut`, `utilisateur`.`motDePasse` FROM `utilisateur`
    WHERE utilisateur.idUtilisateur = :i";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(':i' => $idUtilisateur));
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
		array_push($tableau, $c);
	}
	// On place l'objet EUtilisateur créé dans le tableau
	return $tableau;
}

/**
 * Récupère toutes les données d'un utilisateur de la base de donnée à l'aide de son email
 *
 * @param integer $email L'email de l'utilisateur
 * @return array|bool Un tableau des EUtilisateur
 *                    False si une erreur
 */
function RecuperationDonneeUtilisateurParEmail($email)
{
	$tableau = array();
	$sql = "SELECT `utilisateur`.`idUtilisateur`, `utilisateur`.`nom`, `utilisateur`.`prenom`, `utilisateur`.`pseudo`, 
	`utilisateur`.`email`,  `utilisateur`.`statut`, `utilisateur`.`motDePasse` FROM `utilisateur`
    WHERE utilisateur.email = :e";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(':e' => $email));
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
		array_push($tableau, $c);
	}
	// On place l'objet EUtilisateur créé dans le tableau
	return $tableau;
}

/**
 * Récupère l'identifiant de l'utilisateur à partir de son email
 *
 * @param string $email l'email de l'utilisateur
 * @return array|bool true si la requête a été correctement effectué, sinon false 
 */
function RecupereUtilisateurParEmail($email)
{
	$sql = "SELECT `idUtilisateur` FROM `utilisateur`  WHERE `utilisateur`.`email` = :e";
	$statement = EBaseDonnee::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(':e' => $email));
	} catch (PDOException $e) {
		return false;
	}
	$row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
	return $row['idUtilisateur'];
}

/**
 * Vérifie si l'email n'existe pas déjà dans la base de donnée
 *
 * @param string $email le nouvel email de l'utilisateur
 * @return array|bool true si la requête a été correctement effectué, sinon false 
 */
function VerifieEmailSimilaire($email)
{
	$sql = "SELECT `email` FROM `utilisateur`  WHERE `utilisateur`.`email` = :e";
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
 * Vérifie si le pseudo n'existe pas déjà dans la base de donnée
 *
 * @param string $pseudo le nouveau pseudo de l'utilisateur
 * @return array|bool true si la requête a été correctement effectué, sinon false 
 */
function VerifiePseudoSimilaire($pseudo)
{
	$sql = "SELECT `pseudo` FROM `utilisateur`  WHERE `utilisateur`.`pseudo` = :ps";
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
 *	Fait en sorte de ne pas avoir d'injection XSS
 *
 * @param string $chaineCharactere chaîne de caractère récupèré
 * @return string
 */
function antiInjectionXSS($chaineCharactere)
{
	$chaineCharactere = htmlspecialchars($chaineCharactere, ENT_QUOTES);
	$chaineCharactere = strip_tags($chaineCharactere);
	$chaineCharactere = addslashes($chaineCharactere);
	return $chaineCharactere;
}
