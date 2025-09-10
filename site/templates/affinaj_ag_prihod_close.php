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

include 'affinaj_access.php';

if ($operator == 'no_operator' || $selected_point == 'no_point' || $page_access == false) {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Аффинаж по серебру на закрытие</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title uk-text-center">Нет прав на эту страницу, потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {

//Формирование данных аффинажа
$id = $_GET['id'];
$affinaj_ag_page = $pages->get('id=' . $id . '');
$affinaj_ag_table = '';
$affinaj_ag_table = '
<div>
    <table class="uk-table-striped">
        <thead>
            <tr>
                <th style="width:33%">По пробам</th>
                <th style="width:33%">Должно быть</th>
                <th style="width:33%">По факту</th>
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Аффинаж по серебру на закрытие</h1>
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
                <p class="uk-margin-remove" style="color: green;"><strong>Аффинаж готов к закрытию</strong></p>
                <p class="uk-margin-remove"><strong>ID: </strong><?php echo $id; ?></p>
                <p class="uk-margin-remove"><strong>Дата: </strong><?php echo $affinaj_ag_page->date; ?></p>
                <p class="uk-margin-remove"><strong>Точка: </strong><?php echo $affinaj_ag_page->point; ?></p>
                <p class="uk-margin-remove"><strong>Оператор: </strong><?php echo $affinaj_ag_page->worker; ?></p>
                <p class="uk-margin-remove"><strong>Cтатус: </strong><?php echo $affinaj_ag_page->product_status; ?></p>
                <div class="uk-margin-small-top uk-hidden">
                    <input class="uk-input" id="affinaj_ag_date" type="text" name="affinaj_ag_date" value="<?php echo $today; ?>">
                </div>
                <div class="uk-margin-small-top uk-hidden">
                    <input class="uk-input" id="affinaj_ag_point" type="text" name="affinaj_ag_point" value="<?php echo $selected_point; ?>">
                </div>
                <div class="uk-margin-small-top uk-hidden">
                 <input class="uk-input" id="affinaj_ag_idpoint" type="text" name="affinaj_ag_idpoint" value="<?php echo $selected_id_point; ?>">
                </div>
                <div class="uk-margin-small-top uk-hidden">
                    <input class="uk-input" id="affinaj_ag_worker" type="text" name="affinaj_ag_worker" value="<?php echo $operator; ?>">
                </div>
                <div class="uk-margin-small-top uk-hidden">
                    <input class="uk-input" id="affinaj_ag_id" type="text" name="affinaj_ag_id" value="<?php echo $id; ?>">
                </div>
                <?php echo $affinaj_ag_table; ?>
                <div class="uk-margin-small-top">
                    <label for="price_gramm">Пришедший вес 999 пробы после аффинажа</label>
                    <input class="uk-input close_weight_affinaj" id="close_weight_affinaj_ag" type="text" name="close_weight_affinaj_ag" value="" placeholder="Внесите вес при закрытии аффинажа, обязательное поле" autocomplete="off" required>
                </div>
                <a id="close_affinaj_ag" class="uk-margin-small uk-button uk-button-default">Закрыть</a>
            </div>
            <div id="result_close_affinaj" class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column" style="padding: 0 40px;">
                <p id="result_add" class="messages" style="color: green;"></p>
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