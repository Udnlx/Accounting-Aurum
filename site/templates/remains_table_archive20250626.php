<?php

$today = date("d-m-Y"); 

if ($actual_date == $today) {
	// echo 'Дата совпадает, актуальную дату не перезаписываем';
} else {
	// echo 'Дата не совпадает, актуальную дату перезаписываем';
	$remains_point_startday = $pages->get('template=remains_point, id_point=' . $selected_id_point . '_startday');
	$remains_point_startday->of(false);
	$remains_point_startday->actual_date = $today;
	$remains_point_startday->shift_status = '';
	$remains_point_startday->save();

	$remains_point_startday = $pages->get('template=remains_point, id_point=' . $selected_id_point . '_actual');
	$remains_point_startday->of(false);
	$remains_point_startday->actual_date = $today;
	$remains_point_startday->shift_status = '';
	$remains_point_startday->save();

	$remains_point_startday = $pages->get('template=remains_point, id_point=' . $selected_id_point . '_reserv');
	$remains_point_startday->of(false);
	$remains_point_startday->actual_date = $today;
	$remains_point_startday->shift_status = '';
	$remains_point_startday->save();

	$actual_date = $today;
}

?>