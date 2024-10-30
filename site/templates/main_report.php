<?php namespace ProcessWire;

$day_for_report = date("d-m-Y");

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
$page_cash = $pages->get('template=cash');
$all_cash = $page_cash->children();
foreach ($all_cash as $item) {
    $cash_on_morning .= '
        <p class="card-report__info">' . $item->title . ' - <span style="font-weight:700;">' . number_format($item->cash_remain_startday, 2, '.',' ') . '</span></p>
    ';
    $total_cash = $total_cash + $item->cash_remain_startday;
}
$cash_on_morning .= '
<p class="card-report__info">Всего средств на утро - <span style="color:green;font-weight:700;">' . number_format($total_cash, 2, '.',' ') . '</span></p>
';

//Получение операций дохода по кассам
$income = '';
//ул. Ушакова 23
$page_cash = $pages->get('template=cash_itm, id_point=point1_cash');
$all_operation_cash_ondate = $page_cash->find('template=cash_operation, type_operation=Приход, date=' . $day_for_report . '');
$income .= '<div class="report-table">';
$income .= '<p class="card-report__title_cash">ул. Ушакова 23</p>';
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
foreach ($all_operation_cash_ondate as $item) {
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

//ул. Пушкина 24
$page_cash = $pages->get('template=cash_itm, id_point=point2_cash');
$all_operation_cash_ondate = $page_cash->find('template=cash_operation, type_operation=Приход, date=' . $day_for_report . '');
$income .= '<div class="report-table">';
$income .= '<p class="card-report__title_cash">ул. Пушкина 24</p>';
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
foreach ($all_operation_cash_ondate as $item) {
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

//Получение операций расхода по кассам
$expenses = '';
//ул. Ушакова 23
$page_cash = $pages->get('template=cash_itm, id_point=point1_cash');
$all_operation_cash_ondate = $page_cash->find('template=cash_operation, type_operation=Расход, date=' . $day_for_report . '');
$expenses .= '<div class="report-table">';
$expenses .= '<p class="card-report__title_cash">ул. Ушакова 23</p>';
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
foreach ($all_operation_cash_ondate as $item) {
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

//ул. Пушкина 24
$page_cash = $pages->get('template=cash_itm, id_point=point2_cash');
$all_operation_cash_ondate = $page_cash->find('template=cash_operation, type_operation=Расход, date=' . $day_for_report . '');
$expenses .= '<div class="report-table">';
$expenses .= '<p class="card-report__title_cash">ул. Пушкина 24</p>';
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
foreach ($all_operation_cash_ondate as $item) {
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
            </div>
        </div>

        <div>
            <div class="uk-card card-report uk-card-default uk-flex uk-flex-column">
                <h4 class="uk-card-title uk-margin-remove">Денег на утро</h4>
                <?php echo $cash_on_morning; ?>
		    </div>
		</div>

        <div>
            <div class="uk-card card-report uk-card-default uk-flex uk-flex-column">
                <h4 class="uk-card-title uk-margin-remove">Доходы</h4>
                <?php echo $income; ?>
            </div>
        </div>

        <div>
            <div class="uk-card card-report uk-card-default uk-flex uk-flex-column">
                <h4 class="uk-card-title uk-margin-remove">Расходы</h4>
                <?php echo $expenses; ?>
            </div>
        </div>

        <div>
            <div class="uk-card card-report uk-card-default uk-flex uk-flex-column">
                <h4 class="uk-card-title uk-margin-remove">Металл</h4>
                <?php echo $remain_tables_startday; ?>
            </div>
        </div>
        
    </div>
</div>

<?php   
}
?>