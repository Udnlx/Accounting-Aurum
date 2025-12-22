<?php namespace ProcessWire;

error_reporting(E_ERROR | E_PARSE);

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

include 'affinaj_access.php';

if ($operator == 'no_operator' || $selected_point == 'no_point' || $page_access == false) {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Аффинаж по серебру отправлен</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title uk-text-center">Нет прав на эту страницу, потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {

//Изменяем статус на Отправлен
$id = $_GET['id'];
$edit_ag_page = $pages->get('template=affinaj_itm_ag, id=' . $id . '');
$edit_ag_page->of(false);
$edit_ag_page->product_status = 'Отправлен';
$edit_ag_page->save();

//Записываем в лог
$log = '';
$log .= date("Y-m-d H:i") . ' Отправлен аффинаж по серебру: ' . $edit_ag_page->title . ' === ';
$log .= 'Оператор: ' . $operator . ', ID записи: ' . $id; 
file_put_contents(__DIR__ . '/log_affinaj.txt', $log . PHP_EOL, FILE_APPEND);

//Формирование данных аффинажа
$id = $_GET['id'];
$affinaj_ag_page = $pages->get('id=' . $id . '');
$affinaj_ag_table = '';
$affinaj_ag_table = '
<div>
    <table class="uk-table-striped">
        <thead>
            <tr>
                <th style="width:20%">По пробам</th>
                <th style="width:20%">Должно быть</th>
                <th style="width:20%">По факту</th>
            </tr>
        </thead>
        <tbody>
';

$i = 1;
foreach ($affinaj_ag_page->affinaj_table as $itm) {
    $affinaj_ag_table .= '
    <tr>
        <td>' . $itm->proba . '</td>
        <td>' . $itm->fweight . '</td>
        <td>' . $itm->weight . '</td>
    </tr>
    ';
    $i++;
}

$affinaj_ag_table .= '
        </tbody>
    </table>
</div>
';


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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Аффинаж по серебру отправлен</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/affinazh-ag-raskhod/">Открытые и отправленные аффинажи</a>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <h4 class="uk-card-title uk-margin-remove">Данные аффинажа</h4>  
                <p class="uk-margin-remove" style="color: green;"><strong>Аффинаж успешно отправлен</strong></p>
                <p class="uk-margin-remove"><strong>ID: </strong><?php echo $id; ?></p>
                <p class="uk-margin-remove"><strong>Дата: </strong><?php echo $affinaj_ag_page->date; ?></p>
                <p class="uk-margin-remove"><strong>Точка: </strong><?php echo $affinaj_ag_page->point; ?></p>
                <p class="uk-margin-remove"><strong>Оператор: </strong><?php echo $affinaj_ag_page->worker; ?></p>
                <p class="uk-margin-remove"><strong>Cтатус: </strong><?php echo $affinaj_ag_page->product_status; ?></p>
                <?php echo $affinaj_ag_table; ?>
                <a class="uk-margin-small uk-button uk-button-default" href="/affinazh-ag-raskhod/">Открытые и отправленные аффинажи</a>
            </div>
        </div>
        
        <div>
            <div id="remain_tables" class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <?php echo $remain_tables_startday; ?>
            </div>
        </div>
        
    </div>
</div>

<script>
document.onkeydown = function (e) {
    if (e.keyCode === 116) {
    return false;
}};
</script>

<?php   
}
?>