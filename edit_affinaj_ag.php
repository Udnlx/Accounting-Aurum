<?php

namespace ProcessWire;

require_once 'index.php';





$date = $_POST['affinaj_ag_date'];
$point = $_POST['affinaj_ag_point'];
$idpoint = $_POST['affinaj_ag_idpoint'];
$worker = $_POST['affinaj_ag_worker'];
$affinaj_id = $_POST['affinaj_ag_id'];

$oag = $_POST['oag'];
$oag875 = $_POST['oag800'];
$oag875 = $_POST['oag875'];
$oag925 = $_POST['oag925'];

$nag = $_POST['nag'];
$nag875 = $_POST['nag800'];
$nag875 = $_POST['nag875'];
$nag925 = $_POST['nag925'];

$array = array(
	'Ag' => $oag . '||' . $nag,
	'Ag-800' => $oag800 . '||' . $nag800,
	'Ag-875' => $oag875 . '||' . $nag875,
	'Ag-925' => $oag925 . '||' . $nag925,
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

if ($nag == '' || $nag875 == '' || $nag925 == '') {
    echo '<p id="result_add" class="messages" style="color: red;">Ошибка. Аффинаж не изменен.<br>Проверьте заполненность полей и повторите попытку.</p>';    
} else {
	//$affinaj_id = '1413';
	//Записываем изменения в лог
	$affedt = $pages->get('id=' . $affinaj_id . '');
	$log = '';
    $log .= date("Y-m-d H:i") . ' Был изменен аффинаж Ag: ' . $affedt->title . ', со статусом - ' . $affedt->product_status . ' === ';
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
	echo '<a class="uk-margin-small uk-button uk-button-default" href="/affinazh-ag-raskhod/">Открытые и отправленные аффинажи</a>';
}