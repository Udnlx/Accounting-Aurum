<?php

$today = date("d-m-Y"); 

if ($actual_date == $today) {
	// echo 'Дата совпадает, актуальную дату не перезаписываем';
} else {
	// echo 'Дата не совпадает, актуальную дату перезаписываем';
	$points = ['point1','point2','point3','point4','point5'];
	foreach ($points as $point) {
		$remains_point_startday = $pages->get('template=remains_point, id_point=' . $point . '_startday');
		$remains_point_startday->of(false);
		$remains_point_startday->actual_date = $today;
		$remains_point_startday->shift_status = '';
		$remains_point_startday->save();

		$remains_point_startday = $pages->get('template=remains_point, id_point=' . $point . '_actual');
		$remains_point_startday->of(false);
		$remains_point_startday->actual_date = $today;
		$remains_point_startday->shift_status = '';
		$remains_point_startday->save();

		$remains_point_startday = $pages->get('template=remains_point, id_point=' . $point . '_reserv');
		$remains_point_startday->of(false);
		$remains_point_startday->actual_date = $today;
		$remains_point_startday->shift_status = '';
		$remains_point_startday->save();
	}

	$actual_date = $today;
	include 'remains_table_newday.php';
	$page_newday = $pages->get('template=new_day');
	$pages->add('new_day_itm', $page_newday , [
		    'title' => $actual_date,
		    'data_archive' => $data_archive,
		    'data_coming' => $data_coming,
		    'data_actual' => $data_actual,
	    ]);
}

?>