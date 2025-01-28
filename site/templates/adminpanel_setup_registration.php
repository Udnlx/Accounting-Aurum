<?php namespace ProcessWire;

$point = !empty($_POST['selected_point'])?$_POST['selected_point']:NULL;  
$idpoint = !empty($_POST['selected_idpoint'])?$_POST['selected_idpoint']:NULL;  
$worker = !empty($_POST['selected_worker'])?$_POST['selected_worker']:NULL;  

$main_price_gold = !empty($_POST['main_price_gold'])?$_POST['main_price_gold']:NULL;
$main_price_gold_999 = !empty($_POST['main_price_gold_999'])?$_POST['main_price_gold_999']:NULL;
$main_price_silver = !empty($_POST['main_price_silver'])?$_POST['main_price_silver']:NULL;
$main_price_platinum = !empty($_POST['main_price_platinum'])?$_POST['main_price_platinum']:NULL;
$main_price_palladium = !empty($_POST['main_price_palladium'])?$_POST['main_price_palladium']:NULL;

$success = 'Регистрация новых данных прошла успешно';
if ($point && $idpoint && $worker && $main_price_gold && $main_price_gold_999 && $main_price_silver && $main_price_platinum && $main_price_palladium) {
	//Меняем данные
    $edit_page = $main_options = $pages->get('template=main_options');
    $edit_page->of(false);
    $edit_page->main_price_gold = $main_price_gold;
    $edit_page->main_price_gold_999 = $main_price_gold_999;
    $edit_page->main_price_silver = $main_price_silver;
    $edit_page->main_price_platinum = $main_price_platinum;
    $edit_page->main_price_palladium = $main_price_palladium;
    $edit_page->save();

    //Записываем регистрацию  в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Были внесены изменения в настройки. ';
    $log .= 'Настройки изменены: ' . $worker;
    file_put_contents(__DIR__ . '/log_admin_setup.txt', $log . PHP_EOL, FILE_APPEND);
    $log = 'Цена на золото: ' . $main_price_gold;
    file_put_contents(__DIR__ . '/log_admin_setup.txt', $log . PHP_EOL, FILE_APPEND);
    $log = 'Цена на золото 999 пробы: ' . $main_price_gold_999;
    file_put_contents(__DIR__ . '/log_admin_setup.txt', $log . PHP_EOL, FILE_APPEND);
    $log = 'Цена на серебро: ' . $main_price_silver;
    file_put_contents(__DIR__ . '/log_admin_setup.txt', $log . PHP_EOL, FILE_APPEND);
    $log = 'Цена на платину: ' . $main_price_platinum;
    file_put_contents(__DIR__ . '/log_admin_setup.txt', $log . PHP_EOL, FILE_APPEND);
    $log = 'Цена на палладий: ' . $main_price_palladium;
    file_put_contents(__DIR__ . '/log_admin_setup.txt', $log . PHP_EOL, FILE_APPEND);

    header('Location: /adminpanel-nastroiki/');
} else {
	$success = 'Регистрация новых данных не прошла!<br>Ошибка в данных';
}

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
    	<h1 class="uk-heading-hero uk-text-center">Регистрация новых данных</h1>
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

?>

<div id="content">
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Регистрация новых данных</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
            </div>
        </div>

        <div>
            <h3 class="uk-card-title"><?php echo $success; ?></h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/adminpanel-nastroiki/">Вернутся к настройкам</a>
        </div>

        <br>
        
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