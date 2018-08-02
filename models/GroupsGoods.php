<?php


class GroupsGoods
{
	public static function delGroupGoods()
	{
		$db = Db::getConnection();
		$report = [];
		$result = $db->prepare('DELETE FROM group_goods');
		$report[] = $result->execute();
	}

	public static function setGroupGoods($groupList)
	{
		$db = Db::getConnection();
		$report = [];
		$sql = 'INSERT INTO group_goods (id, name, id_parent, alias)
 				VALUES (:id, :name, :id_parent, :alias)';

		$result = $db->prepare($sql);

		foreach ($groupList as $group) {
			$result->bindValue(':id', $group['ID'], PDO::PARAM_STR);
			$result->bindValue(':name', $group['NAME'], PDO::PARAM_STR);
			$result->bindValue(':id_parent', $group['ID_PARENT'], PDO::PARAM_STR);
			$result->bindValue(':alias', $group['ALIAS'], PDO::PARAM_STR);
			$report[] = $result->execute();
		}
		return $report;
	}

	public static function getGroupGoodsAll()
	{
		$groupGoods = [];
		$groupGoodsTree = [];

		$db = Db::getConnection();
		$sql = 'SELECT id, name, id_parent, alias FROM group_goods';
		$result = $db->query($sql, PDO::FETCH_ASSOC);

		while ($row = $result->fetch()) {
			$groupGoods[$row['id']] = [
				"ID" => $row['id'],
				"NAME" => $row['name'],
				"ID_PARENT" => $row['id_parent'],
				"ALIAS" => $row['alias'],
			];
		}

		foreach ($groupGoods as $id=>&$group) {
			if (!$group['ID_PARENT']) {
				$groupGoodsTree[$id] = &$group;
			} else {
				$groupGoods[$group['ID_PARENT']]['CHILD'][$id] = &$group;
			}
		}
		return $groupGoodsTree;
	}
}