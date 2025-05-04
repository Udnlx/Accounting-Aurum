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

include 'main_close_access.php';

if ($operator == 'no_operator' || $selected_point == 'no_point' || $page_access == false) {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Закрытие смены</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title uk-text-center">Нет прав на эту страницу, потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {
//Проверка есть ли открытые заявки
$info = '';
$all_requests = $pages->find('template=close_day_request_itm, product_status=Открыта');
if (count($all_requests) > 0) {
    $info = '
    <h4 class="uk-card-title uk-margin-remove" style="color:red; text-align:center;">
        Внимание, у вас есть не закрытыте заявки смен с других точек.<br>
        Для продолжения нужно закрыть все открытые заявки.
    </h4>
    <div class="affinaj-link">
        <a class="affinaj-link-lnk" href="/zakrytie-smeny-osnovnaia-otkrytye-zaiavki/">Открытые заявки</a>
    </div>
    ';
} else {
    $info = '
    <h4 class="uk-card-title uk-margin-remove" style="color:red; text-align:center;">
        Убедитесь, прежде чем закрывать смену, что все операции по дню завершены.<br>
        После закрытия смены будет создан архив на выбранный день,<br>
        затем текущие остатки перенесутся в остатки на начало дня.<br>
        Начнется новая смена.
    </h4>
    <form class="uk-flex uk-flex-column" id="select_seat" action="/zakrytie-smeny-osnovnaia-registratciia/" method="post">
        <h4 class="uk-card-title uk-margin-remove" style="color:red; text-align:center;">
            Выберите дату закрытия смены.
        </h4>
        <div class="uk-margin-small-top">
            <input class="uk-input" id="close_date" type="date" name="close_date" value="" required>
        </div>
        <div class="uk-margin-small-top uk-flex uk-flex-column">
            <button class="uk-margin-small-top uk-button uk-button-default" type="submit">Закрыть смену</button>
        </div>
    </form>
    ';
}

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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Закрытие смены</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column" style="align-items:center;">
                <?php echo $info; ?>
            </div>
        </div>
        
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