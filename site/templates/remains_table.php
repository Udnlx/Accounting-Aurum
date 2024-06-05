<?php

//Таблица по остаткам на утро
$startday_items = $startday->children();
$remain_tables_startday .= '
<h2 class="uk-margin-remove uk-card-title">Остаток на начало дня</h2>
    <table class="uk-table-striped">
        <thead>
            <tr>
                <th style="width:33%">По пробам</th>
                <th style="width:33%"></th>
                <th style="width:33%">В 585</th>
            </tr>
        </thead>
        <tbody>
';

$in585 = 0;
foreach ($startday_items as $itm) {
    if ($itm->title == 'Ag' || $itm->title == 'Pt' || $itm->title == 'Pd') {
        //Серебро, Платина и Палладий не считаются
    } else {
        $in585 = $in585 + ($itm->remain/585*$itm->title);
    }
}
$in585 = round($in585, 2);

$i = 1;
foreach ($startday_items as $itm) {
    $sum585 = '';
    if ($i == 1) {
        $sum585 = '<td rowspan="3" align="center">' . number_format($in585, 2, ',', ' ') . '</td>';
    }
    $remain_tables_startday .= '
    <tr>
        <td>' . $itm->title . '</td>
        <td>' . number_format($itm->remain, 2, ',', ' ') . '</td>
        ' . $sum585 . '
    </tr>
    ';
    $i++;
}
$remain_tables_startday .= '
        </tbody>
    </table>
    <br>
';



//Таблица по текущим остаткам
$actual_items = $actual->children();
$remain_tables_startday .= '
<h2 class="uk-margin-remove uk-card-title">Текущий остаток</h2>
    <table class="uk-table-striped">
        <thead>
            <tr>
                <th style="width:33%">По пробам</th>
                <th style="width:33%"></th>
                <th style="width:33%">В 585</th>
            </tr>
        </thead>
        <tbody>
';

$in585 = 0;
foreach ($actual_items as $itm) {
    if ($itm->title == 'Ag' || $itm->title == 'Pt' || $itm->title == 'Pd') {
        //Серебро, Платина и Палладий не считаются
    } else {
        $in585 = $in585 + ($itm->remain/585*$itm->title);
    }
}
$actual_in585 = round($in585, 2);

$i = 1;
foreach ($actual_items as $itm) {
    $sum585 = '';
    if ($i == 1) {
        $sum585 = '<td rowspan="3" align="center">' . number_format($actual_in585, 2, ',', ' ') . '</td>';
    }
    $remain_tables_startday .= '
    <tr>
        <td>' . $itm->title . '</td>
        <td>' . number_format($itm->remain, 2, ',', ' ') . '</td>
        ' . $sum585 . '
    </tr>
    ';
    $i++;
}
$remain_tables_startday .= '
        </tbody>
    </table>
    <br>
';



//Таблица резерва
$reserv_items = $reserv->children();
$remain_tables_startday .= '
<h2 class="uk-margin-remove uk-card-title">Резерв</h2>
    <table class="uk-table-striped">
        <thead>
            <tr>
                <th style="width:33%">По пробам</th>
                <th style="width:33%"></th>
                <th style="width:33%">В 585</th>
            </tr>
        </thead>
        <tbody>
';

$in585 = 0;
foreach ($reserv_items as $itm) {
    if ($itm->title == 'Ag' || $itm->title == 'Pt' || $itm->title == 'Pd') {
        //Серебро, Платина и Палладий не считаются
    } else {
        $in585 = $in585 + ($itm->remain/585*$itm->title);
    }
}
$reserv_in585 = round($in585, 2);

$i = 1;
foreach ($reserv_items as $itm) {
    $sum585 = '';
    if ($i == 1) {
        $sum585 = '<td rowspan="3" align="center">' . number_format($reserv_in585, 2, ',', ' ') . '</td>';
    }
    $remain_tables_startday .= '
    <tr>
        <td>' . $itm->title . '</td>
        <td>' . number_format($itm->remain, 2, ',', ' ') . '</td>
        ' . $sum585 . '
    </tr>
    ';
    $i++;
}
$remain_tables_startday .= '
        </tbody>
    </table>
    <br>
';



//Таблица свободного металла
$actual_items = $actual->children();
$reserv_items = $reserv->children();
$remain_tables_startday .= '
<h2 class="uk-margin-remove uk-card-title">Свободно</h2>
    <table class="uk-table-striped">
        <thead>
            <tr>
                <th style="width:33%">По пробам</th>
                <th style="width:33%"></th>
                <th style="width:33%">В 585</th>
            </tr>
        </thead>
        <tbody>
';

$i = 1;
foreach ($actual_items as $itm) {
    $met_act_name = $itm->title;
    $met_res_item = $reserv_items->get('title=' . $itm->title . '');
    $met_act_weight = $itm->remain;
    $met_res_weight = $met_res_item->remain;
    $free_metal = $met_act_weight - $met_res_weight;

    $sum585 = '';
    if ($i == 1) {
        $free_in585 = round($actual_in585 - $reserv_in585, 2);
        $sum585 = '<td rowspan="3" align="center">' . number_format($free_in585, 2, ',', ' ') . '</td>';
    }

    $remain_tables_startday .= '
    <tr>
        <td>' . $itm->title . '</td>
        <td>' . number_format($free_metal, 2, ',', ' ') . '</td>
        ' . $sum585 . '
    </tr>
    ';
    $i++;
}

$remain_tables_startday .= '
        </tbody>
    </table>
    <br>
';

?>