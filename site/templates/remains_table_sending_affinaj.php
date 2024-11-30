<?php

$all_sending_affinaj = $pages->find('template=affinaj_itm, id_point=' . $selected_id_point . ', product_status=Отправлен, sort=-sort');

$remain_tables_startday .= '<br><h4 class="uk-card-title uk-margin-remove">Текущий отправленный аффинаж</h4><hr>';

foreach ($all_sending_affinaj as $affinaj_itm) {
	$in585 = 0;
	foreach ($affinaj_itm->affinaj_table as $itm) {
	    $in585 = $in585 + ($itm->weight/585*$itm->proba);
	}
	$actual_in585 = round($in585, 2);

    $remain_tables_startday .= '
    	<div class="list-items">
    	<p class="list-items-title">' . $affinaj_itm->title . '</p>
		<p class="list-items-options">Оператор: ' . $affinaj_itm->worker . '</p>
		<p class="list-items-bold-options">В 585 пробе: ' . $actual_in585 . ' г.</p>
		</div>
    ';
}