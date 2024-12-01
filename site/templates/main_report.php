<?php namespace ProcessWire;

//$day_for_report = date("d-m-Y");
$day_for_report = '25-11-2024';

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
    	<h1 class="uk-heading-hero uk-text-center">Отчет</h1>
        <!-- <h4 class="uk-margin-remove uk-heading-hero uk-text-center">Все операции</h4> -->
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title">Потеряна сессия или точка, <br>возможно нет прав на эту страницу.</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {



//Денег на утро
$cash_on_morning = '';
$total_cash = 0;
$bn_total_cash = 0;
$page_cash = $pages->get('template=cash');
$all_cash = $page_cash->children();
foreach ($all_cash as $item) {
    $cash_on_morning .= '
        <p class="card-report__info">' . $item->title . ' наличка - <span style="font-weight:700;">' . number_format($item->cash_remain_startday, 2, '.',' ') . '</span></p>
        <p class="card-report__info">' . $item->title . ' безнал - <span style="font-weight:700;">' . number_format($item->bn_cash_remain_startday, 2, '.',' ') . '</span></p>
    ';
    $total_cash = $total_cash + $item->cash_remain_startday;
    $bn_total_cash = $bn_total_cash + $item->bn_cash_remain_startday;
}
$cash_on_morning .= '
<p class="card-report__info">Всего средств на утро наличка - <span style="color:green;font-weight:700;">' . number_format($total_cash, 2, '.',' ') . '</span></p>
<p class="card-report__info">Всего средств на утро безнал - <span style="color:green;font-weight:700;">' . number_format($bn_total_cash, 2, '.',' ') . '</span></p>
';



//Получение операций дохода по кассам
$income = '';
$total_income = 0;
$bn_total_income = 0;

    //Тверская 20
    $page_cash = $pages->get('template=cash_itm, id_point=point1_cash');
    $all_operation_cash_ondate = $page_cash->find('template=cash_operation, type_operation=Приход, date=' . $day_for_report . '');
    $income .= '<div class="report-table">';
    $income .= '<p class="card-report__title_cash">Тверская 20</p>';
    $income .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:50%">СУММА</th>
                    <th style="width:50%">ОПИСАНИЕ</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_income_point1 = 0;
    $bn_total_income_point1 = 0;
    foreach ($all_operation_cash_ondate as $item) {
        if ($item->cash_card == 'Наличный расчет') {
            $total_income_point1 = $total_income_point1 + $item->sum;
        }
        if ($item->cash_card == 'Безналичный расчет') {
            $bn_total_income_point1 = $bn_total_income_point1 + $item->sum;
        }
        $income .= '
        <tr>
            <td>' . number_format($item->sum, 2, '.', ' ') . '</td>
            <td>' . $item->note . '</td>
        </tr>
        ';
    }
    $income .= '
            </tbody>
        </table>
    ';
    $income .= '</div>';
    $income .= '
        <p class="card-report__title_cash">ИТОГО ДОХОД ПО ТОЧКЕ НАЛИЧКА: <span style="color: green;">' . number_format($total_income_point1, 2, '.', ' ') . '</span></p>
        <p class="card-report__title_cash">ИТОГО ДОХОД ПО ТОЧКЕ БЕЗНАЛ: <span style="color: green;">' . number_format($bn_total_income_point1, 2, '.', ' ') . '</span></p>
        <br>
        ';
    //Тверская 20

    //Тверская 14
    $page_cash = $pages->get('template=cash_itm, id_point=point2_cash');
    $all_operation_cash_ondate = $page_cash->find('template=cash_operation, type_operation=Приход, date=' . $day_for_report . '');
    $income .= '<div class="report-table">';
    $income .= '<p class="card-report__title_cash">Тверская 14</p>';
    $income .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:50%">СУММА</th>
                    <th style="width:50%">ОПИСАНИЕ</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_income_point2 = 0;
    $bn_total_income_point2 = 0;
    foreach ($all_operation_cash_ondate as $item) {
        if ($item->cash_card == 'Наличный расчет') {
            $total_income_point2 = $total_income_point2 + $item->sum;
        }
        if ($item->cash_card == 'Безналичный расчет') {
            $bn_total_income_point2 = $bn_total_income_point2 + $item->sum;
        }
        $income .= '
        <tr>
            <td>' . number_format($item->sum, 2, '.', ' ') . '</td>
            <td>' . $item->note . '</td>
        </tr>
        ';
    }
    $income .= '
            </tbody>
        </table>
    ';
    $income .= '</div>';
    $income .= '
        <p class="card-report__title_cash">ИТОГО ДОХОД ПО ТОЧКЕ НАЛИЧКА: <span style="color: green;">' . number_format($total_income_point2, 2, '.', ' ') . '</span></p>
        <p class="card-report__title_cash">ИТОГО ДОХОД ПО ТОЧКЕ БЕЗНАЛ: <span style="color: green;">' . number_format($bn_total_income_point2, 2, '.', ' ') . '</span></p>
        <br>
        ';
    //Тверская 14

    //Таганка
    $page_cash = $pages->get('template=cash_itm, id_point=point3_cash');
    $all_operation_cash_ondate = $page_cash->find('template=cash_operation, type_operation=Приход, date=' . $day_for_report . '');
    $income .= '<div class="report-table">';
    $income .= '<p class="card-report__title_cash">Таганка</p>';
    $income .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:50%">СУММА</th>
                    <th style="width:50%">ОПИСАНИЕ</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_income_point3 = 0;
    $bn_total_income_point3 = 0;
    foreach ($all_operation_cash_ondate as $item) {
        if ($item->cash_card == 'Наличный расчет') {
            $total_income_point3 = $total_income_point3 + $item->sum;
        }
        if ($item->cash_card == 'Безналичный расчет') {
            $bn_total_income_point3 = $bn_total_income_point3 + $item->sum;
        }
        $income .= '
        <tr>
            <td>' . number_format($item->sum, 2, '.', ' ') . '</td>
            <td>' . $item->note . '</td>
        </tr>
        ';
    }
    $income .= '
            </tbody>
        </table>
    ';
    $income .= '</div>';
    $income .= '
        <p class="card-report__title_cash">ИТОГО ДОХОД ПО ТОЧКЕ НАЛИЧКА: <span style="color: green;">' . number_format($total_income_point3, 2, '.', ' ') . '</span></p>
        <p class="card-report__title_cash">ИТОГО ДОХОД ПО ТОЧКЕ БЕЗНАЛ: <span style="color: green;">' . number_format($bn_total_income_point3, 2, '.', ' ') . '</span></p>
        <br>
        ';
    //Таганка

    //Комсомолка
    $page_cash = $pages->get('template=cash_itm, id_point=point4_cash');
    $all_operation_cash_ondate = $page_cash->find('template=cash_operation, type_operation=Приход, date=' . $day_for_report . '');
    $income .= '<div class="report-table">';
    $income .= '<p class="card-report__title_cash">Комсомолка</p>';
    $income .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:50%">СУММА</th>
                    <th style="width:50%">ОПИСАНИЕ</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_income_point4 = 0;
    $bn_total_income_point4 = 0;
    foreach ($all_operation_cash_ondate as $item) {
        if ($item->cash_card == 'Наличный расчет') {
            $total_income_point4 = $total_income_point4 + $item->sum;
        }
        if ($item->cash_card == 'Безналичный расчет') {
            $bn_total_income_point4 = $bn_total_income_point4 + $item->sum;
        }
        $income .= '
        <tr>
            <td>' . number_format($item->sum, 2, '.', ' ') . '</td>
            <td>' . $item->note . '</td>
        </tr>
        ';
    }
    $income .= '
            </tbody>
        </table>
    ';
    $income .= '</div>';
    $income .= '
        <p class="card-report__title_cash">ИТОГО ДОХОД ПО ТОЧКЕ НАЛИЧКА: <span style="color: green;">' . number_format($total_income_point4, 2, '.', ' ') . '</span></p>
        <p class="card-report__title_cash">ИТОГО ДОХОД ПО ТОЧКЕ БЕЗНАЛ: <span style="color: green;">' . number_format($bn_total_income_point4, 2, '.', ' ') . '</span></p>
        <br>
        ';
    //Комсомолка

$total_income = $total_income_point1 + $total_income_point2 + $total_income_point3 + $total_income_point4;
$bn_total_income = $bn_total_income_point1 + $bn_total_income_point2 + $bn_total_income_point3 + $bn_total_income_point4;
$income .= '<p class="card-report__title_cash">ОБЩИЙ ДОХОД ПО ВСЕМ ТОЧКАМ НАЛИЧКА: <span style="color: green;">' . number_format($total_income, 2, '.', ' ') . '</span></p>';
$income .= '<p class="card-report__title_cash">ОБЩИЙ ДОХОД ПО ВСЕМ ТОЧКАМ БЕЗНАЛ: <span style="color: green;">' . number_format($bn_total_income, 2, '.', ' ') . '</span></p>';



//Получение операций расхода по кассам
$expenses = '';
$total_expenses = 0;
$bn_total_expenses = 0;

    //Тверская 20
    $page_cash = $pages->get('template=cash_itm, id_point=point1_cash');
    $all_operation_cash_ondate = $page_cash->find('template=cash_operation, type_operation=Расход, date=' . $day_for_report . '');
    $expenses .= '<div class="report-table">';
    $expenses .= '<p class="card-report__title_cash">Тверская 20</p>';
    $expenses .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:50%">СУММА</th>
                    <th style="width:50%">ОПИСАНИЕ</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_expenses_point1 = 0;
    $bn_total_expenses_point1 = 0;
    foreach ($all_operation_cash_ondate as $item) {
        if ($item->cash_card == 'Наличный расчет') {
            $total_expenses_point1 = $total_expenses_point1 + $item->sum;
        }
        if ($item->cash_card == 'Безналичный расчет') {
            $bn_total_expenses_point1 = $bn_total_expenses_point1 + $item->sum;
        }
        $expenses .= '
        <tr>
            <td>' . number_format($item->sum, 2, '.', ' ') . '</td>
            <td>' . $item->note . '</td>
        </tr>
        ';
    }
    $expenses .= '
            </tbody>
        </table>
    ';
    $expenses .= '</div>';
    $expenses .= '
        <p class="card-report__title_cash">ИТОГО РАСХОД ПО ТОЧКЕ НАЛИЧКА: <span style="color: red;">' . number_format($total_expenses_point1, 2, '.', ' ') . '</span></p>
        <p class="card-report__title_cash">ИТОГО РАСХОД ПО ТОЧКЕ БЕЗНАЛ: <span style="color: red;">' . number_format($bn_total_expenses_point1, 2, '.', ' ') . '</span></p>
        <br>
        ';
    //Тверская 20

    //Тверская 14
    $page_cash = $pages->get('template=cash_itm, id_point=point2_cash');
    $all_operation_cash_ondate = $page_cash->find('template=cash_operation, type_operation=Расход, date=' . $day_for_report . '');
    $expenses .= '<div class="report-table">';
    $expenses .= '<p class="card-report__title_cash">Тверская 14</p>';
    $expenses .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:50%">СУММА</th>
                    <th style="width:50%">ОПИСАНИЕ</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_expenses_point2 = 0;
    $bn_total_expenses_point2 = 0;
    foreach ($all_operation_cash_ondate as $item) {
        if ($item->cash_card == 'Наличный расчет') {
            $total_expenses_point2 = $total_expenses_point2 + $item->sum;
        }
        if ($item->cash_card == 'Безналичный расчет') {
            $bn_total_expenses_point2 = $bn_total_expenses_point2 + $item->sum;
        }
        $expenses .= '
        <tr>
            <td>' . number_format($item->sum, 2, '.', ' ') . '</td>
            <td>' . $item->note . '</td>
        </tr>
        ';
    }
    $expenses .= '
            </tbody>
        </table>
    ';
    $expenses .= '</div>';
    $expenses .= '
        <p class="card-report__title_cash">ИТОГО РАСХОД ПО ТОЧКЕ НАЛИЧКА: <span style="color: red;">' . number_format($total_expenses_point2, 2, '.', ' ') . '</span></p>
        <p class="card-report__title_cash">ИТОГО РАСХОД ПО ТОЧКЕ БЕЗНАЛ: <span style="color: red;">' . number_format($bn_total_expenses_point2, 2, '.', ' ') . '</span></p>
        <br>
        ';
    //Тверская 14

    //Таганка
    $page_cash = $pages->get('template=cash_itm, id_point=point3_cash');
    $all_operation_cash_ondate = $page_cash->find('template=cash_operation, type_operation=Расход, date=' . $day_for_report . '');
    $expenses .= '<div class="report-table">';
    $expenses .= '<p class="card-report__title_cash">Таганка</p>';
    $expenses .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:50%">СУММА</th>
                    <th style="width:50%">ОПИСАНИЕ</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_expenses_point3 = 0;
    $bn_total_expenses_point3 = 0;
    foreach ($all_operation_cash_ondate as $item) {
        if ($item->cash_card == 'Наличный расчет') {
            $total_expenses_point3 = $total_expenses_point3 + $item->sum;
        }
        if ($item->cash_card == 'Безналичный расчет') {
            $bn_total_expenses_point3 = $bn_total_expenses_point3 + $item->sum;
        }
        $expenses .= '
        <tr>
            <td>' . number_format($item->sum, 2, '.', ' ') . '</td>
            <td>' . $item->note . '</td>
        </tr>
        ';
    }
    $expenses .= '
            </tbody>
        </table>
    ';
    $expenses .= '</div>';
    $expenses .= '
        <p class="card-report__title_cash">ИТОГО РАСХОД ПО ТОЧКЕ НАЛИЧКА: <span style="color: red;">' . number_format($total_expenses_point3, 2, '.', ' ') . '</span></p>
        <p class="card-report__title_cash">ИТОГО РАСХОД ПО ТОЧКЕ БЕЗНАЛ: <span style="color: red;">' . number_format($bn_total_expenses_point3, 2, '.', ' ') . '</span></p>
        <br>
        ';
    //Таганка

    //Комсомолка
    $page_cash = $pages->get('template=cash_itm, id_point=point4_cash');
    $all_operation_cash_ondate = $page_cash->find('template=cash_operation, type_operation=Расход, date=' . $day_for_report . '');
    $expenses .= '<div class="report-table">';
    $expenses .= '<p class="card-report__title_cash">Комсомолка</p>';
    $expenses .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:50%">СУММА</th>
                    <th style="width:50%">ОПИСАНИЕ</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_expenses_point4 = 0;
    $bn_total_expenses_point4 = 0;
    foreach ($all_operation_cash_ondate as $item) {
        if ($item->cash_card == 'Наличный расчет') {
            $total_expenses_point4 = $total_expenses_point4 + $item->sum;
        }
        if ($item->cash_card == 'Безналичный расчет') {
            $bn_total_expenses_point4 = $bn_total_expenses_point4 + $item->sum;
        }
        $expenses .= '
        <tr>
            <td>' . number_format($item->sum, 2, '.', ' ') . '</td>
            <td>' . $item->note . '</td>
        </tr>
        ';
    }
    $expenses .= '
            </tbody>
        </table>
    ';
    $expenses .= '</div>';
    $expenses .= '
        <p class="card-report__title_cash">ИТОГО РАСХОД ПО ТОЧКЕ НАЛИЧКА: <span style="color: red;">' . number_format($total_expenses_point4, 2, '.', ' ') . '</span></p>
        <p class="card-report__title_cash">ИТОГО РАСХОД ПО ТОЧКЕ БЕЗНАЛ: <span style="color: red;">' . number_format($bn_total_expenses_point4, 2, '.', ' ') . '</span></p>
        <br>
        ';
    //Комсомолка

$total_expenses = $total_expenses_point1 + $total_expenses_point2 + $total_expenses_point3 + $total_expenses_point4;
$bn_total_expenses = $bn_total_expenses_point1 + $bn_total_expenses_point2 + $bn_total_expenses_point3 + $bn_total_expenses_point4;
$expenses .= '<p class="card-report__title_cash">ОБЩИЙ РАСХОД ПО ВСЕМ ТОЧКАМ НАЛИЧКА: <span style="color: red;">' . number_format($total_expenses, 2, '.', ' ') . '</span></p>';
$expenses .= '<p class="card-report__title_cash">ОБЩИЙ РАСХОД ПО ВСЕМ ТОЧКАМ БЕЗНАЛ: <span style="color: red;">' . number_format($bn_total_expenses, 2, '.', ' ') . '</span></p>';



//Получение операций по продажам металла
$income_lom = '';
$total_income_lom_sum = 0;
$bn_total_income_lom_sum = 0;
$total_income_lom_in585 = 0;

    //Тверская 20
    $all_operation_lom_ondate = $pages->find('template=operation_itm, type_operation=Продажа, date=' . $day_for_report . '');
    $all_operation_lom_onpoint = $all_operation_lom_ondate->find('id_point=point1');
    $income_lom .= '<div class="report-table">';
    $income_lom .= '<p class="card-report__title_cash">Тверская 20</p>';
    $income_lom .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:14%">ОПЕРАТОР</th>
                    <th style="width:14%">ПРОБА</th>
                    <th style="width:14%">ВЕС</th>
                    <th style="width:14%">ЦЕНА ЗА ГРАММ</th>
                    <th style="width:14%">ЦЕНА ЗА ВСЕ</th>
                    <th style="width:14%">СКОЛЬКО ОТДАЛИ</th>
                    <th style="width:14%">В 585</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_income_lom_sum_point1 = 0;
    $bn_total_income_lom_sum_point1 = 0;
    $total_income_lom_in585_point1 = 0;
    foreach ($all_operation_lom_onpoint as $item) {
        if ($item->cash_card == 'Наличный расчет') {
            $total_income_lom_sum_point1 = $total_income_lom_sum_point1 + $item->pay;
        }
        if ($item->cash_card == 'Безналичный расчет') {
            $bn_total_income_lom_sum_point1 = $bn_total_income_lom_sum_point1 + $item->pay;
        }
        $in585 = ($item->weight/585*$item->proba);
        $total_income_lom_in585_point1 = $total_income_lom_in585_point1 + $in585;
        $income_lom .= '
        <tr>
            <td>' . $item->worker . '</td>
            <td>' . $item->proba . '</td>
            <td>' . number_format($item->weight, 2, '.', ' ') . '</td>
            <td>' . number_format($item->price_gramm, 2, '.', ' ') . '</td>
            <td>' . number_format($item->price, 2, '.', ' ') . '</td>
            <td>' . number_format($item->pay, 2, '.', ' ') . '</td>
            <td>' . number_format($in585, 2, '.', ' ') . '</td>
        </tr>
        ';
    }
    $income_lom .= '
            </tbody>
        </table>
    ';
    $income_lom .= '</div>';
    $income_lom .= '<p class="card-report__title_cash">ДОХОД НА ПРОДАЖАХ МЕТАЛЛА ПО ТОЧКЕ НАЛИЧКА: <span style="color: green;">' . number_format($total_income_lom_sum_point1, 2, '.', ' ') . '</span></p>';
    $income_lom .= '<p class="card-report__title_cash">ДОХОД НА ПРОДАЖАХ МЕТАЛЛА ПО ТОЧКЕ БЕЗНАЛ: <span style="color: green;">' . number_format($bn_total_income_lom_sum_point1, 2, '.', ' ') . '</span></p>';
    $income_lom .= '<p class="card-report__title_cash">ПРОДАННО МЕТАЛЛА НА ТОЧКЕ В 585 ПРОБЕ: <span style="color: green;">' . number_format($total_income_lom_in585_point1, 2, '.', ' ') . '</span></p><br>';
    //Тверская 20

    //Тверская 14
    $all_operation_lom_ondate = $pages->find('template=operation_itm, type_operation=Продажа, date=' . $day_for_report . '');
    $all_operation_lom_onpoint = $all_operation_lom_ondate->find('id_point=point2');
    $income_lom .= '<div class="report-table">';
    $income_lom .= '<p class="card-report__title_cash">Тверская 14</p>';
    $income_lom .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:14%">ОПЕРАТОР</th>
                    <th style="width:14%">ПРОБА</th>
                    <th style="width:14%">ВЕС</th>
                    <th style="width:14%">ЦЕНА ЗА ГРАММ</th>
                    <th style="width:14%">ЦЕНА ЗА ВСЕ</th>
                    <th style="width:14%">СКОЛЬКО ОТДАЛИ</th>
                    <th style="width:14%">В 585</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_income_lom_sum_point2 = 0;
    $bn_total_income_lom_sum_point2 = 0;
    $total_income_lom_in585_point2 = 0;
    foreach ($all_operation_lom_onpoint as $item) {
        if ($item->cash_card == 'Наличный расчет') {
            $total_income_lom_sum_point2 = $total_income_lom_sum_point2 + $item->pay;
        }
        if ($item->cash_card == 'Безналичный расчет') {
            $bn_total_income_lom_sum_point2 = $bn_total_income_lom_sum_point2 + $item->pay;
        }
        $in585 = ($item->weight/585*$item->proba);
        $total_income_lom_in585_point2 = $total_income_lom_in585_point2 + $in585;
        $income_lom .= '
        <tr>
            <td>' . $item->worker . '</td>
            <td>' . $item->proba . '</td>
            <td>' . number_format($item->weight, 2, '.', ' ') . '</td>
            <td>' . number_format($item->price_gramm, 2, '.', ' ') . '</td>
            <td>' . number_format($item->price, 2, '.', ' ') . '</td>
            <td>' . number_format($item->pay, 2, '.', ' ') . '</td>
            <td>' . number_format($in585, 2, '.', ' ') . '</td>
        </tr>
        ';
    }
    $income_lom .= '
            </tbody>
        </table>
    ';
    $income_lom .= '</div>';
    $income_lom .= '<p class="card-report__title_cash">ДОХОД НА ПРОДАЖАХ МЕТАЛЛА ПО ТОЧКЕ НАЛИЧКА: <span style="color: green;">' . number_format($total_income_lom_sum_point2, 2, '.', ' ') . '</span></p>';
    $income_lom .= '<p class="card-report__title_cash">ДОХОД НА ПРОДАЖАХ МЕТАЛЛА ПО ТОЧКЕ БЕЗНАЛ: <span style="color: green;">' . number_format($bn_total_income_lom_sum_point2, 2, '.', ' ') . '</span></p>';
    $income_lom .= '<p class="card-report__title_cash">ПРОДАННО МЕТАЛЛА НА ТОЧКЕ В 585 ПРОБЕ: <span style="color: green;">' . number_format($total_income_lom_in585_point2, 2, '.', ' ') . '</span></p><br>';
    //Тверская 14

    //Таганка
    $all_operation_lom_ondate = $pages->find('template=operation_itm, type_operation=Продажа, date=' . $day_for_report . '');
    $all_operation_lom_onpoint = $all_operation_lom_ondate->find('id_point=point3');
    $income_lom .= '<div class="report-table">';
    $income_lom .= '<p class="card-report__title_cash">Таганка</p>';
    $income_lom .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:14%">ОПЕРАТОР</th>
                    <th style="width:14%">ПРОБА</th>
                    <th style="width:14%">ВЕС</th>
                    <th style="width:14%">ЦЕНА ЗА ГРАММ</th>
                    <th style="width:14%">ЦЕНА ЗА ВСЕ</th>
                    <th style="width:14%">СКОЛЬКО ОТДАЛИ</th>
                    <th style="width:14%">В 585</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_income_lom_sum_point3 = 0;
    $bn_total_income_lom_sum_point3 = 0;
    $total_income_lom_in585_point3 = 0;
    foreach ($all_operation_lom_onpoint as $item) {
        if ($item->cash_card == 'Наличный расчет') {
            $total_income_lom_sum_point3 = $total_income_lom_sum_point3 + $item->pay;
        }
        if ($item->cash_card == 'Безналичный расчет') {
            $bn_total_income_lom_sum_point3 = $bn_total_income_lom_sum_point3 + $item->pay;
        }
        $in585 = ($item->weight/585*$item->proba);
        $total_income_lom_in585_point3 = $total_income_lom_in585_point3 + $in585;
        $income_lom .= '
        <tr>
            <td>' . $item->worker . '</td>
            <td>' . $item->proba . '</td>
            <td>' . number_format($item->weight, 2, '.', ' ') . '</td>
            <td>' . number_format($item->price_gramm, 2, '.', ' ') . '</td>
            <td>' . number_format($item->price, 2, '.', ' ') . '</td>
            <td>' . number_format($item->pay, 2, '.', ' ') . '</td>
            <td>' . number_format($in585, 2, '.', ' ') . '</td>
        </tr>
        ';
    }
    $income_lom .= '
            </tbody>
        </table>
    ';
    $income_lom .= '</div>';
    $income_lom .= '<p class="card-report__title_cash">ДОХОД НА ПРОДАЖАХ МЕТАЛЛА ПО ТОЧКЕ НАЛИЧКА: <span style="color: green;">' . number_format($total_income_lom_sum_point3, 2, '.', ' ') . '</span></p>';
    $income_lom .= '<p class="card-report__title_cash">ДОХОД НА ПРОДАЖАХ МЕТАЛЛА ПО ТОЧКЕ БЕЗНАЛ: <span style="color: green;">' . number_format($bn_total_income_lom_sum_point3, 2, '.', ' ') . '</span></p>';
    $income_lom .= '<p class="card-report__title_cash">ПРОДАННО МЕТАЛЛА НА ТОЧКЕ В 585 ПРОБЕ: <span style="color: green;">' . number_format($total_income_lom_in585_point3, 2, '.', ' ') . '</span></p><br>';
    //Таганка

    //Комсомолка
    $all_operation_lom_ondate = $pages->find('template=operation_itm, type_operation=Продажа, date=' . $day_for_report . '');
    $all_operation_lom_onpoint = $all_operation_lom_ondate->find('id_point=point4');
    $income_lom .= '<div class="report-table">';
    $income_lom .= '<p class="card-report__title_cash">Комсомолка</p>';
    $income_lom .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:14%">ОПЕРАТОР</th>
                    <th style="width:14%">ПРОБА</th>
                    <th style="width:14%">ВЕС</th>
                    <th style="width:14%">ЦЕНА ЗА ГРАММ</th>
                    <th style="width:14%">ЦЕНА ЗА ВСЕ</th>
                    <th style="width:14%">СКОЛЬКО ОТДАЛИ</th>
                    <th style="width:14%">В 585</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_income_lom_sum_point4 = 0;
    $bn_total_income_lom_sum_point4 = 0;
    $total_income_lom_in585_point4 = 0;
    foreach ($all_operation_lom_onpoint as $item) {
        if ($item->cash_card == 'Наличный расчет') {
            $total_income_lom_sum_point4 = $total_income_lom_sum_point4 + $item->pay;
        }
        if ($item->cash_card == 'Безналичный расчет') {
            $bn_total_income_lom_sum_point4 = $bn_total_income_lom_sum_point4 + $item->pay;
        }
        $in585 = ($item->weight/585*$item->proba);
        $total_income_lom_in585_point4 = $total_income_lom_in585_point4 + $in585;
        $income_lom .= '
        <tr>
            <td>' . $item->worker . '</td>
            <td>' . $item->proba . '</td>
            <td>' . number_format($item->weight, 2, '.', ' ') . '</td>
            <td>' . number_format($item->price_gramm, 2, '.', ' ') . '</td>
            <td>' . number_format($item->price, 2, '.', ' ') . '</td>
            <td>' . number_format($item->pay, 2, '.', ' ') . '</td>
            <td>' . number_format($in585, 2, '.', ' ') . '</td>
        </tr>
        ';
    }
    $income_lom .= '
            </tbody>
        </table>
    ';
    $income_lom .= '</div>';
    $income_lom .= '<p class="card-report__title_cash">ДОХОД НА ПРОДАЖАХ МЕТАЛЛА ПО ТОЧКЕ НАЛИЧКА: <span style="color: green;">' . number_format($total_income_lom_sum_point4, 2, '.', ' ') . '</span></p>';
    $income_lom .= '<p class="card-report__title_cash">ДОХОД НА ПРОДАЖАХ МЕТАЛЛА ПО ТОЧКЕ БЕЗНАЛ: <span style="color: green;">' . number_format($bn_total_income_lom_sum_point4, 2, '.', ' ') . '</span></p>';
    $income_lom .= '<p class="card-report__title_cash">ПРОДАННО МЕТАЛЛА НА ТОЧКЕ В 585 ПРОБЕ: <span style="color: green;">' . number_format($total_income_lom_in585_point4, 2, '.', ' ') . '</span></p><br>';
    //Комсомолка

$total_income_lom_sum = $total_income_lom_sum_point1 + $total_income_lom_sum_point2 + $total_income_lom_sum_point3 + $total_income_lom_sum_point4;
$bn_total_income_lom_sum = $bn_total_income_lom_sum_point1 + $bn_total_income_lom_sum_point2 + $bn_total_income_lom_sum_point3 + $bn_total_income_lom_sum_point4;
$total_income_lom_in585 = $total_income_lom_in585_point1 + $total_income_lom_in585_point2 + $total_income_lom_in585_point3 + $total_income_lom_in585_point4;
$income_lom .= '<p class="card-report__title_cash">ОБЩИЙ ДОХОД НА ПРОДАЖАХ МЕТАЛЛА ПО ВСЕМ ТОЧКАМ НАЛИЧКА: <span style="color: green;">' . number_format($total_income_lom_sum, 2, '.', ' ') . '</span></p>';
$income_lom .= '<p class="card-report__title_cash">ОБЩИЙ ДОХОД НА ПРОДАЖАХ МЕТАЛЛА ПО ВСЕМ ТОЧКАМ БЕЗНАЛ: <span style="color: green;">' . number_format($bn_total_income_lom_sum, 2, '.', ' ') . '</span></p>';
$income_lom .= '<p class="card-report__title_cash">ОБЩАЯ ПРОДАЖА МЕТАЛЛА ПО ВСЕМ ТОЧКАМ В 585 ПРОБЕ: <span style="color: green;">' . number_format($total_income_lom_in585, 2, '.', ' ') . '</span></p>';



//Получение операций по скупкам металла
$expenses_lom = '';
$total_expenses_lom_sum = 0;
$bn_total_expenses_lom_sum = 0;
$total_expenses_lom_in585 = 0;

    //Тверская 20
    $all_operation_lom_ondate = $pages->find('template=operation_itm, type_operation=Скупка, date=' . $day_for_report . '');
    $all_operation_lom_onpoint = $all_operation_lom_ondate->find('id_point=point1');
    $expenses_lom .= '<div class="report-table">';
    $expenses_lom .= '<p class="card-report__title_cash">Тверская 20</p>';
    $expenses_lom .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:14%">ОПЕРАТОР</th>
                    <th style="width:14%">ПРОБА</th>
                    <th style="width:14%">ВЕС</th>
                    <th style="width:14%">ЦЕНА ЗА ГРАММ</th>
                    <th style="width:14%">ЦЕНА ЗА ВСЕ</th>
                    <th style="width:14%">СКОЛЬКО ОТДАЛИ</th>
                    <th style="width:14%">В 585</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_expenses_lom_sum_point1 = 0;
    $bn_total_expenses_lom_sum_point1 = 0;
    $total_expenses_lom_in585_point1 = 0;
    foreach ($all_operation_lom_onpoint as $item) {
        if ($item->cash_card == 'Наличный расчет') {
            $total_expenses_lom_sum_point1 = $total_expenses_lom_sum_point1 + $item->pay;
        }
        if ($item->cash_card == 'Безналичный расчет') {
            $bn_total_expenses_lom_sum_point1 = $bn_total_expenses_lom_sum_point1 + $item->pay;
        }
        $in585 = ($item->weight/585*$item->proba);
        $total_expenses_lom_in585_point1 = $total_expenses_lom_in585_point1 + $in585;
        $expenses_lom .= '
        <tr>
            <td>' . $item->worker . '</td>
            <td>' . $item->proba . '</td>
            <td>' . number_format($item->weight, 2, '.', ' ') . '</td>
            <td>' . number_format($item->price_gramm, 2, '.', ' ') . '</td>
            <td>' . number_format($item->price, 2, '.', ' ') . '</td>
            <td>' . number_format($item->pay, 2, '.', ' ') . '</td>
            <td>' . number_format($in585, 2, '.', ' ') . '</td>
        </tr>
        ';
    }
    $expenses_lom .= '
            </tbody>
        </table>
    ';
    $expenses_lom .= '</div>';
    $expenses_lom .= '<p class="card-report__title_cash">РАСХОД НА СКУПКАХ МЕТАЛЛА ПО ТОЧКЕ НАЛИЧКА: <span style="color: red;">' . number_format($total_expenses_lom_sum_point1, 2, '.', ' ') . '</span></p>';
    $expenses_lom .= '<p class="card-report__title_cash">РАСХОД НА СКУПКАХ МЕТАЛЛА ПО ТОЧКЕ БЕЗНАЛ: <span style="color: red;">' . number_format($bn_total_expenses_lom_sum_point1, 2, '.', ' ') . '</span></p>';
    $expenses_lom .= '<p class="card-report__title_cash">КУПЛЕННО МЕТАЛЛА НА ТОЧКЕ В 585 ПРОБЕ: <span style="color: red;">' . number_format($total_expenses_lom_in585_point1, 2, '.', ' ') . '</span></p><br>';
    //Тверская 20

    //Тверская 14
    $all_operation_lom_ondate = $pages->find('template=operation_itm, type_operation=Скупка, date=' . $day_for_report . '');
    $all_operation_lom_onpoint = $all_operation_lom_ondate->find('id_point=point2');
    $expenses_lom .= '<div class="report-table">';
    $expenses_lom .= '<p class="card-report__title_cash">Тверская 14</p>';
    $expenses_lom .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:14%">ОПЕРАТОР</th>
                    <th style="width:14%">ПРОБА</th>
                    <th style="width:14%">ВЕС</th>
                    <th style="width:14%">ЦЕНА ЗА ГРАММ</th>
                    <th style="width:14%">ЦЕНА ЗА ВСЕ</th>
                    <th style="width:14%">СКОЛЬКО ОТДАЛИ</th>
                    <th style="width:14%">В 585</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_expenses_lom_sum_point2 = 0;
    $bn_total_expenses_lom_sum_point2 = 0;
    $total_expenses_lom_in585_point2 = 0;
    foreach ($all_operation_lom_onpoint as $item) {
        if ($item->cash_card == 'Наличный расчет') {
            $total_expenses_lom_sum_point2 = $total_expenses_lom_sum_point2 + $item->pay;
        }
        if ($item->cash_card == 'Безналичный расчет') {
            $bn_total_expenses_lom_sum_point2 = $bn_total_expenses_lom_sum_point2 + $item->pay;
        }
        $in585 = ($item->weight/585*$item->proba);
        $total_expenses_lom_in585_point2 = $total_expenses_lom_in585_point2 + $in585;
        $expenses_lom .= '
        <tr>
            <td>' . $item->worker . '</td>
            <td>' . $item->proba . '</td>
            <td>' . number_format($item->weight, 2, '.', ' ') . '</td>
            <td>' . number_format($item->price_gramm, 2, '.', ' ') . '</td>
            <td>' . number_format($item->price, 2, '.', ' ') . '</td>
            <td>' . number_format($item->pay, 2, '.', ' ') . '</td>
            <td>' . number_format($in585, 2, '.', ' ') . '</td>
        </tr>
        ';
    }
    $expenses_lom .= '
            </tbody>
        </table>
    ';
    $expenses_lom .= '</div>';
    $expenses_lom .= '<p class="card-report__title_cash">РАСХОД НА СКУПКАХ МЕТАЛЛА ПО ТОЧКЕ НАЛИЧКА: <span style="color: red;">' . number_format($total_expenses_lom_sum_point2, 2, '.', ' ') . '</span></p>';
    $expenses_lom .= '<p class="card-report__title_cash">РАСХОД НА СКУПКАХ МЕТАЛЛА ПО ТОЧКЕ БЕЗНАЛ: <span style="color: red;">' . number_format($bn_total_expenses_lom_sum_point2, 2, '.', ' ') . '</span></p>';
    $expenses_lom .= '<p class="card-report__title_cash">КУПЛЕННО МЕТАЛЛА НА ТОЧКЕ В 585 ПРОБЕ: <span style="color: red;">' . number_format($total_expenses_lom_in585_point2, 2, '.', ' ') . '</span></p><br>';
    //Тверская 14

    //Таганка
    $all_operation_lom_ondate = $pages->find('template=operation_itm, type_operation=Скупка, date=' . $day_for_report . '');
    $all_operation_lom_onpoint = $all_operation_lom_ondate->find('id_point=point3');
    $expenses_lom .= '<div class="report-table">';
    $expenses_lom .= '<p class="card-report__title_cash">Таганка</p>';
    $expenses_lom .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:14%">ОПЕРАТОР</th>
                    <th style="width:14%">ПРОБА</th>
                    <th style="width:14%">ВЕС</th>
                    <th style="width:14%">ЦЕНА ЗА ГРАММ</th>
                    <th style="width:14%">ЦЕНА ЗА ВСЕ</th>
                    <th style="width:14%">СКОЛЬКО ОТДАЛИ</th>
                    <th style="width:14%">В 585</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_expenses_lom_sum_point3 = 0;
    $bn_total_expenses_lom_sum_point3 = 0;
    $total_expenses_lom_in585_point3 = 0;
    foreach ($all_operation_lom_onpoint as $item) {
        if ($item->cash_card == 'Наличный расчет') {
            $total_expenses_lom_sum_point3 = $total_expenses_lom_sum_point3 + $item->pay;
        }
        if ($item->cash_card == 'Безналичный расчет') {
            $bn_total_expenses_lom_sum_point3 = $bn_total_expenses_lom_sum_point3 + $item->pay;
        }
        $in585 = ($item->weight/585*$item->proba);
        $total_expenses_lom_in585_point3 = $total_expenses_lom_in585_point3 + $in585;
        $expenses_lom .= '
        <tr>
            <td>' . $item->worker . '</td>
            <td>' . $item->proba . '</td>
            <td>' . number_format($item->weight, 2, '.', ' ') . '</td>
            <td>' . number_format($item->price_gramm, 2, '.', ' ') . '</td>
            <td>' . number_format($item->price, 2, '.', ' ') . '</td>
            <td>' . number_format($item->pay, 2, '.', ' ') . '</td>
            <td>' . number_format($in585, 2, '.', ' ') . '</td>
        </tr>
        ';
    }
    $expenses_lom .= '
            </tbody>
        </table>
    ';
    $expenses_lom .= '</div>';
    $expenses_lom .= '<p class="card-report__title_cash">РАСХОД НА СКУПКАХ МЕТАЛЛА ПО ТОЧКЕ НАЛИЧКА: <span style="color: red;">' . number_format($total_expenses_lom_sum_point3, 2, '.', ' ') . '</span></p>';
    $expenses_lom .= '<p class="card-report__title_cash">РАСХОД НА СКУПКАХ МЕТАЛЛА ПО ТОЧКЕ БЕЗНАЛ: <span style="color: red;">' . number_format($bn_total_expenses_lom_sum_point3, 2, '.', ' ') . '</span></p>';
    $expenses_lom .= '<p class="card-report__title_cash">КУПЛЕННО МЕТАЛЛА НА ТОЧКЕ В 585 ПРОБЕ: <span style="color: red;">' . number_format($total_expenses_lom_in585_point3, 2, '.', ' ') . '</span></p><br>';
    //Таганка

    //Комсомолка
    $all_operation_lom_ondate = $pages->find('template=operation_itm, type_operation=Скупка, date=' . $day_for_report . '');
    $all_operation_lom_onpoint = $all_operation_lom_ondate->find('id_point=point4');
    $expenses_lom .= '<div class="report-table">';
    $expenses_lom .= '<p class="card-report__title_cash">Комсомолка</p>';
    $expenses_lom .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:14%">ОПЕРАТОР</th>
                    <th style="width:14%">ПРОБА</th>
                    <th style="width:14%">ВЕС</th>
                    <th style="width:14%">ЦЕНА ЗА ГРАММ</th>
                    <th style="width:14%">ЦЕНА ЗА ВСЕ</th>
                    <th style="width:14%">СКОЛЬКО ОТДАЛИ</th>
                    <th style="width:14%">В 585</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_expenses_lom_sum_point4 = 0;
    $bn_total_expenses_lom_sum_point4 = 0;
    $total_expenses_lom_in585_point4 = 0;
    foreach ($all_operation_lom_onpoint as $item) {
        if ($item->cash_card == 'Наличный расчет') {
            $total_expenses_lom_sum_point4 = $total_expenses_lom_sum_point4 + $item->pay;
        }
        if ($item->cash_card == 'Безналичный расчет') {
            $bn_total_expenses_lom_sum_point4 = $bn_total_expenses_lom_sum_point4 + $item->pay;
        }
        $in585 = ($item->weight/585*$item->proba);
        $total_expenses_lom_in585_point4 = $total_expenses_lom_in585_point4 + $in585;
        $expenses_lom .= '
        <tr>
            <td>' . $item->worker . '</td>
            <td>' . $item->proba . '</td>
            <td>' . number_format($item->weight, 2, '.', ' ') . '</td>
            <td>' . number_format($item->price_gramm, 2, '.', ' ') . '</td>
            <td>' . number_format($item->price, 2, '.', ' ') . '</td>
            <td>' . number_format($item->pay, 2, '.', ' ') . '</td>
            <td>' . number_format($in585, 2, '.', ' ') . '</td>
        </tr>
        ';
    }
    $expenses_lom .= '
            </tbody>
        </table>
    ';
    $expenses_lom .= '</div>';
    $expenses_lom .= '<p class="card-report__title_cash">РАСХОД НА СКУПКАХ МЕТАЛЛА ПО ТОЧКЕ НАЛИЧКА: <span style="color: red;">' . number_format($total_expenses_lom_sum_point4, 2, '.', ' ') . '</span></p>';
    $expenses_lom .= '<p class="card-report__title_cash">РАСХОД НА СКУПКАХ МЕТАЛЛА ПО ТОЧКЕ БЕЗНАЛ: <span style="color: red;">' . number_format($bn_total_expenses_lom_sum_point4, 2, '.', ' ') . '</span></p>';
    $expenses_lom .= '<p class="card-report__title_cash">КУПЛЕННО МЕТАЛЛА НА ТОЧКЕ В 585 ПРОБЕ: <span style="color: red;">' . number_format($total_expenses_lom_in585_point4, 2, '.', ' ') . '</span></p><br>';
    //Комсомолка

$total_expenses_lom_sum = $total_expenses_lom_sum_point1 + $total_expenses_lom_sum_point2 + $total_expenses_lom_sum_point3 + $total_expenses_lom_sum_point4;
$bn_total_expenses_lom_sum = $bn_total_expenses_lom_sum_point1 + $bn_total_expenses_lom_sum_point2 + $bn_total_expenses_lom_sum_point3 + $bn_total_expenses_lom_sum_point4;
$total_expenses_lom_in585 = $total_expenses_lom_in585_point1 + $total_expenses_lom_in585_point2 + $total_expenses_lom_in585_point3 + $total_expenses_lom_in585_point4;
$expenses_lom .= '<p class="card-report__title_cash">ОБЩИЙ РАСХОД НА ПРОДАЖАХ МЕТАЛЛА ПО ВСЕМ ТОЧКАМ НАЛИЧКА: <span style="color: red;">' . number_format($total_expenses_lom_sum, 2, '.', ' ') . '</span></p>';
$expenses_lom .= '<p class="card-report__title_cash">ОБЩИЙ РАСХОД НА ПРОДАЖАХ МЕТАЛЛА ПО ВСЕМ ТОЧКАМ БЕЗНАЛ: <span style="color: red;">' . number_format($bn_total_expenses_lom_sum, 2, '.', ' ') . '</span></p>';
$expenses_lom .= '<p class="card-report__title_cash">ОБЩАЯ СКУПКА МЕТАЛЛА ПО ВСЕМ ТОЧКАМ В 585 ПРОБЕ: <span style="color: red;">' . number_format($total_expenses_lom_in585, 2, '.', ' ') . '</span></p>';



//Получение операций по продажам изделий
$income_izdelie = '';
$total_income_izdelie_sum = 0;
$bn_total_income_izdelie_sum = 0;

    //Тверская 20
    $all_operation_izdelie_ondate = $pages->find('template=product_itm, product_status=продано, product_date_sell=' . $day_for_report . '');
    $all_operation_izdelie_onpoint = $all_operation_izdelie_ondate->find('id_point=point1');
    $income_izdelie .= '<div class="report-table">';
    $income_izdelie .= '<p class="card-report__title_cash">Тверская 20</p>';
    $income_izdelie .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:20%">ОПЕРАТОР ПРОДАЖИ</th>
                    <th style="width:20%">ЦЕНА СКУПКИ</th>
                    <th style="width:20%">ЦЕНА ПРОДАЖИ</th>
                    <th style="width:20%">НАИМЕНОВАНИЕ</th>
                    <th style="width:20%">ВЕС</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_income_izdelie_sum_point1 = 0;
    $bn_total_income_izdelie_sum_point1 = 0;
    foreach ($all_operation_izdelie_onpoint as $item) {
        if ($item->cash_card_product_sell == 'Наличный расчет') {
            $total_income_izdelie_sum_point1 = $total_income_izdelie_sum_point1 + $item->product_price_sell;
        }
        if ($item->cash_card_product_sell == 'Безналичный расчет') {
            $bn_total_income_izdelie_sum_point1 = $bn_total_income_izdelie_sum_point1 + $item->product_price_sell;
        }
        $income_izdelie .= '
        <tr>
            <td>' . $item->worker_sell . '</td>
            <td>' . number_format($item->product_price_buy, 2, '.', ' ') . '</td>
            <td>' . number_format($item->product_price_sell, 2, '.', ' ') . '</td>
            <td>' . $item->product_name . '<br>' . $item->product_description . '</td>
            <td>' . number_format($item->weight, 2, '.', ' ') . '</td>
        </tr>
        ';
    }
    $income_izdelie .= '
            </tbody>
        </table>
    ';
    $income_izdelie .= '</div>';
    $income_izdelie .= '
        <p class="card-report__title_cash">ДОХОД НА ПРОДАЖАХ ИЗДЕЛИЙ ПО ТОЧКЕ НАЛИЧКА: <span style="color: green;">' . number_format($total_income_izdelie_sum_point1, 2, '.', ' ') . '</span></p>
        <p class="card-report__title_cash">ДОХОД НА ПРОДАЖАХ ИЗДЕЛИЙ ПО ТОЧКЕ БЕЗНАЛ: <span style="color: green;">' . number_format($bn_total_income_izdelie_sum_point1, 2, '.', ' ') . '</span></p>
        <br>
        ';
    //Тверская 20

    //Тверская 14
    $all_operation_izdelie_ondate = $pages->find('template=product_itm, product_status=продано, product_date_sell=' . $day_for_report . '');
    $all_operation_izdelie_onpoint = $all_operation_izdelie_ondate->find('id_point=point2');
    $income_izdelie .= '<div class="report-table">';
    $income_izdelie .= '<p class="card-report__title_cash">Тверская 14</p>';
    $income_izdelie .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:20%">ОПЕРАТОР ПРОДАЖИ</th>
                    <th style="width:20%">ЦЕНА СКУПКИ</th>
                    <th style="width:20%">ЦЕНА ПРОДАЖИ</th>
                    <th style="width:20%">НАИМЕНОВАНИЕ</th>
                    <th style="width:20%">ВЕС</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_income_izdelie_sum_point2 = 0;
    $bn_total_income_izdelie_sum_point2 = 0;
    foreach ($all_operation_izdelie_onpoint as $item) {
        if ($item->cash_card_product_sell == 'Наличный расчет') {
            $total_income_izdelie_sum_point2 = $total_income_izdelie_sum_point2 + $item->product_price_sell;
        }
        if ($item->cash_card_product_sell == 'Безналичный расчет') {
            $bn_total_income_izdelie_sum_point2 = $bn_total_income_izdelie_sum_point2 + $item->product_price_sell;
        }
        $income_izdelie .= '
        <tr>
            <td>' . $item->worker_sell . '</td>
            <td>' . number_format($item->product_price_buy, 2, '.', ' ') . '</td>
            <td>' . number_format($item->product_price_sell, 2, '.', ' ') . '</td>
            <td>' . $item->product_name . '<br>' . $item->product_description . '</td>
            <td>' . number_format($item->weight, 2, '.', ' ') . '</td>
        </tr>
        ';
    }
    $income_izdelie .= '
            </tbody>
        </table>
    ';
    $income_izdelie .= '</div>';
    $income_izdelie .= '
        <p class="card-report__title_cash">ДОХОД НА ПРОДАЖАХ ИЗДЕЛИЙ ПО ТОЧКЕ НАЛИЧКА: <span style="color: green;">' . number_format($total_income_izdelie_sum_point2, 2, '.', ' ') . '</span></p>
        <p class="card-report__title_cash">ДОХОД НА ПРОДАЖАХ ИЗДЕЛИЙ ПО ТОЧКЕ БЕЗНАЛ: <span style="color: green;">' . number_format($bn_total_income_izdelie_sum_point2, 2, '.', ' ') . '</span></p>
        <br>
        ';
    //Тверская 14

    //Таганка
    $all_operation_izdelie_ondate = $pages->find('template=product_itm, product_status=продано, product_date_sell=' . $day_for_report . '');
    $all_operation_izdelie_onpoint = $all_operation_izdelie_ondate->find('id_point=point3');
    $income_izdelie .= '<div class="report-table">';
    $income_izdelie .= '<p class="card-report__title_cash">Таганка</p>';
    $income_izdelie .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:20%">ОПЕРАТОР ПРОДАЖИ</th>
                    <th style="width:20%">ЦЕНА СКУПКИ</th>
                    <th style="width:20%">ЦЕНА ПРОДАЖИ</th>
                    <th style="width:20%">НАИМЕНОВАНИЕ</th>
                    <th style="width:20%">ВЕС</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_income_izdelie_sum_point3 = 0;
    $bn_total_income_izdelie_sum_point3 = 0;
    foreach ($all_operation_izdelie_onpoint as $item) {
        if ($item->cash_card_product_sell == 'Наличный расчет') {
            $total_income_izdelie_sum_point3 = $total_income_izdelie_sum_point3 + $item->product_price_sell;
        }
        if ($item->cash_card_product_sell == 'Безналичный расчет') {
            $bn_total_income_izdelie_sum_point3 = $bn_total_income_izdelie_sum_point3 + $item->product_price_sell;
        }
        $income_izdelie .= '
        <tr>
            <td>' . $item->worker_sell . '</td>
            <td>' . number_format($item->product_price_buy, 2, '.', ' ') . '</td>
            <td>' . number_format($item->product_price_sell, 2, '.', ' ') . '</td>
            <td>' . $item->product_name . '<br>' . $item->product_description . '</td>
            <td>' . number_format($item->weight, 2, '.', ' ') . '</td>
        </tr>
        ';
    }
    $income_izdelie .= '
            </tbody>
        </table>
    ';
    $income_izdelie .= '</div>';
    $income_izdelie .= '
        <p class="card-report__title_cash">ДОХОД НА ПРОДАЖАХ ИЗДЕЛИЙ ПО ТОЧКЕ НАЛИЧКА: <span style="color: green;">' . number_format($total_income_izdelie_sum_point3, 2, '.', ' ') . '</span></p>
        <p class="card-report__title_cash">ДОХОД НА ПРОДАЖАХ ИЗДЕЛИЙ ПО ТОЧКЕ БЕЗНАЛ: <span style="color: green;">' . number_format($bn_total_income_izdelie_sum_point3, 2, '.', ' ') . '</span></p>
        <br>
        ';
    //Таганка

    //Комсомолка
    $all_operation_izdelie_ondate = $pages->find('template=product_itm, product_status=продано, product_date_sell=' . $day_for_report . '');
    $all_operation_izdelie_onpoint = $all_operation_izdelie_ondate->find('id_point=point4');
    $income_izdelie .= '<div class="report-table">';
    $income_izdelie .= '<p class="card-report__title_cash">Комсомолка</p>';
    $income_izdelie .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:20%">ОПЕРАТОР ПРОДАЖИ</th>
                    <th style="width:20%">ЦЕНА СКУПКИ</th>
                    <th style="width:20%">ЦЕНА ПРОДАЖИ</th>
                    <th style="width:20%">НАИМЕНОВАНИЕ</th>
                    <th style="width:20%">ВЕС</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_income_izdelie_sum_point4 = 0;
    $bn_total_income_izdelie_sum_point4 = 0;
    foreach ($all_operation_izdelie_onpoint as $item) {
        if ($item->cash_card_product_sell == 'Наличный расчет') {
            $total_income_izdelie_sum_point4 = $total_income_izdelie_sum_point4 + $item->product_price_sell;
        }
        if ($item->cash_card_product_sell == 'Безналичный расчет') {
            $bn_total_income_izdelie_sum_point4 = $bn_total_income_izdelie_sum_point4 + $item->product_price_sell;
        }
        $income_izdelie .= '
        <tr>
            <td>' . $item->worker_sell . '</td>
            <td>' . number_format($item->product_price_buy, 2, '.', ' ') . '</td>
            <td>' . number_format($item->product_price_sell, 2, '.', ' ') . '</td>
            <td>' . $item->product_name . '<br>' . $item->product_description . '</td>
            <td>' . number_format($item->weight, 2, '.', ' ') . '</td>
        </tr>
        ';
    }
    $income_izdelie .= '
            </tbody>
        </table>
    ';
    $income_izdelie .= '</div>';
    $income_izdelie .= '
        <p class="card-report__title_cash">ДОХОД НА ПРОДАЖАХ ИЗДЕЛИЙ ПО ТОЧКЕ НАЛИЧКА: <span style="color: green;">' . number_format($total_income_izdelie_sum_point4, 2, '.', ' ') . '</span></p>
        <p class="card-report__title_cash">ДОХОД НА ПРОДАЖАХ ИЗДЕЛИЙ ПО ТОЧКЕ БЕЗНАЛ: <span style="color: green;">' . number_format($bn_total_income_izdelie_sum_point4, 2, '.', ' ') . '</span></p>
        <br>
        ';
    //Комсомолка

$total_income_izdelie_sum = $total_income_izdelie_sum_point1 + $total_income_izdelie_sum_point2 + $total_income_izdelie_sum_point3 + $total_income_izdelie_sum_point4;
$bn_total_income_izdelie_sum = $bn_total_income_izdelie_sum_point1 + $bn_total_income_izdelie_sum_point2 + $bn_total_income_izdelie_sum_point3 + $bn_total_income_izdelie_sum_point4;
$income_izdelie .= '<p class="card-report__title_cash">ОБЩИЙ ДОХОД НА ПРОДАЖАХ ИЗДЕЛИЙ ПО ВСЕМ ТОЧКАМ НАЛИЧКА: <span style="color: green;">' . number_format($total_income_izdelie_sum, 2, '.', ' ') . '</span></p>';
$income_izdelie .= '<p class="card-report__title_cash">ОБЩИЙ ДОХОД НА ПРОДАЖАХ ИЗДЕЛИЙ ПО ВСЕМ ТОЧКАМ БЕЗНАЛ: <span style="color: green;">' . number_format($bn_total_income_izdelie_sum, 2, '.', ' ') . '</span></p>';



//Получение операций по скупкам изделий
$expenses_izdelie = '';
$total_expenses_izdelie_sum = 0;
$bn_total_expenses_izdelie_sum = 0;

    //Тверская 20
    $all_operation_izdelie_ondate = $pages->find('template=product_itm, product_date_buy=' . $day_for_report . '');
    $all_operation_izdelie_onpoint = $all_operation_izdelie_ondate->find('id_point=point1');
    $expenses_izdelie .= '<div class="report-table">';
    $expenses_izdelie .= '<p class="card-report__title_cash">Тверская 20</p>';
    $expenses_izdelie .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:16%">ОПЕРАТОР СКУПКИ</th>
                    <th style="width:16%">ЦЕНА СКУПКИ</th>
                    <th style="width:16%">СТАТУС</th>
                    <th style="width:16%">ЦЕНА ПРОДАЖИ</th>
                    <th style="width:16%">НАИМЕНОВАНИЕ</th>
                    <th style="width:16%">ВЕС</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_expenses_izdelie_sum_point1 = 0;
    $bn_total_expenses_izdelie_sum_point1 = 0;
    foreach ($all_operation_izdelie_onpoint as $item) {
        if ($item->cash_card == 'Наличный расчет') {
            $total_expenses_izdelie_sum_point1 = $total_expenses_izdelie_sum_point1 + $item->product_price_buy;
        }
        if ($item->cash_card == 'Безналичный расчет') {
            $bn_total_expenses_izdelie_sum_point1 = $bn_total_expenses_izdelie_sum_point1 + $item->product_price_buy;
        }
        $expenses_izdelie .= '
        <tr>
            <td>' . $item->worker . '</td>
            <td>' . number_format($item->product_price_buy, 2, '.', ' ') . '</td>
            <td>' . $item->product_status . '</td>
            <td>' . number_format($item->product_price_sell, 2, '.', ' ') . '</td>
            <td>' . $item->product_name . '<br>' . $item->product_description . '</td>
            <td>' . number_format($item->weight, 2, '.', ' ') . '</td>
        </tr>
        ';
    }
    $expenses_izdelie .= '
            </tbody>
        </table>
    ';
    $expenses_izdelie .= '</div>';
    $expenses_izdelie .= '
        <p class="card-report__title_cash">РАСХОД НА СКУПКАХ ИЗДЕЛИЙ ПО ТОЧКЕ НАЛИЧКА: <span style="color: red;">' . number_format($total_expenses_izdelie_sum_point1, 2, '.', ' ') . '</span></p>
        <p class="card-report__title_cash">РАСХОД НА СКУПКАХ ИЗДЕЛИЙ ПО ТОЧКЕ БЕЗНАЛ: <span style="color: red;">' . number_format($bn_total_expenses_izdelie_sum_point1, 2, '.', ' ') . '</span></p>
        <br>';
    //Тверская 20

    //Тверская 14
    $all_operation_izdelie_ondate = $pages->find('template=product_itm, product_date_buy=' . $day_for_report . '');
    $all_operation_izdelie_onpoint = $all_operation_izdelie_ondate->find('id_point=point2');
    $expenses_izdelie .= '<div class="report-table">';
    $expenses_izdelie .= '<p class="card-report__title_cash">Тверская 14</p>';
    $expenses_izdelie .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:16%">ОПЕРАТОР СКУПКИ</th>
                    <th style="width:16%">ЦЕНА СКУПКИ</th>
                    <th style="width:16%">СТАТУС</th>
                    <th style="width:16%">ЦЕНА ПРОДАЖИ</th>
                    <th style="width:16%">НАИМЕНОВАНИЕ</th>
                    <th style="width:16%">ВЕС</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_expenses_izdelie_sum_point2 = 0;
    $bn_total_expenses_izdelie_sum_point2 = 0;
    foreach ($all_operation_izdelie_onpoint as $item) {
        if ($item->cash_card == 'Наличный расчет') {
            $total_expenses_izdelie_sum_point2 = $total_expenses_izdelie_sum_point2 + $item->product_price_buy;
        }
        if ($item->cash_card == 'Безналичный расчет') {
            $bn_total_expenses_izdelie_sum_point2 = $bn_total_expenses_izdelie_sum_point2 + $item->product_price_buy;
        }
        $expenses_izdelie .= '
        <tr>
            <td>' . $item->worker . '</td>
            <td>' . number_format($item->product_price_buy, 2, '.', ' ') . '</td>
            <td>' . $item->product_status . '</td>
            <td>' . number_format($item->product_price_sell, 2, '.', ' ') . '</td>
            <td>' . $item->product_name . '<br>' . $item->product_description . '</td>
            <td>' . number_format($item->weight, 2, '.', ' ') . '</td>
        </tr>
        ';
    }
    $expenses_izdelie .= '
            </tbody>
        </table>
    ';
    $expenses_izdelie .= '</div>';
    $expenses_izdelie .= '
        <p class="card-report__title_cash">РАСХОД НА СКУПКАХ ИЗДЕЛИЙ ПО ТОЧКЕ НАЛИЧКА: <span style="color: red;">' . number_format($total_expenses_izdelie_sum_point2, 2, '.', ' ') . '</span></p>
        <p class="card-report__title_cash">РАСХОД НА СКУПКАХ ИЗДЕЛИЙ ПО ТОЧКЕ БЕЗНАЛ: <span style="color: red;">' . number_format($bn_total_expenses_izdelie_sum_point2, 2, '.', ' ') . '</span></p>
        <br>';
    //Тверская 14

    //Таганка
    $all_operation_izdelie_ondate = $pages->find('template=product_itm, product_date_buy=' . $day_for_report . '');
    $all_operation_izdelie_onpoint = $all_operation_izdelie_ondate->find('id_point=point3');
    $expenses_izdelie .= '<div class="report-table">';
    $expenses_izdelie .= '<p class="card-report__title_cash">Таганка</p>';
    $expenses_izdelie .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:16%">ОПЕРАТОР СКУПКИ</th>
                    <th style="width:16%">ЦЕНА СКУПКИ</th>
                    <th style="width:16%">СТАТУС</th>
                    <th style="width:16%">ЦЕНА ПРОДАЖИ</th>
                    <th style="width:16%">НАИМЕНОВАНИЕ</th>
                    <th style="width:16%">ВЕС</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_expenses_izdelie_sum_point3 = 0;
    $bn_total_expenses_izdelie_sum_point3 = 0;
    foreach ($all_operation_izdelie_onpoint as $item) {
        if ($item->cash_card == 'Наличный расчет') {
            $total_expenses_izdelie_sum_point3 = $total_expenses_izdelie_sum_point3 + $item->product_price_buy;
        }
        if ($item->cash_card == 'Безналичный расчет') {
            $bn_total_expenses_izdelie_sum_point3 = $bn_total_expenses_izdelie_sum_point3 + $item->product_price_buy;
        }
        $expenses_izdelie .= '
        <tr>
            <td>' . $item->worker . '</td>
            <td>' . number_format($item->product_price_buy, 2, '.', ' ') . '</td>
            <td>' . $item->product_status . '</td>
            <td>' . number_format($item->product_price_sell, 2, '.', ' ') . '</td>
            <td>' . $item->product_name . '<br>' . $item->product_description . '</td>
            <td>' . number_format($item->weight, 2, '.', ' ') . '</td>
        </tr>
        ';
    }
    $expenses_izdelie .= '
            </tbody>
        </table>
    ';
    $expenses_izdelie .= '</div>';
    $expenses_izdelie .= '
        <p class="card-report__title_cash">РАСХОД НА СКУПКАХ ИЗДЕЛИЙ ПО ТОЧКЕ НАЛИЧКА: <span style="color: red;">' . number_format($total_expenses_izdelie_sum_point3, 2, '.', ' ') . '</span></p>
        <p class="card-report__title_cash">РАСХОД НА СКУПКАХ ИЗДЕЛИЙ ПО ТОЧКЕ БЕЗНАЛ: <span style="color: red;">' . number_format($bn_total_expenses_izdelie_sum_point3, 2, '.', ' ') . '</span></p>
        <br>';
    //Таганка

    //Комсомолка
    $all_operation_izdelie_ondate = $pages->find('template=product_itm, product_date_buy=' . $day_for_report . '');
    $all_operation_izdelie_onpoint = $all_operation_izdelie_ondate->find('id_point=point4');
    $expenses_izdelie .= '<div class="report-table">';
    $expenses_izdelie .= '<p class="card-report__title_cash">Комсомолка</p>';
    $expenses_izdelie .= '
        <table class="uk-table-striped">
            <thead>
                <tr>
                    <th style="width:16%">ОПЕРАТОР СКУПКИ</th>
                    <th style="width:16%">ЦЕНА СКУПКИ</th>
                    <th style="width:16%">СТАТУС</th>
                    <th style="width:16%">ЦЕНА ПРОДАЖИ</th>
                    <th style="width:16%">НАИМЕНОВАНИЕ</th>
                    <th style="width:16%">ВЕС</th>
                </tr>
            </thead>
            <tbody>
    ';
    $total_expenses_izdelie_sum_point4 = 0;
    $bn_total_expenses_izdelie_sum_point4 = 0;
    foreach ($all_operation_izdelie_onpoint as $item) {
        if ($item->cash_card == 'Наличный расчет') {
            $total_expenses_izdelie_sum_point4 = $total_expenses_izdelie_sum_point4 + $item->product_price_buy;
        }
        if ($item->cash_card == 'Безналичный расчет') {
            $bn_total_expenses_izdelie_sum_point4 = $bn_total_expenses_izdelie_sum_point4 + $item->product_price_buy;
        }
        $expenses_izdelie .= '
        <tr>
            <td>' . $item->worker . '</td>
            <td>' . number_format($item->product_price_buy, 2, '.', ' ') . '</td>
            <td>' . $item->product_status . '</td>
            <td>' . number_format($item->product_price_sell, 2, '.', ' ') . '</td>
            <td>' . $item->product_name . '<br>' . $item->product_description . '</td>
            <td>' . number_format($item->weight, 2, '.', ' ') . '</td>
        </tr>
        ';
    }
    $expenses_izdelie .= '
            </tbody>
        </table>
    ';
    $expenses_izdelie .= '</div>';
    $expenses_izdelie .= '
        <p class="card-report__title_cash">РАСХОД НА СКУПКАХ ИЗДЕЛИЙ ПО ТОЧКЕ НАЛИЧКА: <span style="color: red;">' . number_format($total_expenses_izdelie_sum_point4, 2, '.', ' ') . '</span></p>
        <p class="card-report__title_cash">РАСХОД НА СКУПКАХ ИЗДЕЛИЙ ПО ТОЧКЕ БЕЗНАЛ: <span style="color: red;">' . number_format($bn_total_expenses_izdelie_sum_point4, 2, '.', ' ') . '</span></p>
        <br>';
    //Комсомолка

$total_expenses_izdelie_sum = $total_expenses_izdelie_sum_point1 + $total_expenses_izdelie_sum_point2 + $total_expenses_izdelie_sum_point3 + $total_expenses_izdelie_sum_point4;
$bn_total_expenses_izdelie_sum = $bn_total_expenses_izdelie_sum_point1 + $bn_total_expenses_izdelie_sum_point2 + $bn_total_expenses_izdelie_sum_point3 + $bn_total_expenses_izdelie_sum_point4;
$expenses_izdelie .= '<p class="card-report__title_cash">ОБЩИЙ РАСХОД НА СКУПКАХ ИЗДЕЛИЙ ПО ВСЕМ ТОЧКАМ НАЛИЧКА: <span style="color: red;">' . number_format($total_expenses_izdelie_sum, 2, '.', ' ') . '</span></p>';
$expenses_izdelie .= '<p class="card-report__title_cash">ОБЩИЙ РАСХОД НА СКУПКАХ ИЗДЕЛИЙ ПО ВСЕМ ТОЧКАМ БЕЗНАЛ: <span style="color: red;">' . number_format($bn_total_expenses_izdelie_sum, 2, '.', ' ') . '</span></p>';



//Формирование таблицы с остатками
$remain_tables_startday = '';
$startday = $pages->get('id_point=' . $selected_id_point . '_startday');
$actual = $pages->get('id_point=' . $selected_id_point . '_actual');
$reserv = $pages->get('id_point=' . $selected_id_point . '_reserv');

if ($startday != '' || $actual != '' || $reserv != '') {
$actual_date = $startday->actual_date;
include 'remains_table_archive.php';
$remain_tables_startday .= '<h4 class="uk-card-title uk-margin-remove">Дата таблиц: ' . $actual_date . '</h4><hr>';
}

if ($startday == '' || $actual == '' || $reserv == '') {
    $remain_tables_startday .= '
    <h2 class="uk-margin-remove uk-card-title" style="color:red;font-weight:700;text-align:center;">Произошла ошибка получения остатков!<br>Пожалуйста обратитесь к разработчику!</h2>
    ';
} else {
    include 'remains_table.php';
}

?>

<div id="content">
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Отчет</h1>
    <h4 class="uk-margin-remove uk-heading-hero uk-text-center">На дату <?php echo $day_for_report; ?></h4>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/adminpanel-meniu">Админ панель</a>
            </div>
        </div>

        <div>
            <div class="filtermenu uk-width-1-1 uk-flex">
                <form class="form-select-date" id="select_date" action="" method="post">
                    <div class="filtermenu-input">
                        <input class="uk-input" id="selected_on_date" type="date" name="selected_on_date" required>
                    </div>
                    <div class="uk-margin-remove">
                        <button class="uk-margin-remove uk-button uk-button-default" type="submit">Отчет на дату в разработке</button>
                    </div>
                </form>
            </div>
        </div>

        <div>
            <div class="filtermenu uk-width-1-1 uk-flex">
                <form class="form-select-date" id="select_period_date" action="" method="post">
                    <div class="filtermenu-input">
                        <input class="uk-input" id="selected_start_date" type="date" name="selected_start_date" required>
                    </div>
                    <div class="filtermenu-input">
                        <input class="uk-input" id="selected_finish_date" type="date" name="selected_finish_date" required>
                    </div>
                    <div class="uk-margin-remove">
                        <button class="uk-margin-remove uk-button uk-button-default" type="submit">Отчет на период в разработке</button>
                    </div>
                </form>
            </div>
        </div>

        <div>
            <div class="uk-card card-report uk-card-default uk-flex uk-flex-column">
                <h2 class="uk-card-title uk-margin-remove title-table-mainreport">Денег на утро</h2>
                <?php echo $cash_on_morning; ?>
		    </div>
		</div>

        <div>
            <div class="uk-card card-report uk-card-default uk-flex uk-flex-column">
                <h2 class="uk-card-title uk-margin-remove title-table-mainreport">Доходы</h2>
                <?php echo $income; ?>
            </div>
        </div>

        <div>
            <div class="uk-card card-report uk-card-default uk-flex uk-flex-column">
                <h2 class="uk-card-title uk-margin-remove title-table-mainreport">Расходы</h2>
                <?php echo $expenses; ?>
            </div>
        </div>

        <div>
            <div class="uk-card card-report uk-card-default uk-flex uk-flex-column">
                <h2 class="uk-card-title uk-margin-remove title-table-mainreport">Продажи металла</h2>
                <?php echo $income_lom; ?>
            </div>
        </div>

        <div>
            <div class="uk-card card-report uk-card-default uk-flex uk-flex-column">
                <h2 class="uk-card-title uk-margin-remove title-table-mainreport">Скупки металла</h2>
                <?php echo $expenses_lom; ?>
            </div>
        </div>

        <div>
            <div class="uk-card card-report uk-card-default uk-flex uk-flex-column">
                <h2 class="uk-card-title uk-margin-remove title-table-mainreport">Продажи изделий</h2>
                <?php echo $income_izdelie; ?>
            </div>
        </div>

        <div>
            <div class="uk-card card-report uk-card-default uk-flex uk-flex-column">
                <h2 class="uk-card-title uk-margin-remove title-table-mainreport">Скупки изделий</h2>
                <?php echo $expenses_izdelie; ?>
            </div>
        </div>

        <div>
            <div class="uk-card card-report uk-card-default uk-flex uk-flex-column">
                <h2 class="uk-card-title uk-margin-remove title-table-mainreport">Металл</h2>
                <?php echo $remain_tables_startday; ?>
            </div>
        </div>
        
    </div>
</div>

<?php   
}
?>