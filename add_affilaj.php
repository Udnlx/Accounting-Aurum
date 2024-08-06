<?php

namespace ProcessWire;

require_once 'index.php';





$affinaj_id = date("YmdHi");
$date = $_POST['date'];
$point = $_POST['point'];
$idpoint = $_POST['idpoint'];
$worker = $_POST['worker'];
$arr = $_POST['arr'];
$all_operation = '';

if ($date == '' || $point == '' || $idpoint == '' || $worker == '' || $arr == '') {
    echo '<p class="messages" style="color: red;">Ошибка в данных. Аффилаж не зарегистрирован.<br>Обратитесь к администратору.</p>';    
} else {
	foreach ($arr as $affinaj) {
		$af_itm = explode(",", $affinaj);
		$proba = $af_itm[0];
		$weight = $af_itm[1];

		//Регестрируем запись
		$pages->add('affinaj_itm', 1266 , [
	    'title' => date("Y-m-d H:i") . ' Аффинаж - Расход - ' . $proba . ' - ' . $weight . 'г - ' . $point,
	    'affinaj_id' => $affinaj_id,
	    'type_operation' => 'Аффинаж',
	    'undertype_operation' => 'Расход',
	    'date' => $date,
	    'point' => $point,
	    'id_point' => $idpoint,
	    'worker' => $worker,
	    'proba' => $proba,
	    'weight' => $weight
	    ]);
	    $operation_page = $pages->get('title=' . date("Y-m-d H:i") . ' Аффинаж - Расход - ' . $proba . ' - ' . $weight . 'г - ' . $point . '');
	    $operation_id = $operation_page->id;
	    $all_operation .= $operation_page->title . '<br>';

		//Записываем регистрацию в лог
	    $log = '';
	    $log .= date("Y-m-d H:i") . ' Аффинаж - Расход - ' . $proba . ' - ' . $weight . 'г - ' . $point . ' === ';
	    $log .= 'Запись занесена: ' . $worker . ', ID записи: ' . $operation_id; 
	    file_put_contents(__DIR__ . '/site/templates/log_affinaj.txt', $log . PHP_EOL, FILE_APPEND);

		//Изменяем остатки
	    $point_actual_table = $pages->get('id_point=' . $idpoint . '_actual');
	    $edit_page = $point_actual_table->get('title=' . $proba . '');
	    // echo $edit_page . '<br>';
	    // echo $edit_page->remain . '<br>';
	    // echo $weight . '<br>';
	    $result = $edit_page->remain - $weight;
	    // echo $result;
	    $edit_page->of(false);
	    $edit_page->remain = $result;
	    $edit_page->save();
	}

	//Сообщение для пользователя
	echo '
	<p class="messages" style="color: green;">Аффилаж зарегистрирован успешно.</p>
	<p class="messages" style="color: green;">Индентификатор аффинажа: ' . $affinaj_id . '</p>
	<p class="uk-margin-remove" style="color: green; font-weight: 700;">Сформированны записи:</p>
	' . $all_operation . '
	';
}