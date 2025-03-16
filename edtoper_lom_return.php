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
$proba_return = $_POST['proba_return'];
$weight_return = $_POST['weight_return'];

if ($proba_return == '' || $weight_return == '' || $id_edit_operation == '') {
    echo '<p id="result_arrear_add" class="messages" style="color: red;">Ошибка. Данные не измененны.<br>Проверьте заполненность полей и повторите попытку.</p>';    
} else {
    //Записываем изменения в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Внесены изменения по металлу при внесении правок в операцию ID=' . $id_edit_operation . ', возврат пробы - ' . $proba_return . ' - ' . $weight_return . 'г - ' . $selected_point . ' === ';
    $log .= 'Оператор: ' . $operator; 
    file_put_contents(__DIR__ . '/site/templates/log_edit_lomcash.txt', $log . PHP_EOL, FILE_APPEND);

    //Изменяем остатки
    $point_actual_table = $pages->get('id_point=' . $selected_id_point . '_actual');
    $edit_page = $point_actual_table->get('title=' . $proba_return . '');

    $result = $edit_page->remain + $weight_return;

    $edit_page->of(false);
    $edit_page->remain = $result;
    $edit_page->save();

	//Сообщение для пользователя
	echo '<p id="result_arrear_add" class="messages" style="color: green;">Возврат металла осуществлен</p>';
}