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
    	<h1 class="uk-heading-hero uk-text-center">Закрытие смен на других точках</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title">Потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {

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

//Получение открытых заявок закрытия смены по точкам
$all_requests = '';
$all_requests_itm = $pages->find('template=close_day_request_itm, product_status=Открыта, sort=-sort');
foreach ($all_requests_itm as $itm) {
    $all_requests .= '
        <p class="uk-margin-remove">' . $itm->title . '</p>
        <p class="reserv_id_note">Оператор: ' . $itm->worker . '</p>
        <div class="affinaj-link">
            <a class="affinaj-link-lnk" href="/zakrytie-smeny-osnovnaia-zaiavka-proverka/?id=' . $itm->id . '">Проверить и закрыть</a>
        </div><hr>
    ';
}
$open_request = '';
if ($all_requests) {
    $open_request .= '
    <div>
        <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
            <h4 class="uk-card-title uk-margin-remove">Открытые заявки</h4>
    ';
    $open_request .= '<hr>';
    $open_request .= $all_requests; 
    $open_request .= '
        </div>
    </div>
    ';
}

?>

<div id="content">
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Закрытие смен на других точках</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
            </div>
        </div>

        <?php echo $open_request; ?>
        
        <div>
            <div id="remain_tables" class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <?php echo $remain_tables_startday; ?>
            </div>
        </div>
        
    </div>
</div>

<?php   
}
?>