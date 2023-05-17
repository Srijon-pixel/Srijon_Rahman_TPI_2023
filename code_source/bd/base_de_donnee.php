<?php
/**
* Auteur: dominique@aigroz.com
* Date: 17.05.2023
* Projet: TPI video game club
* Détail: Permet la connexion entre le site et la base de donnée
*/

/**
 * @remark Mettre le bon chemin d'accès à votre fichier contenant les constantes
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/conparam.php';

class EBaseDonnee
{
	/**
	 * @var PDO L'instance de l'objet PDO en static à l'aide de la fonction RecuperInstance()
	 */
	private static $objInstance;
	/**
	 * @brief Crée une nouvelle connection à la base de donnée si il n'y en a pas encore
	 *  Met en privé pour que personne puisser faire une nouvelle instance via ' = new EDatabase();'
	 */
	private function __construct()
	{
	}
	/**
	 * @brief	Comme pour le constructeur, on fait  __clone en privé pour que personne puisse instancié le colne
	 */
	private function __clone()
	{
	}
	/**
	 * @brief Retourne une instance de la base de donnée ou créé une nouvelle connection
	 * @return $objInstance;
	 */
	private static function RecuperInstance()
	{
		if (!self::$objInstance) {
			try {

				$dsn = EDB_DBTYPE . ':host=' . EDB_HOST . ';port=' . EDB_PORT . ';dbname=' . EDB_DBNOM;
				self::$objInstance = new PDO($dsn, EDB_UTILISATEUR, EDB_MOTDEPASSE, array('charset' => 'utf8'));
				self::$objInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (PDOException $e) {
				echo "EDatabase Error: " . $e;
			}
		}
		return self::$objInstance;
	}
	/**
	 * @brief Transmet tous les appels statiques à cette classe sur l'instance PDO singleton
	 * @param 	$chrMethode		La méthod à appellé
	 * @param 	$tableauArguments	La méthode en paramètre
	 * @return 	$mix			La valeur retourné de la méthode
	 */
	final public static function __callStatic($chrMethode, $tableauArguments)
	{
		$objInstance = self::RecuperInstance();
		return call_user_func_array(array($objInstance, $chrMethode), $tableauArguments);
	}
}
