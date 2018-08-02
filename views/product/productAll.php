<?php include_once ROOT. '/template/header.php'; ?>

<h1>Каталог продукции</h1>
<?php $i = 1; ?>
<?php foreach ($productsList as $itemProduct) { ?>
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