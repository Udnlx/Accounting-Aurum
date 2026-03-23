<?php namespace ProcessWire;

//Получаем список точек для выбора
$all_points = $pages->find('template=points_itm');
$list_options = '';
foreach ($all_points as $point) {
    $list_options .= '<option value="' . $point->id_point . '">' . $point->title . '</option>';
}

$selected_start_date = !empty($_POST['point_start_date'])?$_POST['point_start_date']:NULL;
$selected_finish_date = !empty($_POST['point_finish_date'])?$_POST['point_finish_date']:NULL;
$report_point = !empty($_POST['report_point'])?$_POST['report_point']:NULL;
$name_point = '';

$point_page = $pages->get('template=points_itm, id_point=' . $report_point);
$name_point = $point_page->title;

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
    	<h1 class="uk-heading-hero uk-text-center">Отчет по точке</h1>
        <!-- <h4 class="uk-margin-remove uk-heading-hero uk-text-center">Все операции</h4> -->
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title uk-text-center">Нет прав на эту страницу, потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {



// //Денег на утро
// $cash_on_morning = '';
// $total_cash = 0;
// $bn_total_cash = 0;
// $page_cash = $pages->get('template=cash');
// $all_cash = $page_cash->children();
// foreach ($all_cash as $item) {
//     $cash_on_morning .= '
//         <p class="card-report__info">' . $item->title . ' наличка - <span style="font-weight:700;">' . number_format($item->cash_remain_startday, 2, '.',' ') . '</span></p>
//         <p class="card-report__info">' . $item->title . ' безнал - <span style="font-weight:700;">' . number_format($item->bn_cash_remain_startday, 2, '.',' ') . '</span></p>
//     ';
//     $total_cash = $total_cash + $item->cash_remain_startday;
//     $bn_total_cash = $bn_total_cash + $item->bn_cash_remain_startday;
// }
// $cash_on_morning .= '
// <p class="card-report__info">Всего средств на утро наличка - <span style="color:green;font-weight:700;">' . number_format($total_cash, 2, '.',' ') . '</span></p>
// <p class="card-report__info">Всего средств на утро безнал - <span style="color:green;font-weight:700;">' . number_format($bn_total_cash, 2, '.',' ') . '</span></p>
// ';



//Получение операций по продажам металла
$income_lom = '';
$total_income_lom_sum = 0;
$bn_total_income_lom_sum = 0;
$total_income_profit = 0;
$total_income_lom_in585 = 0;

    //Точка
    $income_lom .= '<div class="report-table">';
    $income_lom .= '<p class="card-report__title_cash">' . $name_point . '</p>';
    $income_lom .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:12%">ДАТА</th>
                    <th style="width:12%">ОПЕРАТОР</th>
                    <th style="width:7%">ПРОБА</th>
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
            if ($item->proba == 'Ag' || $item->proba == 'Ag-800' || $item->proba == 'Ag-875' || $item->proba == 'Ag-925' || $item->proba == 'Ag-999' || $item->proba == 'Pt' || $item->proba == 'Pd') {
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
    //Точка



//Получение операций по скупкам металла
$expenses_lom = '';
$total_expenses_lom_sum = 0;
$bn_total_expenses_lom_sum = 0;
$total_expenses_profit = 0;
$total_expenses_lom_in585 = 0;

    //Точка
    $expenses_lom .= '<div class="report-table">';
    $expenses_lom .= '<p class="card-report__title_cash">' . $name_point . '</p>';
    $expenses_lom .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:12%">ДАТА</th>
                    <th style="width:12%">ОПЕРАТОР</th>
                    <th style="width:7%">ПРОБА</th>
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
    $total_expenses_lom_sum_point = 0;
    $bn_total_expenses_lom_sum_point = 0;
    $total_expenses_profit_point = 0;
    $total_expenses_lom_in585_point = 0;
    foreach ($dates as $day_itm) {
        $start_day_for_report = date('d-m-Y', strtotime($day_itm));
        $all_operation_lom_ondate = $pages->find('template=operation_itm, type_operation=Скупка||Мульти скупка, date=' . $start_day_for_report . '');
        $all_operation_lom_onpoint = $all_operation_lom_ondate->find('id_point=' . $report_point . '');
        foreach ($all_operation_lom_onpoint as $item) {
            if ($item->type_operation == 'Скупка') {
                if ($item->cash_card == 'Наличный расчет') {
                    $total_expenses_lom_sum_point = $total_expenses_lom_sum_point + $item->pay;
                }
                if ($item->cash_card == 'Безналичный расчет') {
                    $bn_total_expenses_lom_sum_point = $bn_total_expenses_lom_sum_point + $item->pay;
                }
                if ($item->cash_card == 'Смешанный расчет') {
                    $total_expenses_lom_sum_point = $total_expenses_lom_sum_point + $item->multisum_nal;
                    $bn_total_expenses_lom_sum_point = $bn_total_expenses_lom_sum_point + $item->multisum_beznal;
                }
                $profit = $item->price - $item->pay;
                $total_expenses_profit_point = $total_expenses_profit_point + $profit;
                $in585 = 0;
                if ($item->proba == 'Ag' || $item->proba == 'Ag-800' || $item->proba == 'Ag-875' || $item->proba == 'Ag-925' || $item->proba == 'Ag-999' || $item->proba == 'Pt' || $item->proba == 'Pd') {
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
            }

            if ($item->type_operation == 'Мульти скупка') {
                if ($item->cash_card == 'Наличный расчет') {
                    $total_expenses_lom_sum_point = $total_expenses_lom_sum_point + $item->pay;
                }
                if ($item->cash_card == 'Безналичный расчет') {
                    $bn_total_expenses_lom_sum_point = $bn_total_expenses_lom_sum_point + $item->pay;
                }
                if ($item->cash_card == 'Смешанный расчет') {
                    $total_expenses_lom_sum_point = $total_expenses_lom_sum_point + $item->multisum_nal;
                    $bn_total_expenses_lom_sum_point = $bn_total_expenses_lom_sum_point + $item->multisum_beznal;
                }
                $child_operations = $item->children();
                foreach ($child_operations as $child_operation) {
                    $desc_multi = '<span style="font-size:9px;">' . $child_operation->description_operation . '</span>';
                    $profit = $child_operation->price - $child_operation->pay;
                    $total_expenses_profit_point = $total_expenses_profit_point + $profit;
                    $in585 = 0;
                    if ($child_operation->proba == 'Ag' || $child_operation->proba == 'Ag-800' || $child_operation->proba == 'Ag-875' || $child_operation->proba == 'Ag-925' || $child_operation->proba == 'Ag-999' || $child_operation->proba == 'Pt' || $child_operation->proba == 'Pd') {
                    //Серебро, Платина и Палладий не считаются
                    } else {
                        $in585 = ($child_operation->weight/585*$child_operation->proba);
                    }
                    $total_expenses_lom_in585_point = $total_expenses_lom_in585_point + $in585;
                    $expenses_lom .= '
                    <tr>
                        <td>' . $child_operation->date . '</td>
                        <td>' . $child_operation->worker . '<br>' . $desc_multi . '</td>
                        <td>' . $child_operation->proba . '</td>
                        <td>' . number_format($child_operation->weight, 2, '.', ' ') . '</td>
                        <td>' . number_format($child_operation->price_gramm, 2, '.', ' ') . '</td>
                        <td>' . number_format($child_operation->price, 2, '.', ' ') . '</td>
                        <td>' . number_format($child_operation->pay, 2, '.', ' ') . '</td>
                        <td>' . number_format($profit, 2, '.', ' ') . '</td>
                        <td>' . number_format($in585, 2, '.', ' ') . '</td>
                    </tr>
                    ';
                }
            }
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
    //Точка



//Получение операций по продажам изделий
$income_izdelie = '';
$total_income_izdelie_sum = 0;
$bn_total_income_izdelie_sum = 0;

    //Точка
    $income_izdelie .= '<div class="report-table">';
    $income_izdelie .= '<p class="card-report__title_cash">' . $name_point . '</p>';
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
    //Точка



//Получение операций по скупкам изделий
$expenses_izdelie = '';
$total_expenses_izdelie_sum = 0;
$bn_total_expenses_izdelie_sum = 0;

    //Точка
    $expenses_izdelie .= '<div class="report-table">';
    $expenses_izdelie .= '<p class="card-report__title_cash">' . $name_point . '</p>';
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
    //Точка



//Получение операций дохода по кассам
$income = '';
$total_income = 0;
$bn_total_income = 0;

    //Точка
    $income .= '<div class="report-table">';
    $income .= '<p class="card-report__title_cash">' . $name_point . '</p>';
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
    //Точка



//Получение операций расхода по кассам
$expenses = '';
$total_expenses = 0;
$bn_total_expenses = 0;

    //Точка
    $expenses .= '<div class="report-table">';
    $expenses .= '<p class="card-report__title_cash">' . $name_point . '</p>';
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
            if ($item->cash_card == 'Смешанный расчет') {
                $total_expenses_point = $total_expenses_point + $item->multisum_nal;
                $bn_total_expenses_point = $bn_total_expenses_point + $item->multisum_beznal;
            }
            $expenses .= '
            <tr>
                <td>' . $item->date . '</td>
                <td>' . number_format($item->sum, 2, '.', ' ') . '</td>
                <td>' . $item->note . '</td>
            </tr>
            ';
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



// //Формирование таблицы с остатками
// $remain_tables_startday = '';
// $startday = $pages->get('id_point=' . $selected_id_point . '_startday');
// $actual = $pages->get('id_point=' . $selected_id_point . '_actual');
// $reserv = $pages->get('id_point=' . $selected_id_point . '_reserv');

// if ($startday != '' || $actual != '' || $reserv != '') {
// $actual_date = $startday->actual_date;
// include 'remains_table_archive.php';
// $remain_tables_startday .= '<h4 class="uk-card-title uk-margin-remove">Дата таблиц: ' . $actual_date . '</h4><hr>';
// }

// if ($startday == '' || $actual == '' || $reserv == '') {
//     $remain_tables_startday .= '
//     <h2 class="uk-margin-remove uk-card-title" style="color:red;font-weight:700;text-align:center;">Произошла ошибка получения остатков!<br>Пожалуйста обратитесь к разработчику!</h2>
//     ';
// } else {
//     include 'remains_table.php';
// }

?>

<div id="content">
    <div id="start"></div>
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Отчет по точке <?php echo $name_point; ?></h1>
    <h4 class="uk-margin-remove uk-heading-hero uk-text-center">За период с <?php echo $std; ?> по <?php echo $fid; ?></h4>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/adminpanel-meniu/">Админ панель</a>
                <a class="menu-link" href="/analiticheskii-otchet-start/">Аналитический отчет</a>
                <a class="menu-link" href="/osnovnoi-otchet/">Отчет за текущий день</a>
            </div>
        </div>

        <div>
            <div class="filtermenu uk-width-1-1">
                <form class="form-select-date" id="select_date" action="/otchet-za-den/" method="post">
                    <div class="uk-flex">
                        <div class="filtermenu-input">
                            <input class="uk-input" id="selected_on_date" type="date" name="selected_on_date" required>
                        </div>
                    </div>

                    <div class="uk-margin-small-top uk-width-1-1">
                        <button class="uk-margin-remove uk-button uk-button-default uk-width-1-1" type="submit">Отчет за дату</button>
                    </div>
                </form>
            </div>
        </div>

        <div>
            <div class="filtermenu uk-width-1-1">
                <form class="form-select-date" id="select_period_date" action="/otchet-za-period/" method="post">
                    <div class="uk-flex">
                        <div class="filtermenu-input">
                            <input class="uk-input" id="selected_start_date" type="date" name="selected_start_date" required>
                        </div>
                        <div class="filtermenu-input">
                            <input class="uk-input" id="selected_finish_date" type="date" name="selected_finish_date" required>
                        </div>
                    </div>
                    
                    <div class="uk-margin-small-top uk-width-1-1">
                        <button class="uk-margin-remove uk-button uk-button-default uk-width-1-1" type="submit">Отчет за период</button>
                    </div>
                </form>
            </div>
        </div>

        <div>
            <div class="filtermenu uk-width-1-1">
                <form class="form-select-date" id="select_period_date" action="/otchet-po-tochke/" method="post">
                    <div class="uk-flex">
                        <div class="filtermenu-input">
                            <input class="uk-input" id="point_start_date" type="date" name="point_start_date" required>
                        </div>
                        <div class="filtermenu-input">
                            <input class="uk-input" id="point_finish_date" type="date" name="point_finish_date" required>
                        </div>
                        <div class="filtermenu-input">
                            <select class="uk-select" id="report_point" name="report_point" required>
                                <option></option>
                                <?php echo $list_options; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="uk-margin-small-top uk-width-1-1">
                        <button class="uk-margin-remove uk-button uk-button-default uk-width-1-1" type="submit">Отчет по точке</button>
                    </div>
                </form>
            </div>
        </div>

        <div uk-sticky="sel-target: .flipmenu; cls-active: uk-navbar-sticky; offset: 0;">
            <input class="uk-input uk-hidden" id="download_start_date" type="text" name="download_start_date" value="<?php echo $std; ?>">
            <input class="uk-input uk-hidden" id="download_finish_date" type="text" name="download_finish_date" value="<?php echo $fid; ?>">
            <input class="uk-input uk-hidden" id="download_report_point" type="text" name="download_report_point" value="<?php echo $report_point; ?>">
            <div class="flipmenu pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="#start">Начало</a>
                <a class="menu-link" href="#income_lom">Продажи металла</a>
                <a class="menu-link" href="#expenses_lom">Скупки металла</a>
                <a class="menu-link" href="#income_izdelie">Продажа изделий</a>
                <a class="menu-link" href="#expenses_izdelie">Скупки изделий</a>
                <a class="menu-link" href="#income">Доходы</a>
                <a class="menu-link" href="#expenses">Расходы</a>
                <a class="menu-link" href="#arrears">Долги</a>
                <a id="download_period_point" class="menu-link" >Скачать</a>
            </div>
        </div>

        <!--
        <div>
            <div class="uk-card card-report uk-card-default uk-flex uk-flex-column">
                <h2 class="uk-card-title uk-margin-remove title-table-mainreport">Денег на утро</h2>
                <?php //echo $cash_on_morning; ?>
		    </div>
		</div>
        -->

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

        <!--
        <div>
            <div class="uk-card card-report uk-card-default uk-flex uk-flex-column">
                <h2 class="uk-card-title uk-margin-remove title-table-mainreport">Металл</h2>
                <?php echo $remain_tables_startday; ?>
            </div>
        </div>
        -->
        
    </div>
</div>

<?php   
}
?>