<?php

$today = date("d-m-Y"); 

if ($actual_date == $today) {
	// echo 'Дата совпадает, архивы не делаем';
} else {
	// echo 'Дата не совпадает, делаем архив по таблицам';
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

	$actual_rew = $pages->get('id_point=point1_actual');
	$startday_rew = $pages->get('id_point=point1_startday');
	$actual_items = $actual_rew->children();
	$startday_items = $startday_rew->children();
	foreach ($actual_items as $itm) {
	    $met_start_item = $startday_items->get('title=' . $itm->title . '');
	    $met_start_item->of(false);
	    $met_start_item->remain = $itm->remain;
	    $met_start_item->save();
	}

	$actual_rew = $pages->get('id_point=point2_actual');
	$startday_rew = $pages->get('id_point=point2_startday');
	$actual_items = $actual_rew->children();
	$startday_items = $startday_rew->children();
	foreach ($actual_items as $itm) {
	    $met_start_item = $startday_items->get('title=' . $itm->title . '');
	    $met_start_item->of(false);
	    $met_start_item->remain = $itm->remain;
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

	//echo 'Дата не совпадает, делаем архив по кассам';
	$cash_data_archive = '';
	$cash_remains_parent = $pages->get('template=cash');
	$cash_remains_points = $cash_remains_parent->children();
	foreach ($cash_remains_points as $cash_remains_point) {
		$cash_data_archive .= ':::' . $cash_remains_point->title . '::: ===' . $cash_remains_point->sum . '===';
	}

	$pages->add('remains_archive_itm', 1550 , [
    'title' => $actual_date,
    'data_archive' => $cash_data_archive,
    ]);

    $cash_remains_parent = $pages->get('template=cash');
	$cash_remains_points = $cash_remains_parent->children();
	foreach ($cash_remains_points as $cash_remains_point) {
		$sum_on_startday = $cash_remains_point->sum;
		$cash_remains_point->of(false);
	    $cash_remains_point->cash_remain_startday = $sum_on_startday;
	    $cash_remains_point->save();
	}
}

?>