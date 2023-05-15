<?php

/**
 * @remark Mettre le bon chemin d'accès à votre fichier contenant les constantes
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/conparam.php';

class EBaseDonnee
{

	private static $objInstance;

	private function __construct()
	{
	}

	private function __clone()
	{
	}

	private static function getInstance()
	{
		if (!self::$objInstance) {
			try {

				$dsn = EDB_DBTYPE . ':host=' . EDB_HOST . ';port=' . EDB_PORT . ';dbname=' . EDB_DBNAME;
				self::$objInstance = new PDO($dsn, EDB_USER, EDB_PASS, array('charset' => 'utf8'));
				self::$objInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (PDOException $e) {
				echo "EDatabase Error: " . $e;
			}
		}
		return self::$objInstance;
	}

	final public static function __callStatic($chrMethod, $arrArguments)
	{
		$objInstance = self::getInstance();
		return call_user_func_array(array($objInstance, $chrMethod), $arrArguments);
	}
}
