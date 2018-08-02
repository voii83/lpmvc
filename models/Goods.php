<?php

class Goods
{
	public static function setGoods($goodsList)
	{
		$db = Db::getConnection();
		$report = [];

		$sql = 'INSERT INTO goods (id, name, id_group)VALUES (:id, :name, :id_group) ON DUPLICATE KEY UPDATE name = :name, id_group = :id_group';

		$result = $db->prepare($sql);

		foreach ($goodsList as $goods) {
			$result->bindValue(':id', $goods['ID'], PDO::PARAM_STR);
			$result->bindValue(':name', $goods['NAME'], PDO::PARAM_STR);
			$result->bindValue(':id_group', $goods['ID_GROUP'], PDO::PARAM_STR);
			$report[] = $result->execute();
		}
		return $report;
	}

	public static function setGoodsProperties($goodsList)
	{
		$db = Db::getConnection();
		$report = [];

		$sql = 'INSERT INTO goods_properties (id_goods, id_property, id_property_value)
 				VALUES (:id_goods, :id_property, :id_property_value)
 				ON DUPLICATE KEY UPDATE id_property_value = :id_property_value';

		$result = $db->prepare($sql);

		foreach ($goodsList as $goods) {
			$result->bindValue(':id_goods', $goods['ID'], PDO::PARAM_STR);
			foreach ($goods['PROP'] as $goodsProperty) {
				$result->bindValue(':id_property', $goodsProperty['ID_PROP'], PDO::PARAM_STR);
				if (!is_array($goodsProperty['VALUE'])) {
					$result->bindValue(':id_property_value', $goodsProperty['VALUE'], PDO::PARAM_STR);
					$report[] = $result->execute();
				}
			}
		}
		return $report;
	}

	public static function getAllGoods()
	{
		$productListId = [];
		$db = Db::getConnection();
		$sql = 'SELECT goods.id, goods.name, goods_properties.id_property_value, properties.name AS property_name, properties_value.value AS property_value
				FROM goods
				LEFT JOIN goods_properties ON goods.id = goods_properties.id_goods
				LEFT JOIN properties ON goods_properties.id_property = properties.id
				LEFT JOIN properties_value ON goods_properties.id_property_value = properties_value.id';
		$result = $db->query($sql, PDO::FETCH_ASSOC);
		$id = "";
		while ($row = $result->fetch()) {

			if ($row['property_value']) {
				$property_value = $row['property_value'];
			} else {
				$property_value = $row['id_property_value'];
			}

			if ($row['id'] != $id) {
				$productListId[$row['id']]["GOODS"] = [
					"ID" => $row['id'],
					"NAME" => $row['name'],
				];
				$productListId[$row['id']]["PROPERTY"][] = [
						"PROPERTY_NAME" => $row['property_name'],
						"PROPERTY_VALUE" => $property_value,
				];
			} else {
				$productListId[$row['id']]["PROPERTY"][] = [
					"PROPERTY_NAME" => $row['property_name'],
					"PROPERTY_VALUE" => $property_value,
				];
			}
			$id = $row['id'];
		}
		return $productListId;
	}

	public static function getGoodsByGroup($group)
	{
		$productByGroup = [];
		$db = Db::getConnection();
		$sql = 'SELECT goods.id, goods.name, goods_properties.id_property_value, properties.name AS property_name, properties_value.value AS property_value
				FROM goods
				LEFT JOIN group_goods ON group_goods.id = goods.id_group
				LEFT JOIN goods_properties ON goods.id = goods_properties.id_goods
				LEFT JOIN properties ON goods_properties.id_property = properties.id
				LEFT JOIN properties_value ON goods_properties.id_property_value = properties_value.id
				WHERE group_goods.alias='."'".$group."'";

		$result = $db->query($sql, PDO::FETCH_ASSOC);
		$id = "";
		while ($row = $result->fetch()) {

			if ($row['property_value']) {
				$property_value = $row['property_value'];
			} else {
				$property_value = $row['id_property_value'];
			}

			if ($row['id'] != $id) {
				$productByGroup[$row['id']]["GOODS"] = [
						"ID" => $row['id'],
						"NAME" => $row['name'],
				];
				$productByGroup[$row['id']]["PROPERTY"][] = [
						"PROPERTY_NAME" => $row['property_name'],
						"PROPERTY_VALUE" => $property_value,
				];
			} else {
				$productByGroup[$row['id']]["PROPERTY"][] = [
						"PROPERTY_NAME" => $row['property_name'],
						"PROPERTY_VALUE" => $property_value,
				];
			}
			$id = $row['id'];
		}
		return $productByGroup;
	}
}