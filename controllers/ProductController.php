<?php

include_once ROOT. '/models/Goods.php';
include_once ROOT. '/models/GroupsGoods.php';

class ProductController
{

	public function actionIndex()
	{
		$goodsGroup = [];
		$goodsGroup = GroupsGoods::getGroupGoodsAll();
		require_once(ROOT. "/views/product/index.php");
		return true;
	}

	public function actionCategory($alias)
	{
		$goodsGroup = [];
		$productListByGroup = [];

		$goodsGroup = GroupsGoods::getGroupGoodsAll();
		$productListByGroup = Goods::getGoodsByGroup($alias);
		require_once(ROOT. "/views/product/productByCategory.php");
		return true;
	}

	public function actionProductAll()
	{
		$productsList = [];
		$productsList = Goods::getAllGoods();
		require_once(ROOT. "/views/product/productAll.php");
		return true;
	}

}