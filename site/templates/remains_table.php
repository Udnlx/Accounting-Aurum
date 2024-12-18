<?php

$remain_tables_startday .= '<div class="uk-grid-medium uk-child-width-1-4@l" uk-grid>';

//Таблица по остаткам на утро
$startday_items = $startday->children();
$remain_tables_startday .= '
<div>
    <h4 class="uk-margin-remove uk-card-title">Остаток на начало дня</h2>
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
    if ($itm->title == '999,9' || $itm->title == 'Ag' || $itm->title == 'Pt' || $itm->title == 'Pd') {
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
        $sum585 = '<td rowspan="15" align="center">' . number_format($in585, 2, '.', ' ') . '</td>';
    }
    $remain_tables_startday .= '
    <tr>
        <td>' . $itm->title . '</td>
        <td>' . number_format($itm->remain, 2, '.', ' ') . '</td>
        ' . $sum585 . '
    </tr>
    ';
    $i++;
}
$remain_tables_startday .= '
        </tbody>
    </table>
</div>
';



//Таблица по текущим остаткам
$actual_items = $actual->children();
$remain_tables_startday .= '
<div>
    <h4 class="uk-margin-remove uk-card-title">Текущий остаток</h2>
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
        $sum585 = '<td rowspan="15" align="center">' . number_format($actual_in585, 2, '.', ' ') . '</td>';
    }
    $remain_tables_startday .= '
    <tr>
        <td>' . $itm->title . '</td>
        <td>' . number_format($itm->remain, 2, '.', ' ') . '</td>
        ' . $sum585 . '
    </tr>
    ';
    $i++;
}
$remain_tables_startday .= '
        </tbody>
    </table>
</div>
';



//Таблица резерва
$reserv_items = $reserv->children();
$remain_tables_startday .= '
<div>
    <h4 class="uk-margin-remove uk-card-title">Резерв</h2>
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
        $sum585 = '<td rowspan="15" align="center">' . number_format($reserv_in585, 2, '.', ' ') . '</td>';
    }
    $remain_tables_startday .= '
    <tr>
        <td>' . $itm->title . '</td>
        <td>' . number_format($itm->remain, 2, '.', ' ') . '</td>
        ' . $sum585 . '
    </tr>
    ';
    $i++;
}
$remain_tables_startday .= '
        </tbody>
    </table>
</div>
';



//Таблица свободного металла
$actual_items = $actual->children();
$reserv_items = $reserv->children();
$remain_tables_startday .= '
<div>
    <h4 class="uk-margin-remove uk-card-title">Свободно</h2>
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
        $sum585 = '<td rowspan="15" align="center">' . number_format($free_in585, 2, '.', ' ') . '</td>';
    }

    $remain_tables_startday .= '
    <tr>
        <td>' . $itm->title . '</td>
        <td id="free_' . $itm->title . '">' . number_format($free_metal, 2, '.', ' ') . '</td>
        ' . $sum585 . '
    </tr>
    ';
    $i++;
}

$remain_tables_startday .= '
        </tbody>
    </table>
</div>
';

$remain_tables_startday .= '</div>';



include 'remains_table_sending_affinaj.php';



//Таблица свободного металла для аффинажа
$affinaj_table_start = '';
$actual_items = $actual->children();
$reserv_items = $reserv->children();
$affinaj_table_start .= '
<div>
    <table class="uk-table-striped">
        <thead>
            <tr>
                <th style="width:20%">По пробам</th>
                <th style="width:20%">Должно быть</th>
                <th style="width:20%">По факту</th>
                <th style="width:20%">В 585 должно быть</th>
                <th style="width:20%">В 585 по факту</th>
            </tr>
        </thead>
        <tbody>
';

$i = 1;
foreach ($actual_items as $itm) {
    if ($itm->title=='999' || $itm->title=='Ag' || $itm->title=='Pt' || $itm->title=='Pd') {
    //echo 'Не выводим значения';
    } else {
    $met_act_name = $itm->title;
    $met_res_item = $reserv_items->get('title=' . $itm->title . '');
    $met_act_weight = $itm->remain;
    $met_res_weight = $met_res_item->remain;
    $free_metal = $met_act_weight - $met_res_weight;

    $sum585 = '';
    if ($i == 1) {
        $free_in585 = round($actual_in585 - $reserv_in585, 2);
        $sum585 = '
        <td rowspan="14" align="center">' . number_format($free_in585, 2, '.', ' ') . '</td>
        <td rowspan="14" align="center">При формировании аффинажа не расчитывается</td>
        ';
    }

    $affinaj_table_start .= '
    <tr>
        <td>' . $itm->title . '</td>
        <td id="free_for_affinaj_' . $itm->title . '">' . number_format($free_metal, 2, '.', ' ') . '</td>
        <td id="edit_for_affinaj_' . $itm->title . '">
            <input class="uk-input selected_weight_affinaj" id="weight_affinaj_' . $itm->title . '" type="text" name="weight_for_affinaj_' . $itm->title . '" value="1">
        </td>
        ' . $sum585 . '
    </tr>
    ';
    $i++;
    }
}

$affinaj_table_start .= '
        </tbody>
    </table>
</div>
';

?>