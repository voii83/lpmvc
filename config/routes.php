<?php

return [

	'exchange.*' => 'exchange/index',
	'upload-prop' => 'upload/properties',

	'products/category/(([a-z0-9-])*([a-z0-9])+)' => 'product/category/$1',
	'products' => 'product/index',
	'products-all' => 'product/productAll',
	'news' => 'news/index',
	'' => 'index/index',

];