<?php include_once ROOT. '/template/header.php'; ?>

	<h1>Каталог продукции</h1>
<?php // ниже не правильно, только до 2-го уровня вложенности, нужно неограниченно. Это для теста.?>
	<ul>
		<?php foreach ($goodsGroup as $item) : ?>
			<?php if ($item['CHILD']) : ?>
				<li><span><?= $item['NAME'] ?></span>
					<ul>
						<?php foreach ($item['CHILD'] as $childItem) : ?>
							<li><a href="/products/category/<?= $childItem['ALIAS'] ?>"><?= $childItem['NAME'] ?></a></li>
						<?php endforeach; ?>
					</ul>
				</li>
			<?php else : ?>
				<li><a href="/products/category/<?= $item['ALIAS'] ?>"><?= $item['NAME'] ?></a></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>

	<hr>

<?php $i = 1; ?>
<?php foreach ($productListByGroup as $itemProduct) { ?>
	<div>
		<h3><?= $itemProduct['GOODS']['NAME'] ." - ". $i ?></h3>
		<ul>
			<?php foreach ($itemProduct['PROPERTY'] as $itemProductsProperty) { ?>
				<li><?= $itemProductsProperty['PROPERTY_NAME'] ." ---- ".$itemProductsProperty['PROPERTY_VALUE'] ?></li>
			<?php } ?>
		</ul>
	</div>
	<?php $i++; ?>
<?php } ?>

<?php include_once ROOT. '/template/footer.php'; ?>