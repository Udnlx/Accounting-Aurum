<?php

namespace ProcessWire;

require_once 'index.php';





if(isset($_SESSION['operator'])){
    $operator = $_SESSION['operator'];
} else {
    $operator = 'no_operator';
}

if(isset($_SESSION['point'])){
    $selected_point = $_SESSION['point'];
} else {
    $selected_point = 'no_point';
}

if(isset($_SESSION['id_point'])){
    $selected_id_point = $_SESSION['id_point'];
} else {
    $selected_id_point = 'no_id_point';
}

$id_edit_operation = $_POST['id_edit_operation'];
$id_edit_cash = $_POST['id_edit_cash'];
$sum_edit = $_POST['sum_edit'];

if ($id_edit_cash == '' || $sum_edit == '' || $id_edit_operation == '') {
    echo '<p id="result_arrear_add" class="messages" style="color: red;">Ошибка. Данные не измененны.<br>Проверьте заполненность полей и повторите попытку.</p>';    
} else {
    //Записываем изменения в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Внесены изменения по кассовой операции ID=' . $id_edit_cash . ' при внесении правок в операцию ID=' . $id_edit_operation . ', сумма изменилась на: ' . $sum_edit . 'р - ' . $selected_point . ' === ';
    $log .= 'Оператор: ' . $operator; 
    file_put_contents(__DIR__ . '/site/templates/log_edit_lomcash.txt', $log . PHP_EOL, FILE_APPEND);

    //Изменяем сумму в кассовой операции
    $edit_page = $pages->get('template=cash_operation, id=' . $id_edit_cash . '');

    $result = $edit_page->sum + $sum_edit;

    $edit_page_title = $edit_page->title;
    $data_array = explode(" - ", $edit_page_title);
    $new_title = $data_array[0] . ' - ' . $result . ' - ' . $data_array[2] . ' - Операция изменялась';

    $edit_page->of(false);
    $edit_page->title = $new_title;
    $edit_page->sum = $result;
    $edit_page->save();

    //Изменяем остатки в кассе
    $cash_card = $edit_page->cash_card;
    $cash_point = $edit_page->id_point;
    $edit_page = $pages->get('template=cash_itm, id_point=' . $cash_point . '_cash');
    if ($cash_card == 'Наличный расчет') {
        $result = $edit_page->sum + $sum_edit;
        $edit_page->of(false);
        $edit_page->sum = $result;
        $edit_page->save();
    }
    if ($cash_card == 'Безналичный расчет') {
        $result = $edit_page->bn_sum + $sum_edit;
        $edit_page->of(false);
        $edit_page->bn_sum = $result;
        $edit_page->save();
    }

	//Сообщение для пользователя
	echo '<p id="result_arrear_add" class="messages" style="color: green;">Изменения по кассе осуществлены</p>';
}