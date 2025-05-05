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

include 'adminpanel_access.php';

if ($operator == 'no_operator' || $selected_point == 'no_point' || $page_access == false) {
?>
    <div id="content" style="max-width: 700px;">
        <h1 class="uk-heading-hero uk-text-center">Панель администратора</h1>
        <h4 class="uk-margin-remove uk-heading-hero uk-text-center">Весь резерв</h4>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title uk-text-center">Нет прав на эту страницу, потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {

//Получение всех операций
$all_operations = '';
$all_operations_itm = $pages->find('template=reserv_itm, sort=-sort, limit=20');
$all_operations .= '<div class="scrolling-list" style="max-height: 700px;">';
foreach ($all_operations_itm as $itm) {
    $all_operations .= '
    <div class="list-operation-itm">
        <div>
            <p>' . $itm->title . '</p>
            <p class="reserv_id_note" style="margin:0!important;">Для кого резерв: ' . $itm->description_operation . '</p>
            <p class="reserv_id_note" style="margin:0!important;">Оператор: ' . $itm->worker . '</p>
            <p class="reserv_id_note">' . $itm->reserv_note . '</p>
        </div>
    </div>
    ';
}
$all_operations .= '</div>';

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
    <h1 class="uk-margin-remove uk-heading-hero uk-text-center">Панель администратора</h1>
    <h4 class="uk-margin-remove uk-heading-hero uk-text-center">Весь резерв</h4>
    <div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/adminpanel-meniu/">Админ панель</a>
            </div>
        </div>

        <div>
            <h4 class="uk-card-title uk-margin-remove">Последние 20 операций по резерву, укажите период для поиска резерва</h4>
            <div class="filtermenu uk-width-1-1">
                <form class="form-select-date" id="select_period_date" action="/adminpanel-ves-rezerv-rezul-tat-poiska/" method="post">
                    <div class="uk-flex">
                        <div class="filtermenu-input">
                            <input class="uk-input" id="selected_start_date" type="date" name="selected_start_date" required>
                        </div>
                        <div class="filtermenu-input">
                            <input class="uk-input" id="selected_finish_date" type="date" name="selected_finish_date" required>
                        </div>
                    </div>

                    <div class="uk-margin-small-top uk-width-1-1">
                        <button class="uk-margin-remove uk-button uk-button-default uk-width-1-1" type="submit">Найти</button>
                    </div>
                </form>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <?php echo $all_operations; ?>
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