<?php namespace ProcessWire;

$_SESSION['reload'] = 'off';

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
    	<h1 class="uk-heading-hero uk-text-center">Домашняя страница</h1>
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

if ($startday == '' || $actual == '' || $reserv == '') {
    $menu = '<br>';
    $remain_tables_startday .= '
    <h2 class="uk-margin-remove uk-card-title" style="color:red;font-weight:700;text-align:center;">Произошла ошибка получения остатков!<br>Пожалуйста обратитесь к разработчику!</h2>
    ';
}

if ($startday != '' || $actual != '' || $reserv != '') {
    $menu = '
    <div>
        <div class="pagemenu uk-width-1-1 uk-flex">
            <a class="menu-link" href="/skupka-tip-skupki/">Скупка</a>
            <a class="menu-link" href="">Продажа</a>
            <a class="menu-link" href="">Аффинаж</a>
            <a class="menu-link" href="">Админ панель</a>
        </div>
    </div>
    ';
    $actual_date = $startday->actual_date;
    include 'remains_table_archive.php';
    $remain_tables_startday .= '
    <h4 class="uk-card-title uk-margin-remove">Дата таблиц: ' . $actual_date . '</h4><hr>
    ';
    include 'remains_table.php';
}

?>

<div id="content">
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Домашняя страница</h1>
<!--     <h4 class="uk-margin-remove uk-heading-hero uk-text-center">Подзаголовок</h4> -->
	<div>
	    
        <?php echo $menu; ?>
        
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