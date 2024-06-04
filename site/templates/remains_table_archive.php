<?php

$today = date("d-m-Y"); 

if ($actual_date == $today) {
	//echo 'Дата совпадает, архив не делаем';
} else {
	//echo 'Дата не совпадает, делаем архив';
	$data_archive = '';
	$remains_parent = $pages->get('template=remains');
	$remains_points = $remains_parent->children();
	foreach ($remains_points as $remains_point) {
		$data_archive .= ':::' . $remains_point->title . '::: ===' . $remains_point->type_remains . '===';
		$ramains_items = $remains_point->children();
		foreach ($ramains_items as $ramains_item) {
		$data_archive .= '/' . $ramains_item->title . '-' . $ramains_item->remain . '/';
		}
	}

	$pages->add('remains_archive_itm', 1045 , [
    'title' => $actual_date,
    'data_archive' => $data_archive,
    ]);

	$actual = $pages->get('id_point=point1_actual');
	$startday = $pages->get('id_point=point1_startday');
	$actual_items = $actual->children();
	$startday_items = $startday->children();
	foreach ($actual_items as $itm) {
	    $met_act_name = $itm->title;
	    $met_start_item = $startday_items->get('title=' . $itm->title . '');
	    $met_act_weight = $itm->remain;
	    $met_start_weight = $met_start_item->remain;
	    $met_start_item->of(false);
	    $met_start_item->remain = $met_act_weight;
	    $met_start_item->save();
	}

	$actual = $pages->get('id_point=point2_actual');
	$startday = $pages->get('id_point=point2_startday');
	$actual_items = $actual->children();
	$startday_items = $startday->children();
	foreach ($actual_items as $itm) {
	    $met_act_name = $itm->title;
	    $met_start_item = $startday_items->get('title=' . $itm->title . '');
	    $met_act_weight = $itm->remain;
	    $met_start_weight = $met_start_item->remain;
	    $met_start_item->of(false);
	    $met_start_item->remain = $met_act_weight;
	    $met_start_item->save();
	}

	$remains_parent = $pages->get('template=remains');
	$remains_points = $remains_parent->children();
	foreach ($remains_points as $remains_point) {
		$remains_point->of(false);
	    $remains_point->actual_date = $today;
	    $remains_point->save();
	}

	$actual_date = $today;
}

?>