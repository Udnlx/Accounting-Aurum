<?php

include_once __DIR__ . '/SimpleXLSXGen/SimpleXLSXGen.php';
$xlsx_report = [];

$selected_start_date = $_GET['download_start_date'];;
$selected_finish_date = $_GET['download_finish_date'];;
$report_point = $_GET['download_report_point'];;
$name_point = '';

if ($report_point == 'point1') {
    $name_point = 'Тверская 14';
}
if ($report_point == 'point3') {
    $name_point = 'Таганка';
}
if ($report_point == 'point4') {
    $name_point = 'Комсомолка';
}
if ($report_point == 'point5') {
    $name_point = 'Митинская 27а';
}

function get_dates($start, $end, $format = 'd.m.Y') {
    $day = 86400;
    $start = strtotime($start . ' -1 days');
    $end = strtotime($end . ' +1 days');
    $nums = round(($end - $start) / $day); 
    $days = array();
    for ($i = 1; $i < $nums; $i++) { 
        $days[] = date($format, ($start + ($i * $day)));
    }
    return $days;
}
 
$dates = get_dates($selected_start_date, $selected_finish_date);
//print_r($dates);

$std = date('d-m-Y', strtotime($selected_start_date));
$fid = date('d-m-Y', strtotime($selected_finish_date));

if(isset($_SESSION['operator'])){
    $operator = $_SESSION['operator'];
} else {
    $operator = 'no_operator';
}

if(isset($_SESSION['point'])){
    $selected_point = $_SESSION['point'];
} else {
    $selected_point = 'no_point';
}

if(isset($_SESSION['id_point'])){
    $selected_id_point = $_SESSION['id_point'];
} else {
    $selected_id_point = 'no_id_point';
}

$access = '';
if(isset($_SESSION['access'])){
    $access = $_SESSION['access'];
}

if ($operator == 'no_operator' || $selected_point == 'no_point' || $access != 'admin') {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Скачивание отчета по точке</h1>
        <!-- <h4 class="uk-margin-remove uk-heading-hero uk-text-center">Все операции</h4> -->
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title uk-text-center">Нет прав на эту страницу, потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {



//Получение операций по продажам металла
$xlsx_report[] = ['<b><style bgcolor="#969696" font-size="24">Продажи металла</style></b>'];
$xlsx_report[] = [''];
$income_lom = '';
$total_income_lom_sum = 0;
$bn_total_income_lom_sum = 0;
$total_income_profit = 0;
$total_income_lom_in585 = 0;

    //Точка
    $income_lom .= '<div class="report-table">';
    $income_lom .= '<p class="card-report__title_cash">' . $name_point . '</p>';
    $xlsx_report[] = ['<b><style font-size="14">Продажи металла на точке ' . $name_point . '</style></b>'];
    $income_lom .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:12%">ДАТА</th>
                    <th style="width:12%">ОПЕРАТОР</th>
                    <th style="width:12%">ПРОБА</th>
                    <th style="width:12%">ВЕС</th>
                    <th style="width:12%">ЦЕНА ЗА ГРАММ</th>
                    <th style="width:12%">ЦЕНА ЗА ВСЕ</th>
                    <th style="width:12%">СУММА ПРОДАЖИ</th>
                    <th style="width:12%">ПРОФИТ</th>
                    <th style="width:12%">В 585</th>
                </tr>
            </thead>
            <tbody>
    ';
    $xlsx_report[] = [
        '<b>ДАТА</b>',
        '<b>ОПЕРАТОР</b>',
        '<b>ПРОБА</b>',
        '<b>ВЕС</b>',
        '<b>ЦЕНА ЗА ГРАММ</b>',
        '<b>ЦЕНА ЗА ВСЕ</b>',
        '<b>СУММА ПРОДАЖИ</b>',
        '<b>ПРОФИТ</b>',
        '<b>В 585</b>'
    ];
    $total_income_lom_sum_point = 0;
    $bn_total_income_lom_sum_point = 0;
    $total_income_profit_point = 0;
    $total_income_lom_in585_point = 0;
    foreach ($dates as $day_itm) {
        $start_day_for_report = date('d-m-Y', strtotime($day_itm));
        $all_operation_lom_ondate = $pages->find('template=operation_itm, type_operation=Продажа, date=' . $start_day_for_report . '');
        $all_operation_lom_onpoint = $all_operation_lom_ondate->find('id_point=' . $report_point . '');
        foreach ($all_operation_lom_onpoint as $item) {
            if ($item->cash_card == 'Наличный расчет') {
                $total_income_lom_sum_point = $total_income_lom_sum_point + $item->pay;
            }
            if ($item->cash_card == 'Безналичный расчет') {
                $bn_total_income_lom_sum_point = $bn_total_income_lom_sum_point + $item->pay;
            }
            $profit = $item->pay - $item->price;
            $total_income_profit_point = $total_income_profit_point + $profit;
            $in585 = 0;
            if ($item->proba == 'Ag' || $item->proba == 'Ag-875' || $item->proba == 'Ag-925' || $item->proba == 'Ag-999' || $item->proba == 'Pt' || $item->proba == 'Pd') {
            //Серебро, Платина и Палладий не считаются
            } else {
                $in585 = ($item->weight/585*$item->proba);
            }
            $total_income_lom_in585_point = $total_income_lom_in585_point + $in585;
            $income_lom .= '
            <tr>
                <td>' . $item->date . '</td>
                <td>' . $item->worker . '</td>
                <td>' . $item->proba . '</td>
                <td>' . number_format($item->weight, 2, '.', ' ') . '</td>
                <td>' . number_format($item->price_gramm, 2, '.', ' ') . '</td>
                <td>' . number_format($item->price, 2, '.', ' ') . '</td>
                <td>' . number_format($item->pay, 2, '.', ' ') . '</td>
                <td>' . number_format($profit, 2, '.', ' ') . '</td>
                <td>' . number_format($in585, 2, '.', ' ') . '</td>
            </tr>
            ';
            $xlsx_report[] = [
                '<left>' . $item->date . '</left>', 
                '<left>' . $item->worker . '</left>', 
                '<left>' . $item->proba . '</left>', 
                '<left>' . $item->weight . '</left>', 
                '<left>' . $item->price_gramm . '</left>', 
                '<left>' . $item->price . '</left>', 
                '<left>' . $item->pay . '</left>', 
                '<left>' . $profit . '</left>',
                '<left>' . number_format($in585, 2, '.', ' ') . '</left>'
            ];
        }
    }
    $income_lom .= '
            </tbody>
        </table>
    ';
    $income_lom .= '</div>';
    $income_lom .= '<p class="card-report__title_cash">ДОХОД НА ПРОДАЖАХ МЕТАЛЛА ПО ТОЧКЕ НАЛИЧКА: <span style="color: green;">' . number_format($total_income_lom_sum_point, 2, '.', ' ') . '</span></p>';
    $income_lom .= '<p class="card-report__title_cash">ДОХОД НА ПРОДАЖАХ МЕТАЛЛА ПО ТОЧКЕ БЕЗНАЛ: <span style="color: green;">' . number_format($bn_total_income_lom_sum_point, 2, '.', ' ') . '</span></p>';
    $income_lom .= '<p class="card-report__title_cash">ПРОФИТ ПО ТОЧКЕ: <span style="color: green;">' . number_format($total_income_profit_point, 2, '.', ' ') . '</span></p>';
    $income_lom .= '<p class="card-report__title_cash">ПРОДАННО МЕТАЛЛА НА ТОЧКЕ В 585 ПРОБЕ: <span style="color: green;">' . number_format($total_income_lom_in585_point, 2, '.', ' ') . '</span></p><br>';

    $xlsx_report[] = [''];
    $xlsx_report[] = ['<b><style font-size="10">ДОХОД НА ПРОДАЖАХ МЕТАЛЛА ПО ТОЧКЕ НАЛИЧКА: ' . number_format($total_income_lom_sum_point, 2, '.', ' ') . '</style></b>'];
    $xlsx_report[] = ['<b><style font-size="10">ДОХОД НА ПРОДАЖАХ МЕТАЛЛА ПО ТОЧКЕ БЕЗНАЛ: ' . number_format($bn_total_income_lom_sum_point, 2, '.', ' ') . '</style></b>'];
    $xlsx_report[] = ['<b><style font-size="10">ПРОФИТ ПО ТОЧКЕ: ' . number_format($total_income_profit_point, 2, '.', ' ') . '</style></b>'];
    $xlsx_report[] = ['<b><style font-size="10">ПРОДАННО МЕТАЛЛА НА ТОЧКЕ В 585 ПРОБЕ: ' . number_format($total_income_lom_in585_point, 2, '.', ' ') . '</style></b>'];
    $xlsx_report[] = [''];
    $xlsx_report[] = [''];
    $xlsx_report[] = [''];
    //Точка



//Получение операций по скупкам металла
$xlsx_report[] = ['<b><style bgcolor="#969696" font-size="24">Скупки металла</style></b>'];
$xlsx_report[] = [''];
$expenses_lom = '';
$total_expenses_lom_sum = 0;
$bn_total_expenses_lom_sum = 0;
$total_expenses_profit = 0;
$total_expenses_lom_in585 = 0;

    //Точка
    $expenses_lom .= '<div class="report-table">';
    $expenses_lom .= '<p class="card-report__title_cash">' . $name_point . '</p>';
    $xlsx_report[] = ['<b><style font-size="14">Скупки металла на точке ' . $name_point . '</style></b>'];
    $expenses_lom .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:12%">ДАТА</th>
                    <th style="width:12%">ОПЕРАТОР</th>
                    <th style="width:12%">ПРОБА</th>
                    <th style="width:12%">ВЕС</th>
                    <th style="width:12%">ЦЕНА ЗА ГРАММ</th>
                    <th style="width:12%">ЦЕНА ЗА ВСЕ</th>
                    <th style="width:12%">СУММА СКУПКИ</th>
                    <th style="width:12%">ПРОФИТ</th>
                    <th style="width:12%">В 585</th>
                </tr>
            </thead>
            <tbody>
    ';
    $xlsx_report[] = [
        '<b>ДАТА</b>',
        '<b>ОПЕРАТОР</b>',
        '<b>ПРОБА</b>',
        '<b>ВЕС</b>',
        '<b>ЦЕНА ЗА ГРАММ</b>',
        '<b>ЦЕНА ЗА ВСЕ</b>',
        '<b>СУММА СКУПКИ</b>',
        '<b>ПРОФИТ</b>',
        '<b>В 585</b>'
    ];
    $total_expenses_lom_sum_point = 0;
    $bn_total_expenses_lom_sum_point = 0;
    $total_expenses_profit_point = 0;
    $total_expenses_lom_in585_point = 0;
    foreach ($dates as $day_itm) {
        $start_day_for_report = date('d-m-Y', strtotime($day_itm));
        $all_operation_lom_ondate = $pages->find('template=operation_itm, type_operation=Скупка, date=' . $start_day_for_report . '');
        $all_operation_lom_onpoint = $all_operation_lom_ondate->find('id_point=' . $report_point . '');
        foreach ($all_operation_lom_onpoint as $item) {
            if ($item->cash_card == 'Наличный расчет') {
                $total_expenses_lom_sum_point = $total_expenses_lom_sum_point + $item->pay;
            }
            if ($item->cash_card == 'Безналичный расчет') {
                $bn_total_expenses_lom_sum_point = $bn_total_expenses_lom_sum_point + $item->pay;
            }
            $profit = $item->price - $item->pay;
            $total_expenses_profit_point = $total_expenses_profit_point + $profit;
            $in585 = 0;
            if ($item->proba == 'Ag' || $item->proba == 'Ag-875' || $item->proba == 'Ag-925' || $item->proba == 'Ag-999' || $item->proba == 'Pt' || $item->proba == 'Pd') {
            //Серебро, Платина и Палладий не считаются
            } else {
                $in585 = ($item->weight/585*$item->proba);
            }
            $total_expenses_lom_in585_point = $total_expenses_lom_in585_point + $in585;
            $expenses_lom .= '
            <tr>
                <td>' . $item->date . '</td>
                <td>' . $item->worker . '</td>
                <td>' . $item->proba . '</td>
                <td>' . number_format($item->weight, 2, '.', ' ') . '</td>
                <td>' . number_format($item->price_gramm, 2, '.', ' ') . '</td>
                <td>' . number_format($item->price, 2, '.', ' ') . '</td>
                <td>' . number_format($item->pay, 2, '.', ' ') . '</td>
                <td>' . number_format($profit, 2, '.', ' ') . '</td>
                <td>' . number_format($in585, 2, '.', ' ') . '</td>
            </tr>
            ';
            $xlsx_report[] = [
                '<left>' . $item->date . '</left>', 
                '<left>' . $item->worker . '</left>', 
                '<left>' . $item->proba . '</left>', 
                '<left>' . $item->weight . '</left>', 
                '<left>' . $item->price_gramm . '</left>', 
                '<left>' . $item->price . '</left>', 
                '<left>' . $item->pay . '</left>', 
                '<left>' . $profit . '</left>',
                '<left>' . number_format($in585, 2, '.', ' ') . '</left>'
            ];
        }
    }
    $expenses_lom .= '
            </tbody>
        </table>
    ';
    $expenses_lom .= '</div>';
    $expenses_lom .= '<p class="card-report__title_cash">РАСХОД НА СКУПКАХ МЕТАЛЛА ПО ТОЧКЕ НАЛИЧКА: <span style="color: red;">' . number_format($total_expenses_lom_sum_point, 2, '.', ' ') . '</span></p>';
    $expenses_lom .= '<p class="card-report__title_cash">РАСХОД НА СКУПКАХ МЕТАЛЛА ПО ТОЧКЕ БЕЗНАЛ: <span style="color: red;">' . number_format($bn_total_expenses_lom_sum_point, 2, '.', ' ') . '</span></p>';
    $expenses_lom .= '<p class="card-report__title_cash">ПРОФИТ ПО ТОЧКЕ: <span style="color: red;">' . number_format($total_expenses_profit_point, 2, '.', ' ') . '</span></p>';
    $expenses_lom .= '<p class="card-report__title_cash">КУПЛЕННО МЕТАЛЛА НА ТОЧКЕ В 585 ПРОБЕ: <span style="color: red;">' . number_format($total_expenses_lom_in585_point, 2, '.', ' ') . '</span></p><br>';

    $xlsx_report[] = [''];
    $xlsx_report[] = ['<b><style font-size="10">РАСХОД НА СКУПКАХ МЕТАЛЛА ПО ТОЧКЕ НАЛИЧКА: ' . number_format($total_expenses_lom_sum_point, 2, '.', ' ') . '</style></b>'];
    $xlsx_report[] = ['<b><style font-size="10">РАСХОД НА СКУПКАХ МЕТАЛЛА ПО ТОЧКЕ БЕЗНАЛ: ' . number_format($bn_total_expenses_lom_sum_point, 2, '.', ' ') . '</style></b>'];
    $xlsx_report[] = ['<b><style font-size="10">ПРОФИТ ПО ТОЧКЕ: ' . number_format($total_expenses_profit_point, 2, '.', ' ') . '</style></b>'];
    $xlsx_report[] = ['<b><style font-size="10">КУПЛЕННО МЕТАЛЛА НА ТОЧКЕ В 585 ПРОБЕ: ' . number_format($total_expenses_lom_in585_point, 2, '.', ' ') . '</style></b>'];
    $xlsx_report[] = [''];
    $xlsx_report[] = [''];
    $xlsx_report[] = [''];
    //Точка



//Получение операций по продажам изделий
$xlsx_report[] = ['<b><style bgcolor="#969696" font-size="24">Продажа изделий</style></b>'];
$xlsx_report[] = [''];
$income_izdelie = '';
$total_income_izdelie_sum = 0;
$bn_total_income_izdelie_sum = 0;

    //Точка
    $income_izdelie .= '<div class="report-table">';
    $income_izdelie .= '<p class="card-report__title_cash">' . $name_point . '</p>';
    $xlsx_report[] = ['<b><style font-size="14">Продажа изделий на точке ' . $name_point . '</style></b>'];
    $income_izdelie .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:16%">ДАТА ПРОДАЖИ</th>
                    <th style="width:16%">ОПЕРАТОР ПРОДАЖИ</th>
                    <th style="width:16%">ЦЕНА СКУПКИ</th>
                    <th style="width:16%">ЦЕНА ПРОДАЖИ</th>
                    <th style="width:16%">НАИМЕНОВАНИЕ</th>
                    <th style="width:16%">ВЕС</th>
                </tr>
            </thead>
            <tbody>
    ';
    $xlsx_report[] = [
        '<b>ДАТА ПРОДАЖИ</b>',
        '<b>ОПЕРАТОР ПРОДАЖИ</b>',
        '<b>ЦЕНА СКУПКИ</b>',
        '<b>ЦЕНА ПРОДАЖИ</b>',
        '<b>НАИМЕНОВАНИЕ</b>',
        '<b>ВЕС</b>',
    ];
    $total_income_izdelie_sum_point = 0;
    $bn_total_income_izdelie_sum_point = 0;
    foreach ($dates as $day_itm) {
        $start_day_for_report = date('d-m-Y', strtotime($day_itm));
        $all_operation_izdelie_ondate = $pages->find('template=product_itm, product_status=продано, product_date_sell=' . $start_day_for_report . '');
        $all_operation_izdelie_onpoint = $all_operation_izdelie_ondate->find('id_point=' . $report_point . '');
        foreach ($all_operation_izdelie_onpoint as $item) {
            if ($item->cash_card_product_sell == 'Наличный расчет') {
                $total_income_izdelie_sum_point = $total_income_izdelie_sum_point + $item->product_price_sell;
            }
            if ($item->cash_card_product_sell == 'Безналичный расчет') {
                $bn_total_income_izdelie_sum_point = $bn_total_income_izdelie_sum_point + $item->product_price_sell;
            }
            $income_izdelie .= '
            <tr>
                <td>' . $item->product_date_sell . '</td>
                <td>' . $item->worker_sell . '</td>
                <td>' . number_format($item->product_price_buy, 2, '.', ' ') . '</td>
                <td>' . number_format($item->product_price_sell, 2, '.', ' ') . '</td>
                <td>' . $item->product_name . '<br>' . $item->product_description . '</td>
                <td>' . number_format($item->weight, 2, '.', ' ') . '</td>
            </tr>
            ';
             $xlsx_report[] = [
                '<left>' . $item->product_date_sell . '</left>', 
                '<left>' . $item->worker_sell . '</left>', 
                '<left>' . $item->product_price_buy . '</left>', 
                '<left>' . $item->product_price_sell . '</left>', 
                '<left><wraptext>' . $item->product_name . ' ' . $item->product_description . '</wraptext></left>', 
                '<left>' . $item->weight . '</left>', 
            ];
        }
    }
    $income_izdelie .= '
            </tbody>
        </table>
    ';
    $income_izdelie .= '</div>';
    $income_izdelie .= '
        <p class="card-report__title_cash">ДОХОД НА ПРОДАЖАХ ИЗДЕЛИЙ ПО ТОЧКЕ НАЛИЧКА: <span style="color: green;">' . number_format($total_income_izdelie_sum_point, 2, '.', ' ') . '</span></p>
        <p class="card-report__title_cash">ДОХОД НА ПРОДАЖАХ ИЗДЕЛИЙ ПО ТОЧКЕ БЕЗНАЛ: <span style="color: green;">' . number_format($bn_total_income_izdelie_sum_point, 2, '.', ' ') . '</span></p>
        <br>
        ';

    $xlsx_report[] = [''];
    $xlsx_report[] = ['<b><style font-size="10">ДОХОД НА ПРОДАЖАХ ИЗДЕЛИЙ ПО ТОЧКЕ НАЛИЧКА: ' . number_format($total_income_izdelie_sum_point, 2, '.', ' ') . '</style></b>'];
    $xlsx_report[] = ['<b><style font-size="10">ДОХОД НА ПРОДАЖАХ ИЗДЕЛИЙ ПО ТОЧКЕ БЕЗНАЛ: ' . number_format($bn_total_income_izdelie_sum_point, 2, '.', ' ') . '</style></b>'];
    $xlsx_report[] = [''];
    $xlsx_report[] = [''];
    $xlsx_report[] = [''];
    //Точка



//Получение операций по скупкам изделий
$xlsx_report[] = ['<b><style bgcolor="#969696" font-size="24">Скупка изделий</style></b>'];
$xlsx_report[] = [''];
$expenses_izdelie = '';
$total_expenses_izdelie_sum = 0;
$bn_total_expenses_izdelie_sum = 0;

    //Точка
    $expenses_izdelie .= '<div class="report-table">';
    $expenses_izdelie .= '<p class="card-report__title_cash">' . $name_point . '</p>';
    $xlsx_report[] = ['<b><style font-size="14">Скупка изделий на точке ' . $name_point . '</style></b>'];
    $expenses_izdelie .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:14%">ДАТА СКУПКИ</th>
                    <th style="width:14%">ОПЕРАТОР СКУПКИ</th>
                    <th style="width:14%">ЦЕНА СКУПКИ</th>
                    <th style="width:14%">СТАТУС</th>
                    <th style="width:14%">ЦЕНА ПРОДАЖИ</th>
                    <th style="width:14%">НАИМЕНОВАНИЕ</th>
                    <th style="width:14%">ВЕС</th>
                </tr>
            </thead>
            <tbody>
    ';
    $xlsx_report[] = [
        '<b>ДАТА СКУПКИ</b>',
        '<b>ОПЕРАТОР СКУПКИ</b>',
        '<b>ЦЕНА СКУПКИ</b>',
        '<b>СТАТУС</b>',
        '<b>ЦЕНА ПРОДАЖИ</b>',
        '<b>НАИМЕНОВАНИЕ</b>',
        '<b>ВЕС</b>',
    ];
    $total_expenses_izdelie_sum_point = 0;
    $bn_total_expenses_izdelie_sum_point = 0;
    foreach ($dates as $day_itm) {
        $start_day_for_report = date('d-m-Y', strtotime($day_itm));
        $all_operation_izdelie_ondate = $pages->find('template=product_itm, product_date_buy=' . $start_day_for_report . '');
        $all_operation_izdelie_onpoint = $all_operation_izdelie_ondate->find('id_point=' . $report_point . '');
        foreach ($all_operation_izdelie_onpoint as $item) {
            if ($item->cash_card == 'Наличный расчет') {
                $total_expenses_izdelie_sum_point = $total_expenses_izdelie_sum_point + $item->product_price_buy;
            }
            if ($item->cash_card == 'Безналичный расчет') {
                $bn_total_expenses_izdelie_sum_point = $bn_total_expenses_izdelie_sum_point + $item->product_price_buy;
            }
            $expenses_izdelie .= '
            <tr>
                <td>' . $item->product_date_buy . '</td>
                <td>' . $item->worker . '</td>
                <td>' . number_format($item->product_price_buy, 2, '.', ' ') . '</td>
                <td>' . $item->product_status . '</td>
                <td>' . number_format($item->product_price_sell, 2, '.', ' ') . '</td>
                <td>' . $item->product_name . '<br>' . $item->product_description . '</td>
                <td>' . number_format($item->weight, 2, '.', ' ') . '</td>
            </tr>
            ';
            $xlsx_report[] = [
                '<left>' . $item->product_date_buy . '</left>', 
                '<left>' . $item->worker . '</left>', 
                '<left>' . $item->product_price_buy . '</left>', 
                '<left>' . $item->product_status . '</left>',
                '<left>' . $item->product_price_sell . '</left>', 
                '<left><wraptext>' . $item->product_name . ' ' . $item->product_description . '</wraptext></left>', 
                '<left>' . $item->weight . '</left>', 
            ];
        }
    }
    $expenses_izdelie .= '
            </tbody>
        </table>
    ';
    $expenses_izdelie .= '</div>';
    $expenses_izdelie .= '
        <p class="card-report__title_cash">РАСХОД НА СКУПКАХ ИЗДЕЛИЙ ПО ТОЧКЕ НАЛИЧКА: <span style="color: red;">' . number_format($total_expenses_izdelie_sum_point, 2, '.', ' ') . '</span></p>
        <p class="card-report__title_cash">РАСХОД НА СКУПКАХ ИЗДЕЛИЙ ПО ТОЧКЕ БЕЗНАЛ: <span style="color: red;">' . number_format($bn_total_expenses_izdelie_sum_point, 2, '.', ' ') . '</span></p>
        <br>';

    $xlsx_report[] = [''];
    $xlsx_report[] = ['<b><style font-size="10">РАСХОД НА СКУПКАХ ИЗДЕЛИЙ ПО ТОЧКЕ НАЛИЧКА: ' . number_format($total_expenses_izdelie_sum_point, 2, '.', ' ') . '</style></b>'];
    $xlsx_report[] = ['<b><style font-size="10">РАСХОД НА СКУПКАХ ИЗДЕЛИЙ ПО ТОЧКЕ БЕЗНАЛ: ' . number_format($bn_total_expenses_izdelie_sum_point, 2, '.', ' ') . '</style></b>'];
    $xlsx_report[] = [''];
    $xlsx_report[] = [''];
    $xlsx_report[] = [''];
    //Точка



//Получение операций дохода по кассам
$xlsx_report[] = ['<b><style bgcolor="#969696" font-size="24">Доход по кассам</style></b>'];
$xlsx_report[] = [''];
$income = '';
$total_income = 0;
$bn_total_income = 0;

    //Точка
    $income .= '<div class="report-table">';
    $income .= '<p class="card-report__title_cash">' . $name_point . '</p>';
    $xlsx_report[] = ['<b><style font-size="14">Доход по кассам на точке ' . $name_point . '</style></b>'];
    $income .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:25%">ДАТА</th>
                    <th style="width:25%">СУММА</th>
                    <th style="width:50%">ОПИСАНИЕ</th>
                </tr>
            </thead>
            <tbody>
    ';
    $xlsx_report[] = [
        '<b>ДАТА</b>',
        '<b>СУММА</b>',
        '<b>ОПИСАНИЕ</b>',
    ];
    $total_income_point = 0;
    $bn_total_income_point = 0;
    foreach ($dates as $day_itm) {
        $start_day_for_report = date('d-m-Y', strtotime($day_itm));
        $page_cash = $pages->get('template=cash_itm, id_point=' . $report_point . '_cash');
        $all_operation_cash_ondate = $page_cash->find('template=cash_operation, type_operation=Приход, date=' . $start_day_for_report . '');
        foreach ($all_operation_cash_ondate as $item) {
            if ($item->cash_card == 'Наличный расчет') {
                $total_income_point = $total_income_point + $item->sum;
            }
            if ($item->cash_card == 'Безналичный расчет') {
                $bn_total_income_point = $bn_total_income_point + $item->sum;
            }
            $income .= '
            <tr>
                <td>' . $item->date . '</td>
                <td>' . number_format($item->sum, 2, '.', ' ') . '</td>
                <td>' . $item->note . '</td>
            </tr>
            ';
            $xlsx_report[] = [
                '<left>' . $item->date . '</left>', 
                '<left>' . $item->sum . '</left>', 
                '<left><wraptext>' . $item->note . '</wraptext></left>', 
            ];
        }
    }
    $income .= '
            </tbody>
        </table>
    ';
    $income .= '</div>';
    $income .= '
        <p class="card-report__title_cash">ИТОГО ДОХОД ПО ТОЧКЕ НАЛИЧКА: <span style="color: green;">' . number_format($total_income_point, 2, '.', ' ') . '</span></p>
        <p class="card-report__title_cash">ИТОГО ДОХОД ПО ТОЧКЕ БЕЗНАЛ: <span style="color: green;">' . number_format($bn_total_income_point, 2, '.', ' ') . '</span></p>
        <br>
        ';

    $xlsx_report[] = [''];
    $xlsx_report[] = ['<b><style font-size="10">ИТОГО ДОХОД ПО ТОЧКЕ НАЛИЧКА: ' . number_format($total_income_point, 2, '.', ' ') . '</style></b>'];
    $xlsx_report[] = ['<b><style font-size="10">ИТОГО ДОХОД ПО ТОЧКЕ БЕЗНАЛ: ' . number_format($bn_total_income_point, 2, '.', ' ') . '</style></b>'];
    $xlsx_report[] = [''];
    $xlsx_report[] = [''];
    $xlsx_report[] = [''];
    //Точка



//Получение операций расхода по кассам
$xlsx_report[] = ['<b><style bgcolor="#969696" font-size="24">Расход по кассам</style></b>'];
$xlsx_report[] = [''];
$expenses = '';
$total_expenses = 0;
$bn_total_expenses = 0;

    //Точка
    $expenses .= '<div class="report-table">';
    $expenses .= '<p class="card-report__title_cash">' . $name_point . '</p>';
    $xlsx_report[] = ['<b><style font-size="14">Расход по кассам на точке ' . $name_point . '</style></b>'];
    $expenses .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:25%">ДАТА</th>
                    <th style="width:25%">СУММА</th>
                    <th style="width:50%">ОПИСАНИЕ</th>
                </tr>
            </thead>
            <tbody>
    ';
    $xlsx_report[] = [
        '<b>ДАТА</b>',
        '<b>СУММА</b>',
        '<b>ОПИСАНИЕ</b>',
    ];
    $total_expenses_point = 0;
    $bn_total_expenses_point = 0;
    foreach ($dates as $day_itm) {
        $start_day_for_report = date('d-m-Y', strtotime($day_itm));
        $page_cash = $pages->get('template=cash_itm, id_point=' . $report_point . '_cash');
        $all_operation_cash_ondate = $page_cash->find('template=cash_operation, type_operation=Расход, date=' . $start_day_for_report . '');
        foreach ($all_operation_cash_ondate as $item) {
            if ($item->cash_card == 'Наличный расчет') {
                $total_expenses_point = $total_expenses_point + $item->sum;
            }
            if ($item->cash_card == 'Безналичный расчет') {
                $bn_total_expenses_point = $bn_total_expenses_point + $item->sum;
            }
            $expenses .= '
            <tr>
                <td>' . $item->date . '</td>
                <td>' . number_format($item->sum, 2, '.', ' ') . '</td>
                <td>' . $item->note . '</td>
            </tr>
            ';
            $xlsx_report[] = [
                '<left>' . $item->date . '</left>', 
                '<left>' . $item->sum . '</left>', 
                '<left><wraptext>' . $item->note . '</wraptext></left>', 
            ];
        }
    }
    $expenses .= '
            </tbody>
        </table>
    ';
    $expenses .= '</div>';
    $expenses .= '
        <p class="card-report__title_cash">ИТОГО РАСХОД ПО ТОЧКЕ НАЛИЧКА: <span style="color: red;">' . number_format($total_expenses_point, 2, '.', ' ') . '</span></p>
        <p class="card-report__title_cash">ИТОГО РАСХОД ПО ТОЧКЕ БЕЗНАЛ: <span style="color: red;">' . number_format($bn_total_expenses_point, 2, '.', ' ') . '</span></p>
        <br>
        ';

    $xlsx_report[] = [''];
    $xlsx_report[] = ['<b><style font-size="10">ИТОГО РАСХОД ПО ТОЧКЕ НАЛИЧКА: ' . number_format($total_expenses_point, 2, '.', ' ') . '</style></b>'];
    $xlsx_report[] = ['<b><style font-size="10">ИТОГО РАСХОД ПО ТОЧКЕ БЕЗНАЛ: ' . number_format($bn_total_expenses_point, 2, '.', ' ') . '</style></b>'];
    $xlsx_report[] = [''];
    $xlsx_report[] = [''];
    $xlsx_report[] = [''];
    //Точка



//Долги
$arrears = '';
$total_arrears = 0;
foreach ($dates as $day_itm) {
    $start_day_for_report = date('d-m-Y', strtotime($day_itm));
    $page_arrears = $pages->get('template=arrears');
    $all_arrears = $page_arrears->children('date=' . $start_day_for_report . '');
    foreach ($all_arrears as $item) {
        $arrears .= '
        <p class="card-report__info">' . $item->date . ' - ' . $item->client_name . ' - <span style="font-weight:700;">' . number_format($item->sum, 2, '.',' ') . '</span></p>
        <p class="card-report__info" style="margin: -5px 0 7px 0;font-size: 12px;">Оператор: ' . $item->worker . '; Статус: ' . $item->product_status . '; Описание: ' . $item->description_operation . '</p>
        ';
        $total_arrears = $total_arrears + $item->sum;
    }
}
$arrears .= '
<p class="card-report__info">Всего долгов - <span style="color:green;font-weight:700;">' . number_format($total_arrears, 2, '.',' ') . '</span></p>
';



//Выводим в xlsx 
// $xlsx_report = [
//     ['ISBN', 'title', 'author', 'publisher', 'ctry' ],
//     [618260307, 'The Hobbit', 'J. R. R. Tolkien', 'Houghton Mifflin', 'USA'],
//     [908606664, 'Slinky Malinki', 'Lynley Dodd', 'Mallinson Rendel', 'NZ']
// ];
$xlsx = Shuchkin\SimpleXLSXGen::fromArray( $xlsx_report );
$xlsx->downloadAs('Отчет.xlsx');
//$xlsx->saveAs('Отчет.xlsx');
//Выводим в xlsx 
?>

<div id="content">
    <div id="start"></div>
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Скачивание отчета по точке <?php echo $name_point; ?></h1>
    <h4 class="uk-margin-remove uk-heading-hero uk-text-center">За период с <?php echo $std; ?> по <?php echo $fid; ?></h4>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/adminpanel-meniu/">Админ панель</a>
                <a class="menu-link" href="/osnovnoi-otchet/">Отчет за текущий день</a>
            </div>
        </div>

        <div class="anchor"><span id="income_lom"></span></div>
        <div>
            <div class="uk-card card-report uk-card-default uk-flex uk-flex-column">
                <h2 class="uk-card-title uk-margin-remove title-table-mainreport">Продажи металла</h2>
                <?php echo $income_lom; ?>
            </div>
        </div>

        <div class="anchor"><span id="expenses_lom"></span></div>
        <div>
            <div class="uk-card card-report uk-card-default uk-flex uk-flex-column">
                <h2 class="uk-card-title uk-margin-remove title-table-mainreport">Скупки металла</h2>
                <?php echo $expenses_lom; ?>
            </div>
        </div>

        <div class="anchor"><span id="income_izdelie"></span></div>
        <div>
            <div class="uk-card card-report uk-card-default uk-flex uk-flex-column">
                <h2 class="uk-card-title uk-margin-remove title-table-mainreport">Продажи изделий</h2>
                <?php echo $income_izdelie; ?>
            </div>
        </div>

        <div class="anchor"><span id="expenses_izdelie"></span></div>
        <div>
            <div class="uk-card card-report uk-card-default uk-flex uk-flex-column">
                <h2 class="uk-card-title uk-margin-remove title-table-mainreport">Скупки изделий</h2>
                <?php echo $expenses_izdelie; ?>
            </div>
        </div>

        <div class="anchor"><span id="income"></span></div>
        <div>
            <div class="uk-card card-report uk-card-default uk-flex uk-flex-column">
                <h2 class="uk-card-title uk-margin-remove title-table-mainreport">Доходы</h2>
                <?php echo $income; ?>
            </div>
        </div>

        <div class="anchor"><span id="expenses"></span></div>
        <div>
            <div class="uk-card card-report uk-card-default uk-flex uk-flex-column">
                <h2 class="uk-card-title uk-margin-remove title-table-mainreport">Расходы</h2>
                <?php echo $expenses; ?>
            </div>
        </div>

        <div class="anchor"><span id="arrears"></span></div>
        <div>
            <div class="uk-card card-report uk-card-default uk-flex uk-flex-column">
                <h2 class="uk-card-title uk-margin-remove title-table-mainreport">Долги</h2>
                <?php echo $arrears; ?>
            </div>
        </div>
        
    </div>
</div>

<?php   
}
?>