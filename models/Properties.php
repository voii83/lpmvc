<?php

class Properties
{

	public static function setProperties($propertyList)
{
	$db = Db::getConnection();
	$report = [];

	$sql = 'INSERT INTO properties (id, name)VALUES (:id, :name) ON DUPLICATE KEY UPDATE name = :name';

	$result = $db->prepare($sql);

	foreach ($propertyList as $property) {
		$result->bindValue(':id', $property['ID'], PDO::PARAM_STR);
		$result->bindValue(':name', $property['NAME'], PDO::PARAM_STR);
		$report[] = $result->execute();
	}
	return $report;
}

	public static function setPropertiesValue($propertyValue)
	{
		$db = Db::getConnection();
		$report = [];

		$sql = 'INSERT INTO properties_value (id, value)VALUES (:id, :value) ON DUPLICATE KEY UPDATE value = :value';

		$result = $db->prepare($sql);

		foreach ($propertyValue as $property) {
				$result->bindValue(':id', $property['ID'], PDO::PARAM_STR);
			if (!is_array($property['VALUE'])) {
				$result->bindValue(':value', $property['VALUE'], PDO::PARAM_STR);
				$report[] = $result->execute();
			}
		}
		return $report;
	}

	public static function getProperties()
	{
		$db = DB::getConnection();
		$propertyList = [];

		$sql = 'SELECT * FROM properties';
		$result = $db->query($sql);

		$i = 0;
		while ($row = $result->fetch()) {
			$propertyList[$i]['id'] = $row['id'];
			$propertyList[$i]['name'] = $row['name'];
			$i++;
		}

		return $propertyList;
	}
}