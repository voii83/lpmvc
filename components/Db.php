<?php

class Db
{

	public static function getConnection()
	{
		$paramsPatch = ROOT . '/config/db_params.php';
		$params = include($paramsPatch);

		$dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
		$db = new PDO($dsn, $params['user'], $params['password']);

		return $db;
	}

}