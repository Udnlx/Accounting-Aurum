<?php namespace ProcessWire;

//Получаем список точек для выбора и отчета
$all_points = $pages->find('template=points_itm');
$list_options = '';
$points = [];
foreach ($all_points as $point) {
    $list_options .= '<option value="' . $point->id_point . '">' . $point->title . '</option>';
    $points[] = $point->id_point;
}

$day_for_report = date("d-m-Y");
//$day_for_report = '26-12-2024';

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
            <h3 class="uk-card-title uk-text-center">Нет прав на эту страницу, потеряна сессия или точка, перезайти</h3>
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

?>

<div id="content">
    <div id="start"></div>
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Отчет</h1>
    <h4 class="uk-margin-remove uk-heading-hero uk-text-center">За дату <?php echo $day_for_report; ?></h4>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/osnovnoi-otchet/">Обычный отчет</a>
                <a class="menu-link" href="/adminpanel-meniu/">Админ панель</a>
            </div>
        </div>

        <div>
            <div class="filtermenu uk-width-1-1">
                <form class="form-select-date" id="select_period_date" action="/analiticheskii-otchet-finish/" method="post">
                    <p class="uk-margin-remove">Выберите интересующий период</p>
                    <div class="uk-flex">
                        <div class="filtermenu-input">
                            <input class="uk-input" id="selected_astart_date" type="date" name="selected_astart_date" required>
                        </div>
                        <div class="filtermenu-input">
                            <input class="uk-input" id="selected_afinish_date" type="date" name="selected_afinish_date" required>
                        </div>
                    </div>
                    <br>
                    <p class="uk-margin-remove">Выберите период для сравнения</p>
                    <div class="uk-flex">
                        <div class="filtermenu-input">
                            <input class="uk-input" id="selected_bstart_date" type="date" name="selected_bstart_date" required>
                        </div>
                        <div class="filtermenu-input">
                            <input class="uk-input" id="selected_bfinish_date" type="date" name="selected_bfinish_date" required>
                        </div>
                    </div>
                    
                    <div class="uk-margin-small-top uk-width-1-1">
                        <button class="uk-margin-remove uk-button uk-button-default uk-width-1-1" type="submit">Сформировать</button>
                    </div>
                </form>
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