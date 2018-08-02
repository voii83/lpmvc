<?php


class News
{

	/**
	 * Returns single news item with specified id
	 * @param integer $id
	 */
	public static function getNewsItemById($id)
	{
		$id = intval($id);
		$db = Db::getConnection();
		if ($id) {
			$result = $db->query('SELECT * FROM news WHERE id=' . $id);
			$result->setFetchMode(PDO::FETCH_ASSOC);
			$newsItem = $result->fetch();

			return $newsItem;
		}
	}

	/**
	 * Returns an array of news items
	 */
	public static function getNewsList()
	{
		$newsList = [];
		$db = Db::getConnection();
		$result = $db->query('SELECT id, title, date, short_content '
			. 'FROM news '
			. 'ORDER BY date DESC '
			. 'LIMIT 10'
		);

		$i = 0;
		$result->setFetchMode(PDO::FETCH_ASSOC);
		while($row = $result->fetch()) {
			$newsList[$i]['id'] = $row['id'];
			$newsList[$i]['title'] = $row['title'];
			$newsList[$i]['date'] = $row['date'];
			$newsList[$i]['short_content'] = $row['short_content'];
			$i++;
		}

		return $newsList;
	}

}