<?php

namespace ProcessWire;

require_once 'index.php';





$date = $_POST['affinaj_ag_date'];
$point = $_POST['affinaj_ag_point'];
$idpoint = $_POST['affinaj_ag_idpoint'];
$worker = $_POST['affinaj_ag_worker'];

$fag = $_POST['fag'];
$fag875 = $_POST['fag875'];
$fag925 = $_POST['fag925'];

$pag = $_POST['pag'];
$pag875 = $_POST['pag875'];
$pag925 = $_POST['pag925'];

$array = array(
	'Ag' => $fag . '||' . $pag,
	'Ag-875' => $fag875 . '||' . $pag875,
	'Ag-925' => $fag925 . '||' . $pag925,
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

if ($pag == '' || $pag875 == '' || $pag925 == '') {
    echo '<p id="result_add" class="messages" style="color: red;">Ошибка. Аффинаж не зарегестрирован.<br>Проверьте заполненность полей и повторите попытку.</p>';    
} else {
	//$operation_id = '1375';
	//Регестрируем запись
	$pages->add('affinaj_itm_ag', 1266 , [
    'title' => date("Y-m-d H:i") . ' Аффинаж Ag - Расход - ' . $point . '',
    'product_status' => 'Открыт',
    'type_operation' => 'Аффинаж',
    'undertype_operation' => 'Расход',
    'date' => $date,
    'point' => $point,
    'id_point' => $idpoint,
    'worker' => $worker,
    ]);
    $operation_page = $pages->get('title=' . date("Y-m-d H:i") . ' Аффинаж Ag - Расход - ' . $point . '');
    $operation_id = $operation_page->id;

    //Добавляем позиции в запись и вычитаем материал из остатков
	foreach ($array as $key => $value) {
		$data_array = explode("||", $value);
		$affadd = $pages->get('id=' . $operation_id . '')->affinaj_table->getNew();
		$affadd->proba = $key;
		$affadd->fweight = $data_array[0];
		$affadd->weight = $data_array[1];
			//Изменяем остатки
		    $point_actual_table = $pages->get('id_point=' . $idpoint . '_actual');
		    $edit_page = $point_actual_table->get('title=' . $affadd->proba . '');
		    $result = $edit_page->remain - $affadd->weight;
		    $edit_page->of(false);
		    $edit_page->remain = $result;
		    $edit_page->save();
		$affadd->save();
		$pages->get('id=' . $operation_id . '')->affinaj_table->add($affadd);
	}

	//Записываем регистрацию в лог
	$log = '';
    $log .= date("Y-m-d H:i") . ' Аффинаж Ag - Расход - ' . $point . ' === ';
    $log .= 'Запись занесена: ' . $worker . ', ID записи: ' . $operation_id; 
    file_put_contents(__DIR__ . '/site/templates/log_affinaj.txt', $log . PHP_EOL, FILE_APPEND);

	//Сообщение для пользователя
	echo '<p id="result_add" class="messages" style="color: green;">Аффинаж зарегестрирован</p>';
	echo '<p id="operation_id" class="messages uk-margin-remove">' . $operation_id . '</p>';
	echo '<p class="messages uk-margin-remove">ID: ' . $operation_id . '</p>';
	echo '<p class="messages uk-margin-remove">Cтатус: Открыт</p>';
	echo '<a class="uk-margin-small uk-button uk-button-default" href="/affinazh-ag-raskhod/">Открытые и отправленные аффинажи по серебру</a>';
}