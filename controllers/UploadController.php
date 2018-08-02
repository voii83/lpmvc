<?php

require_once(ROOT.'/components/UploadXml.php');
include_once ROOT. '/models/Properties.php';
include_once ROOT. '/models/Goods.php';
include_once ROOT. '/models/GroupsGoods.php';

class UploadController
{

	public function actionProperties()
	{
		$xmlPath = ROOT.'/config/xml_path.php';
		$paramsPatch = include($xmlPath);

		$objUploadXML = new uploadXML($paramsPatch['UPLOAD_URL'], $paramsPatch['UPLOAD_DIR']);

		foreach ($objUploadXML->getArrXmlFiles() as $xmlFile) {

			$xml = simplexml_load_file($xmlFile);


			if ($xml->Классификатор->Свойства) {
				$arProperties = $objUploadXML->loadProperties($xml);
				$resultProperties = Properties::setProperties($arProperties["PROPERTY"]);
				$resultPropertiesValue = Properties::setPropertiesValue($arProperties["VALUES"]);
				debug($resultPropertiesValue);
			}

			if ($xml->Каталог->Товары) {
				$arGoods = $objUploadXML->loadGoods($xml);
				$resultGoods = Goods::setGoods($arGoods);
				$resultGoodsProperties = Goods::setGoodsProperties($arGoods);
				debug($resultGoodsProperties);
			}

			if ($xml->Классификатор->Группы) {
				$arGroups = $objUploadXML->loadGroupsGoods($xml);
				GroupsGoods::delGroupGoods();
				$resultGroup = GroupsGoods::setGroupGoods($arGroups);
				debug($resultGroup);
			}
		}
	}

}