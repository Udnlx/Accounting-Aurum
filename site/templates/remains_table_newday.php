<?php namespace ProcessWire;

//Получение остатков на конец прошлого дня в главной точке
$data_archive = '';
$actual_rew = $pages->get('id_point=point1_actual');
$actual_items = $actual_rew->children();
foreach ($actual_items as $itm) {
	$data_archive .= $itm->title . '=' . $itm->remain . ';';
}
// echo 'Остатки на конец прошлого дня в главной точке' . '<br>';
// echo $data_archive . '<br>';
//Получение остатков на конец прошлого дня в главной точке



//Получение прихода со всех второстепенных точек
$data_coming = '';
$points = ['point3','point4','point5'];
foreach ($points as $point) {
	$remains_point = $pages->get('template=remains_point, id_point=' . $point . '_actual');
	$data_coming .= '///' . $remains_point->title . '===';
	$actual_items = $remains_point->children();
	foreach ($actual_items as $itm) {
		$data_coming .= $itm->title . '=' . $itm->remain . ';';
		//Перемещаем метал из второстепенных точек на главную
		$actual_rew = $pages->get('id_point=point1_actual');
		$actual_items = $actual_rew->children();
		$actual_item = $actual_items->get('template=remains_point_itm, title=' . $itm->title . '');
		$remain = $actual_item->remain + $itm->remain;
		$actual_item->of(false);
	    $actual_item->remain = $remain;
	    $actual_item->save();
	    $page_item = $pages->get('template=remains_point_itm, id=' . $itm->id . '');
	    $page_item->of(false);
	    $page_item->remain = 0;
	    $page_item->save();
	}
}
// echo 'Приход со всех второстепенных точек' . '<br>';
// echo $data_coming . '<br>';
//Получение прихода со всех второстепенных точек



//Получение остатков на начало дня в главной точке
$data_actual = '';
$actual_rew = $pages->get('id_point=point1_actual');
$startday_rew = $pages->get('id_point=point1_startday');
$actual_items = $actual_rew->children();
$startday_items = $startday_rew->children();
foreach ($actual_items as $itm) {
	$data_actual .= $itm->title . '=' . $itm->remain . ';';
	//Записываем метал на начало дня в главной точке
    $met_start_item = $startday_items->get('title=' . $itm->title . '');
    $met_start_item->of(false);
    $met_start_item->remain = $itm->remain;
    $met_start_item->save();
}
// echo 'Остатки на начало дня в главной точке' . '<br>';
// echo $data_actual . '<br>';
//Получение остатков на начало дня в главной точке