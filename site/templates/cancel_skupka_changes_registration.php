<?php namespace ProcessWire;

$worker = !empty($_POST['selected_worker'])?$_POST['selected_worker']:NULL;  
$id_operation_changes = !empty($_POST['id_operation_changes'])?$_POST['id_operation_changes']:NULL;  

//Получение страницы продукта
$operation_page = $pages->get('id=' . $id_operation_changes . '');

$success = 'Изменения прошли успешно';
if ($worker && $id_operation_changes && $_SESSION['reload'] != 'on') {
	// //Изменяем запись
 //    $edit_page = $pages->get('template=product_itm, id=' . $operation_page->id . '');
 //    $edit_page->of(false);
 //    $edit_page->product_description = $new_product_description;
 //    $edit_page->url_avito = $new_product_url_avito;
 //    $edit_page->save();

    // //Записываем регистрацию  в лог
    // $log = '';
    // $log .= date("Y-m-d H:i") . ' Внесены изменения в продукт - ' . $operation_page->title . '; ';
    // $log .= 'Запись изменена: ' . $worker . ', ID записи: ' . $operation_page->id . '; '; 
    // $log .= 'Старое описание: ' . $old_product_description . '; '; 
    // $log .= 'Новое описание: ' . $new_product_description . '; '; 
    // $log .= 'Старый URL Авито: ' . $old_product_url_avito . '; '; 
    // $log .= 'Новый URL Авито: ' . $new_product_url_avito . '; '; 
    // file_put_contents(__DIR__ . '/log_products_changes.txt', $log . PHP_EOL, FILE_APPEND);

    //Предотвращаем повторную регистрацию
    $_SESSION['reload'] = 'on';
} else {
	$success = 'Изменения не прошли!<br>Ошибка в данных';
    if ($_SESSION['reload'] == 'on') {
        $success = 'Повторная отправка данных!';
    }
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
    	<h1 class="uk-heading-hero uk-text-center">Внесение изменений</h1>
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Внесение изменений</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/otmena-skupka-lom/">Выбрать другую скупку</a>
            </div>
        </div>

        <div>
            <h3 class="uk-card-title"><?php echo $success; ?></h3>
	        <p class="uk-margin-remove">ID записи операции: <span style="font-weight: 700;"><?php echo $operation_page->id; ?></span></p>
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