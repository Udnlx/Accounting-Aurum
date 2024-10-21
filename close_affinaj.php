<?php

namespace ProcessWire;

require_once 'index.php';





$date = $_POST['affinaj_date'];
$point = $_POST['affinaj_point'];
$idpoint = $_POST['affinaj_idpoint'];
$worker = $_POST['affinaj_worker'];
$affinaj_id = $_POST['affinaj_id'];

$proba999 = $_POST['proba999'];

if ($proba999 <= 0) {
    echo '<p id="result_add" class="messages" style="color: red;">Ошибка. Аффинаж не закрыт.<br>Проверьте заполненность полей и повторите попытку.</p>';    
} else {
	//$affinaj_id = '1413';
	//Записываем изменения в лог
	$affedt = $pages->get('id=' . $affinaj_id . '');
	$log = '';
    $log .= date("Y-m-d H:i") . ' Закрыт аффинаж: ' . $affedt->title . ' === ';
    $log .= 'Закрыл: ' . $worker . ', ID записи: ' . $affinaj_id; 
    file_put_contents(__DIR__ . '/site/templates/log_affinaj.txt', $log . PHP_EOL, FILE_APPEND);

	//Изменяем статус на Отправлен
	$edit_page = $pages->get('template=affinaj_itm, id=' . $affinaj_id . '');
	$edit_page->of(false);
	$edit_page->product_status = 'Закрыт';
	$edit_page->weight = $proba999;
	$edit_page->save();

    //Изменяем остатки
    $point_actual_table = $pages->get('id_point=' . $idpoint . '_actual');
    $edit_page = $point_actual_table->get('title=999.9');
    // echo $edit_page . '<br>';
    // echo $edit_page->remain . '<br>';
    // echo $weight . '<br>';
    $result = $edit_page->remain + $proba999;
    // echo $result;
    $edit_page->of(false);
    $edit_page->remain = $result;
    $edit_page->save();

	//Сообщение для пользователя
	echo '<p id="result_add" class="messages" style="color: green;">Аффинаж закрыт</p>';
	echo '<p id="affinaj_id" class="messages uk-margin-remove">' . $affinaj_id . '</p>';
	echo '<p class="messages uk-margin-remove">ID: ' . $affinaj_id . '</p>';
	echo '<p class="messages uk-margin-remove">Cтатус: Закрыт</p>';
	echo '<a class="uk-margin-small uk-button uk-button-default" href="/affinazh-raskhod/">Открытые и отправленные аффинажи</a>';
}