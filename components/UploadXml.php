<?php

require_once(ROOT.'/components/Translation.php');

class UploadXml
{

	private $uploadUrl;
	private $uploadDir;

	public function __construct($uploadUrl, $uploadDir)
	{
		$this->uploadUrl = $uploadUrl;
		$this->uploadDir = $uploadDir;
	}

	public function getArrXmlFiles()
	{
		ob_start();
		$this->pathXmlFiles($this->uploadDir, $this->uploadUrl);
		$files = iconv("cp1251", "UTF-8", ob_get_clean());
		$files = explode("~", $files);
		array_pop($files);

		$arFilesUpload = [];

		foreach ($files as $file) {
			$arFilesUpload[] = $file;
		}

		return $arFilesUpload;
	}

	/* Возвращает массив свойств товара */
	public function loadProperties($xml)
	{
		$xml = json_decode(json_encode($xml), TRUE);
		$arrProperties = [];

		foreach ($xml["Классификатор"]["Свойства"]["Свойство"] as $item) {
			$arrProperties["PROPERTY"][] = [
					"ID" => $item["Ид"],
					"NAME" => $item["Наименование"]
			];

			if ($item["ТипЗначений"] == "Справочник") {
				foreach ($item["ВариантыЗначений"]["Справочник"] as $value) {

					$arrProperties["VALUES"][] = [
						'ID' => $value['ИдЗначения'],
						'VALUE' => $value['Значение']
					];

				}
			}
		}
		return $arrProperties;
	}

	/* Возвращает массив товаров */
	public function loadGoods($xml)
	{
		$xml = json_decode(json_encode($xml), TRUE);
		$arrGoods = [];

		foreach ($xml["Каталог"]["Товары"]["Товар"] as $item) {
			$arrGoods[$item["Ид"]] = [
					"ID" => $item["Ид"],
					"NAME" => $item["Наименование"],
					"ARTICLE" => $item["Артикул"],
					"DESCRIPTION" => $item["Описание"],
					"ID_GROUP" => $item["Группы"]["Ид"],
			];

			// foreach для фото
			if (isset($item["Картинка"]) && is_array($item["Картинка"])) {
				foreach ($item["Картинка"] as $value) {
					$arrGoods[$item["Ид"]]["IMAGE"][] = $value;
				}
			} elseif (isset($item["Картинка"]) && !is_array($item["Картинка"])) {
				$arrGoods[$item["Ид"]]["IMAGE"][] = $item["Картинка"];
			}

			// foreach для свойств
			foreach ($item["ЗначенияСвойств"]["ЗначенияСвойства"] as $value) {
				$arrGoods[$item["Ид"]]["PROP"][] = [
					"ID_PROP" => $value["Ид"],
					"VALUE" => $value["Значение"],
				];
			}
		}
		return $arrGoods;
	}

	/* Возвращает массив групп товаров */
	public function loadGroupsGoods($xml)
	{
		$xml = json_decode(json_encode($xml), TRUE);
		$arrGroupGoods = $this->getGroupsRecursive($xml['Классификатор']['Группы']["Группа"]["Группы"]);
		return $arrGroupGoods;
	}

	/* Рекурсивно обходит вложенные группы */
	private function getGroupsRecursive($arrGroupsInXml, $idParentGroups = '', $aliasParentGroups = '', &$arrOut=[])
	{
		$valueId = '';
		$valueName = '';
		$valueParent = '';
		$valueAlias = '';
		$partAlias = '';
		foreach ($arrGroupsInXml as $item) {

			if (isset($arrGroupsInXml['Группы']['Группа'])) {
				if (isset($arrGroupsInXml['Ид'])) {
					$idParentGroups = $arrGroupsInXml['Ид'];
				}
				else {
					$idParentGroups = '';
				}
				if (isset($arrGroupsInXml['Наименование'])) {

					if($arrGroupsInXml['Наименование'] != $partAlias) {
						$aliasParentGroups .= $arrGroupsInXml['Наименование'] . '-';
					}
					$partAlias = $arrGroupsInXml['Наименование'];
				}
			}

			if (isset($item['Ид'])) {
				$valueId = $item['Ид'];
				$valueParent = $idParentGroups;
			}
			if (isset($item['Наименование'])) {
				$valueName = $item['Наименование'];
				$valueAlias = $aliasParentGroups.$item['Наименование'];
			}

			if (isset($item['Ид']) && isset($item['Наименование'])) {
				$arrOut[] = [
					"ID" => $valueId,
					"NAME" => $valueName,
					"ID_PARENT" => $valueParent,
					"ALIAS" => Translation::lineTranslation($valueAlias),
				];
			}

			if (is_array($item)) {
				$this->getGroupsRecursive($item, $idParentGroups, $aliasParentGroups, $arrOut);
			}
		}
		return $arrOut;
	}

	private function pathXmlFiles($dir, $url)
	{
		$files = scandir($dir);

		foreach ($files as $file) {
			if ($file == "." || $file == ".." || $file == "import_files") continue;

			$path = $dir . "/" . $file;
			if (is_dir($path)) {
				$this->pathXmlFiles($path, $url);
			} else {
				echo $url . "/" . $path . "~";
			}
		}
	}

}