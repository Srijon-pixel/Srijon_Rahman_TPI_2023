<?php

/**
 * Auteur: Srijon Rahman
 * Date: 27.04.2023
 * Projet: TPI video game club
 * Détail: Regroupe toutes les fonctionnalités pour les utilisateurs du sites
 */
require_once './db/database.php';
require_once './class/user.php';



/**
 * Récupère toutes les utilisateurs de la base de donnée
 *
 * @return array|bool Un tableau des EUser
 *                    False si une erreur
 */
function getAllUser()
{
	$arr = array();

	$sql = "SELECT `users`.`id_user`, `users`.`username`, `users`.`email`, 
	`users`.`password`, `users`.`actif`, `users`.`admin` FROM users WHERE users.actif = 1";
	$statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute();
	} catch (PDOException $e) {
		return false;
	}
	// On parcoure les enregistrements 
	while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
		// On crée l'objet EUser en l'initialisant avec les données provenant
		// de la base de données
		$c = new EUser(
			intval($row['id_user']),
			$row['username'],
			$row['email'],
			$row['password'],
			$row['actif'] = 1,
			$row['admin']

		);
		// On place l'objet EUser créé dans le tableau
		array_push($arr, $c);
	}

	// Done
	return $arr;
}



/**
 * Insère l'utilisateur dans la base de donnée
 *
 * @param integer $username le nom de l'utilisateur
 * @param string $email l'email de l'utilisateur
 * @param string $password le mot de passe de l'utilisateur
 * @return bool true si l'insertion a été correctement effectué, sinon false 
 */
function addUser($username, $email, $password)
{
	$sql = "INSERT INTO `db_caps`.`users` (`username`, `email`, `password`) VALUES(:u,:e,:p)";
	$statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(":u" => $username, ":e" => $email, ":p" => password_hash($password, PASSWORD_BCRYPT)));
	} catch (PDOException $e) {
		return false;
	}
	// Done
	return true;
}

/**
 * Rend le compte de l'utilisateur inactif et donc impossible de se connecter avec
 *
 * @param integer $idUser l'identifiant de l'utilisateur
 * @return bool true si la requête a été correctement effectué, sinon false 
 */
function desactivateUser($idUser)
{
	$sql = "UPDATE db_caps.users SET users.actif = 0 WHERE `users`.`id_user` = :u";
	$statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(":u" => $idUser));
	} catch (PDOException $e) {
		return false;
	}
	// Done
	return true;
}

/**
 * Modifie les données de l'utilisateur dans la base de donnée
 *
 * @param integer $idUser L'identifiant de l'utilisateur
 * @param string $username le nom de l'utilisateur
 * @param string $email l'email de l'utilisateur
 * @param string $password le mot de passe de l'utilisateur
 * @return bool true si la requête a été correctement effectué, sinon false 
 */
function modifyUser($idUser, $username, $email, $password)
{
	$sql = "UPDATE `db_caps`.`users` SET users.username = :n, users.email = :e, users.password = :p  
	WHERE `users`.`id_user` = :u";
	$statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(":u" => $idUser, ":n" => $username, ":e" => $email, ":p" => password_hash($password, PASSWORD_BCRYPT)));
	} catch (PDOException $e) {
		return false;
	}
	// Done
	return true;
}

/**
 * Vérifie si le mot de passe répond aux critères pour la syntax
 *
 * @param string $password le mot de pass de l'utilisateur
 * @return bool true si le mot de passe répond à tous les critères, sinon false 
 */
function CheckPasswordSyntax($password)
{
	$uppercase = preg_match('@[A-Z]@', $password);
	$lowercase = preg_match('@[a-z]@', $password);
	$number    = preg_match('@[0-9]@', $password);
	$specialChars = preg_match('@[^\w]@', $password);

	if ($uppercase && $lowercase && $number && $specialChars && strlen($password) >= 8) {
		return $password;
	}
	return false;
}

/**
 * Récupère toutes les données d'un utilisateur de la base de donnée
 *
 * @param integer $idUser L'identifiant de l'utilisateur
 * @return array|bool Un tableau des EUser
 *                    False si une erreur
 */
function getDataUserById($idUser)
{
	$arr = array();
	$sql = "SELECT users.id_user, users.username, users.email, users.password, `users`.`actif`, `users`.`admin` FROM db_caps.users
    WHERE users.id_user = :i and users.actif = 1";
	$statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(':i' => $idUser));
	} catch (PDOException $e) {
		return false;
	}
	// On parcoure les enregistrements 
	while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
		$c = new EUser(
			intval($row['id_user']),
			$row['username'],
			$row['email'],
			$row['password'],
			$row['actif'],
			$row['admin']
		);
		array_push($arr, $c);
	}
	return $arr;
}

/**
 * Récupère l'identifiant de l'utilisateur à partir de son email
 *
 * @param string $email l'email de l'utilisateur
 * @return bool true si la requête a été correctement effectué, sinon false 
 */
function getUserIdByEmail($email)
{
	$sql = "SELECT id_user FROM db_caps.users  WHERE users.email = :e and users.actif = 1";
	$statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute(array(':e' => $email));
	} catch (PDOException $e) {
		return false;
	}
	$row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
	return $row['id_user'];
}

/**
 * Vérifie si l'utilisateur existe dans la base de donnée
 *
 * @param string $emailUser l'email de l'utilisateur
 * @param string $passwordUser le mot de passe de l'utilisateur
 * @return bool true si l'utilisateur est bel et bien présent dans la base de donnée, sinon false
 */
function CheckUserExistInDB($emailUser, $passwordUser)
{
	$recordsUser = getAllUser();
	foreach ($recordsUser as $user) {
		if ($emailUser == $user->email) {
			if (password_verify($passwordUser, $user->password)) {
				return true;
			}
		}
	}
	return false;
}
