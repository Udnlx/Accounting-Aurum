<?php namespace ProcessWire;

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

if ($operator == 'no_operator' || $selected_point == 'no_point') {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Тип операции по кассе</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title">Потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {

$page_cash = $pages->get('template=cash_itm, id_point=' . $selected_id_point . '_cash');
$cash = number_format($page_cash->sum, 2, '.',' ');
$cash_startday = number_format($page_cash->cash_remain_startday, 2, '.',' ');

//Получение всех операций по кассе
$all_cash_operation = '';
$all_cash_operation_itm = $pages->get('template=cash_itm, id_point=' . $selected_id_point . '_cash');
$all_operation = $all_cash_operation_itm->children('sort=-id, limit=20');

$all_cash_operation .= '<div class="scrolling-list" style="max-height: 700px;">';
foreach ($all_operation as $itm) {
    $all_cash_operation .= '
        <p>' . $itm->title . '</p>
        <p style="font-size:10px;">ID операции: ' . $itm->id . '</p>
        <p style="font-size:10px;">Оператор: ' . $itm->worker . '</p>
        <p style="font-size:14px;font-weight:700;">Тип операции: ' . $itm->type_operation . ' - ' . $itm->sum . '</p>
        <p style="font-size:10px;">Описание: ' . $itm->note . '</p>
        <hr>
    ';
}
$all_cash_operation .= '</div>';

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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Тип операции по кассе</h1>
    <h3 class="uk-margin-remove uk-heading-hero uk-text-center">Касса точки</h3>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/kassa-raskhod/">Касса расход</a>
                <a class="menu-link" href="/kassa-prikhod/">Касса приход</a>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <p class="uk-margin-remove">Касса на начало дня: <?php echo $cash_startday; ?></p>
                <h2 class="uk-card-title uk-margin-remove" style="color: green;font-weight: 700;">В Кассе: <?php echo $cash; ?></h2>
                <h4 class="uk-card-title uk-margin-remove">Последние 20 операций по кассе</h4>
                <hr>
                <div id="all_cash_operation">
                    <?php echo $all_cash_operation; ?>
                </div>
            </div>
        </div>
        
        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <?php echo $remain_tables_startday; ?>
            </div>
        </div>
        
    </div>
</div>

<?php   
}
?>