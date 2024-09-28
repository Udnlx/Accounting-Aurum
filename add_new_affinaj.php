<?php

namespace ProcessWire;

require_once 'index.php';





$date = $_POST['affinaj_date'];
$point = $_POST['affinaj_point'];
$idpoint = $_POST['affinaj_idpoint'];
$worker = $_POST['affinaj_worker'];

$p375 = $_POST['p375'];
$p333 = $_POST['p333'];
$p417 = $_POST['p417'];
$p500 = $_POST['p500'];
$p585 = $_POST['p585'];
$p620 = $_POST['p620'];
$p750 = $_POST['p750'];
$p800 = $_POST['p800'];
$p850 = $_POST['p850'];
$p875 = $_POST['p875'];
$p900 = $_POST['p900'];
$p916 = $_POST['p916'];
$p958 = $_POST['p958'];
$p990 = $_POST['p990'];

$array = array(
	'375' => $p375,
	'333' => $p333,
	// '417' => $p417,
	// '500' => $p500,
	// '585' => $p585,
	// '620' => $p620,
	// '750' => $p750,
	// '800' => $p800,
	// '850' => $p850,
	// '875' => $p875,
	// '900' => $p900,
	// '916' => $p916,
	// '958' => $p958,
	// '990' => $p990,
);

// echo '<pre>';
// print_r($array);
// echo '</pre>';

if ($p375 == '' || $p333 == '' || $p417 == '' || $p500 == '' || $p585 == '' || $p620 == '' || $p750 == '' || $p800 == '' || $p850 == '' || $p875 == '' || $p900 == '' || $p916 == '' || $p958 == '' || $p990 == '') {
    echo '<p id="result_add" class="messages" style="color: red;">Ошибка. Аффинаж не зарегестрирован.<br>Проверьте заполненность полей и повторите попытку.</p>';    
} else {
	//Регестрируем запись
	$pages->add('affinaj_itm', 1266 , [
    'title' => date("Y-m-d H:i") . ' Аффинаж - Расход - ' . $point . '',
    'product_status' => 'Открыт',
    'type_operation' => 'Аффинаж',
    'undertype_operation' => 'Расход',
    'date' => $date,
    'point' => $point,
    'id_point' => $idpoint,
    'worker' => $worker,
    ]);
    $operation_page = $pages->get('title=' . date("Y-m-d H:i") . ' Аффинаж - Расход - ' . $point . '');
    $operation_id = $operation_page->id;

	foreach ($array as $key => $value) {
		$promo = $pages->get('id=' . $operation_id . '')->affinaj_table->getNew();
		$promo->proba = $key;
		$promo->weight = $value;
		$promo->save();
		$pages->get('id=' . $operation_id . '')->affinaj_table->add($promo);
	}

	//Сообщение для пользователя
	echo '<p id="result_add" class="messages" style="color: green;">Аффинаж зарегестрирован</p>';
	echo '<p class="messages uk-margin-remove">ID: ' . $operation_id . '</p>';
	echo '<p class="messages uk-margin-remove">Cтатус: Открыт</p>';
	echo '<a class="uk-margin-small uk-button uk-button-default" href="/affinazh-raskhod/">Открытые и отправленные аффинажи</a>';
}