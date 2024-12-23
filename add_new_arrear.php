<?php

namespace ProcessWire;

require_once 'index.php';





$arrear_date = $_POST['arrear_date'];
$arrear_point = $_POST['arrear_point'];
$arrear_idpoint = $_POST['arrear_idpoint'];
$arrear_worker = $_POST['arrear_worker'];
$arrear_person = $_POST['arrear_person'];
$arrear_sum = $_POST['arrear_sum'];
$arrear_descript = $_POST['arrear_descript'];

if ($arrear_date == '' || $arrear_point == '' || $arrear_idpoint == '' || $arrear_worker == '' || $arrear_person == '' || $arrear_sum == '' || $arrear_descript == '') {
    echo '<p id="result_arrear_add" class="messages" style="color: red;">Ошибка. Долг не зарегестрирован.<br>Проверьте заполненность полей и повторите попытку.</p>';    
} else {
	//$operation_id = '00000';
	//Регестрируем запись
	$pages->add('arrears_itm', 1872 , [
    'title' => date("Y-m-d H:i") . ' Долг - ' . $arrear_person . ' - ' . $arrear_sum . '',
    'product_status' => 'Открыт',
    'date' => $arrear_date,
    'point' => $arrear_point,
    'id_point' => $arrear_idpoint,
    'worker' => $arrear_worker,
    'client_name' => $arrear_person,
    'sum' => $arrear_sum,
    'description_operation' => $arrear_descript,
    ]);
    $operation_page = $pages->get('title=' . date("Y-m-d H:i") . ' Долг - ' . $arrear_person . ' - ' . $arrear_sum . '');
    $operation_id = $operation_page->id;

	//Записываем регистрацию в лог
	$log = '';
    $log .= date("Y-m-d H:i") . ' Долг - ' . $arrear_person . ' - ' . $arrear_sum . ' === ';
    $log .= 'Запись занесена: ' . $arrear_worker . ', ID записи: ' . $operation_id . ', Описание долга: ' . $arrear_descript; 
    file_put_contents(__DIR__ . '/site/templates/log_arrears.txt', $log . PHP_EOL, FILE_APPEND);

	//Сообщение для пользователя
	//echo $arrear_date . $arrear_point . $arrear_idpoint . $arrear_worker . $arrear_person . $arrear_sum . $arrear_descript;
	echo '<p id="result_arrear_add" class="messages" style="color: green;">Долг зарегестрирован</p>';
	echo '<p class="messages uk-margin-remove">ID: ' . $operation_id . '</p>';
	echo '<p class="messages uk-margin-remove">Cтатус: Открыт</p>';
}