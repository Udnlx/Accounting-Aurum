<?php

namespace ProcessWire;

require_once 'index.php';





$date = $_POST['date'];
$point = $_POST['point'];
$idpoint = $_POST['idpoint'];
$worker = $_POST['worker'];
$arr = $_POST['arr'];

if ($date == '' || $point == '' || $idpoint == '' || $worker == '' || $arr == '') {
    echo '<p class="messages" style="color: red;">Ошибка в данных. Аффилаж не зарегистрирован.<br>Обратитесь к администратору.</p>';    
} else {
	//Работа с данными

	//Запись в лог

	//Сообщение для пользователя
	echo '
	<p class="messages" style="color: green;">Аффилаж зарегистрирован успешно.</p>
	<p class="messages" style="color: green;">Индентификатор аффинажа: 202407011912</p>
	<p class="messages" style="color: green;">Сформированны записи:</p>
	';
}