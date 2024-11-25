<?php

namespace ProcessWire;

require_once 'index.php';





$date = $_POST['affinaj_date'];
$point = $_POST['affinaj_point'];
$idpoint = $_POST['affinaj_idpoint'];
$worker = $_POST['affinaj_worker'];
$affinaj_id = $_POST['affinaj_id'];

$o375 = $_POST['o375'];
$o333 = $_POST['o333'];
$o417 = $_POST['o417'];
$o500 = $_POST['o500'];
$o585 = $_POST['o585'];
$o620 = $_POST['o620'];
$o750 = $_POST['o750'];
$o800 = $_POST['o800'];
$o850 = $_POST['o850'];
$o875 = $_POST['o875'];
$o900 = $_POST['o900'];
$o916 = $_POST['o916'];
$o958 = $_POST['o958'];
$o990 = $_POST['o990'];

$n375 = $_POST['n375'];
$n333 = $_POST['n333'];
$n417 = $_POST['n417'];
$n500 = $_POST['n500'];
$n585 = $_POST['n585'];
$n620 = $_POST['n620'];
$n750 = $_POST['n750'];
$n800 = $_POST['n800'];
$n850 = $_POST['n850'];
$n875 = $_POST['n875'];
$n900 = $_POST['n900'];
$n916 = $_POST['n916'];
$n958 = $_POST['n958'];
$n990 = $_POST['n990'];

$array = array(
	'375' => $o375 . '||' . $n375,
	'333' => $o333 . '||' . $n333,
	'417' => $o417 . '||' . $n417,
	'500' => $o500 . '||' . $n500,
	'585' => $o585 . '||' . $n585,
	'620' => $o620 . '||' . $n620,
	'750' => $o750 . '||' . $n750,
	'800' => $o800 . '||' . $n800,
	'850' => $o850 . '||' . $n850,
	'875' => $o875 . '||' . $n875,
	'900' => $o900 . '||' . $n900,
	'916' => $o916 . '||' . $n916,
	'958' => $o958 . '||' . $n958,
	'990' => $o990 . '||' . $n990,
);

// echo '<pre>';
// print_r($array);
// echo '</pre>';
// foreach ($array as $key => $value) {
// 	echo $key;
// 	$data_array = explode("||", $value);
// 	echo $data_array[0];
// 	echo $data_array[1];
// }

if ($n375 == '' || $n333 == '' || $n417 == '' || $n500 == '' || $n585 == '' || $n620 == '' || $n750 == '' || $n800 == '' || $n850 == '' || $n875 == '' || $n900 == '' || $n916 == '' || $n958 == '' || $n990 == '') {
    echo '<p id="result_add" class="messages" style="color: red;">Ошибка. Аффинаж не изменен.<br>Проверьте заполненность полей и повторите попытку.</p>';    
} else {
	//$affinaj_id = '1413';
	//Записываем изменения в лог
	$affedt = $pages->get('id=' . $affinaj_id . '');
	$log = '';
    $log .= date("Y-m-d H:i") . ' Был изменен аффинаж: ' . $affedt->title . ', со статусом - ' . $affedt->product_status . ' === ';
    $log .= 'Запись изменил: ' . $worker . ', ID записи: ' . $affinaj_id; 
    file_put_contents(__DIR__ . '/site/templates/log_affinaj_edit.txt', $log . PHP_EOL, FILE_APPEND);

	//Редактируем запись
	foreach ($array as $key => $value) {
		$data_array = explode("||", $value);
		$affedt = $pages->get('id=' . $affinaj_id . '')->affinaj_table->get('proba=' . $key . '');
		$affedt->weight = $data_array[1];
			//Изменяем остатки
		    $point_actual_table = $pages->get('id_point=' . $idpoint . '_actual');
		    $edit_page = $point_actual_table->get('title=' . $key . '');
		    $result = $edit_page->remain + $data_array[0] - $data_array[1];
		    //echo $edit_page->remain . ' + ' . $data_array[0] . ' - ' . $data_array[1] . ' = ' . $result . '<br>';
		    	//записываем в лог
			    $log = 'Проба ' . $key . ' изменена с ' . $data_array[0] . ' на ' . $data_array[1];
			    file_put_contents(__DIR__ . '/site/templates/log_affinaj_edit.txt', $log . PHP_EOL, FILE_APPEND);
		    $edit_page->of(false);
		    $edit_page->remain = $result;
		    $edit_page->save();
		$affedt->save();
		$pages->get('id=' . $affinaj_id . '')->affinaj_table->add($affedt);
	}

	//Сообщение для пользователя
	echo '<p id="result_add" class="messages" style="color: green;">Аффинаж изменен</p>';
	echo '<p id="affinaj_id" class="messages uk-margin-remove">' . $affinaj_id . '</p>';
	echo '<p class="messages uk-margin-remove">ID: ' . $affinaj_id . '</p>';
	echo '<p class="messages uk-margin-remove">Cтатус: Открыт</p>';
	echo '<a class="uk-margin-small uk-button uk-button-default" href="/affinazh-raskhod/">Открытые и отправленные аффинажи</a>';
}