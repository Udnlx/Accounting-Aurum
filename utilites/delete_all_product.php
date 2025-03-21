<?php

namespace ProcessWire;

require_once '../index.php';

ini_set('max_execution_time', 0);
ini_set('memory_limit', '4096M');

echo 'Delete all Tickets' . '<br>';

$all_products = $pages->find('template=product_itm, limit=10');
echo count($all_products) . '<br>';

foreach ($all_products as $product) {
	echo $product->title . '<br>';
	$del_page = $product;
	$pages->delete($del_page);
}
