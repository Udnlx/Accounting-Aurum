<?php

namespace ProcessWire;

require_once 'index.php';





$date = $_POST['affinaj_date'];
$point = $_POST['affinaj_point'];
$idpoint = $_POST['affinaj_idpoint'];
$worker = $_POST['affinaj_worker'];

$f375 = $_POST['f375'];
$f333 = $_POST['f333'];
$f417 = $_POST['f417'];
$f500 = $_POST['f500'];
$f585 = $_POST['f585'];
$f620 = $_POST['f620'];
$f750 = $_POST['f750'];
$f800 = $_POST['f800'];
$f850 = $_POST['f850'];
$f875 = $_POST['f875'];
$f900 = $_POST['f900'];
$f916 = $_POST['f916'];
$f958 = $_POST['f958'];
$f990 = $_POST['f990'];

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
	'375' => $f375 . '||' . $p375,
	'333' => $f333 . '||' . $p333,
	'417' => $f417 . '||' . $p417,
	'500' => $f500 . '||' . $p500,
	'585' => $f585 . '||' . $p585,
	'620' => $f620 . '||' . $p620,
	'750' => $f750 . '||' . $p750,
	'800' => $f800 . '||' . $p800,
	'850' => $f850 . '||' . $p850,
	'875' => $f875 . '||' . $p875,
	'900' => $f900 . '||' . $p900,
	'916' => $f916 . '||' . $p916,
	'958' => $f958 . '||' . $p958,
	'990' => $f990 . '||' . $p990,
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

if ($p375 == '' || $p333 == '' || $p417 == '' || $p500 == '' || $p585 == '' || $p620 == '' || $p750 == '' || $p800 == '' || $p850 == '' || $p875 == '' || $p900 == '' || $p916 == '' || $p958 == '' || $p990 == '') {
    echo '<p id="result_add" class="messages" style="color: red;">Ошибка. Аффинаж не зарегестрирован.<br>Проверьте заполненность полей и повторите попытку.</p>';    
} else {
	//$operation_id = '1375';
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
    $log .= date("Y-m-d H:i") . ' Аффинаж - Расход - ' . $point . ' === ';
    $log .= 'Запись занесена: ' . $worker . ', ID записи: ' . $operation_id; 
    file_put_contents(__DIR__ . '/site/templates/log_affinaj.txt', $log . PHP_EOL, FILE_APPEND);

	//Сообщение для пользователя
	echo '<p id="result_add" class="messages" style="color: green;">Аффинаж зарегестрирован</p>';
	echo '<p id="operation_id" class="messages uk-margin-remove">' . $operation_id . '</p>';
	echo '<p class="messages uk-margin-remove">ID: ' . $operation_id . '</p>';
	echo '<p class="messages uk-margin-remove">Cтатус: Открыт</p>';
	echo '<a class="uk-margin-small uk-button uk-button-default" href="/affinazh-raskhod/">Открытые и отправленные аффинажи</a>';
}