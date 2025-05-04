<?php namespace ProcessWire;

$worker = !empty($_POST['worker'])?$_POST['worker']:NULL;  
$id_product_changes = !empty($_POST['id_product_changes'])?$_POST['id_product_changes']:NULL;  
$old_product_description = !empty($_POST['old_product_description'])?$_POST['old_product_description']:NULL;  
$new_product_description = !empty($_POST['new_product_description'])?$_POST['new_product_description']:NULL;  
$old_product_url_avito = !empty($_POST['old_product_url_avito'])?$_POST['old_product_url_avito']:NULL;  
$new_product_url_avito = !empty($_POST['new_product_url_avito'])?$_POST['new_product_url_avito']:NULL; 
$old_product_url_image = !empty($_POST['old_product_url_image'])?$_POST['old_product_url_image']:NULL;  
$new_product_url_image = !empty($_POST['new_product_url_image'])?$_POST['new_product_url_image']:NULL; 

//Получение страницы продукта
$product_page = $pages->get('id=' . $id_product_changes . '');

$success = 'Изменения прошли успешно';
if ($worker && $id_product_changes && $old_product_description && $new_product_description && $_SESSION['reload'] != 'on') {
	//Изменяем запись
    $edit_page = $pages->get('template=product_itm, id=' . $product_page->id . '');
    $edit_page->of(false);
    $edit_page->product_description = $new_product_description;
    $edit_page->url_avito = $new_product_url_avito;
    $edit_page->url_image = $new_product_url_image;
    $edit_page->save();

    //Записываем регистрацию  в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Внесены изменения в продукт - ' . $product_page->title . '; ';
    $log .= 'Запись изменена: ' . $worker . ', ID записи: ' . $product_page->id . '; '; 
    $log .= 'Старое описание: ' . $old_product_description . '; '; 
    $log .= 'Новое описание: ' . $new_product_description . '; '; 
    $log .= 'Старый URL Авито: ' . $old_product_url_avito . '; '; 
    $log .= 'Новый URL Авито: ' . $new_product_url_avito . '; '; 
    if ($old_product_url_image != $new_product_url_image) {
        $log .= 'Была замена изображения; ';
    }
    file_put_contents(__DIR__ . '/log_products_changes.txt', $log . PHP_EOL, FILE_APPEND);

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

include 'prodaja_access.php';

if ($operator == 'no_operator' || $selected_point == 'no_point' || $page_access == false) {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Внесение изменений</h1>
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Внесение изменений</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/prodazha-izdelie/">Выбрать другое изделие</a>
            </div>
        </div>

        <div>
            <h3 class="uk-card-title"><?php echo $success; ?></h3>
	        <p class="uk-margin-remove">ID записи продукта: <span style="font-weight: 700;"><?php echo $product_page->id; ?></span></p>
            <p class="uk-margin-remove">Старое описание: <span style="font-weight: 700;"><?php echo $old_product_description; ?></span></p>
            <p class="uk-margin-remove">Новое описание: <span style="font-weight: 700;"><?php echo $new_product_description; ?></span></p>
            <p class="uk-margin-remove">Старое URL Авито: <span style="font-weight: 700;"><?php echo $old_product_url_avito; ?></span></p>
            <p class="uk-margin-remove">Новое URL Авито: <span style="font-weight: 700;"><?php echo $new_product_url_avito; ?></span></p>
            <p class="uk-margin-remove">Старое URL изображения: <span style="font-weight: 700;"><?php echo $old_product_url_image; ?></span></p>
            <p class="uk-margin-remove">Новое URL изображения: <span style="font-weight: 700;"><?php echo $new_product_url_image; ?></span></p>
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