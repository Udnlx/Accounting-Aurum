<?php

namespace ProcessWire;

$addw_worker = !empty($_GET['addw_worker'])?$_GET['addw_worker']:NULL; 
$addw_id_product = !empty($_GET['prod_id'])?$_GET['prod_id']:NULL;  
$addw_id = !empty($_GET['addw_id'])?$_GET['addw_id']:NULL;  
$addw_sum = !empty($_GET['addw_sum'])?$_GET['addw_sum']:NULL;  

//Получение страницы продукта
$product_page = $pages->get('id=' . $addw_id_product . '');

$success = 'Работа у изделия успешно удалена';
if ($addw_worker && $addw_id_product && $addw_id && $addw_sum && $_SESSION['reload'] != 'on') {
	$addw_description = $pages->get('template=product_itm, id=' . $addw_id_product . '')->addw_table->get('id=' . $addw_id . '')->description_operation;

	//Изменяем запись
    $edit_page = $pages->get('template=product_itm, id=' . $addw_id_product . '');
    $edit_page->of(false);
    $edit_page->product_price_buy = $edit_page->product_price_buy - $addw_sum;
    $edit_page->save();

    $addw_del = $pages->get('template=product_itm, id=' . $addw_id_product . '')->addw_table->get('id=' . $addw_id . '');
    $edit_page->of(false);
    $edit_page->addw_table->remove($addw_del);
 	$edit_page->save ();

    //Записываем регистрацию  в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Внесены изменения в продукт - ' . $product_page->title . '; ';
    $log .= 'Запись изменена: ' . $addw_worker . ', ID записи: ' . $product_page->id . '; '; 
    $log .= 'У изделия удалена работа: ' . $addw_description . '; '; 
    $log .= 'Сумма удаленной работы: ' . $addw_sum . '; '; 
    file_put_contents(__DIR__ . '/log_addw_changes.txt', $log . PHP_EOL, FILE_APPEND);

    //Предотвращаем повторную регистрацию
    $_SESSION['reload'] = 'on';
} else {
	$success = 'Работа не удалена!<br>Ошибка в данных';
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
    	<h1 class="uk-heading-hero uk-text-center">Удаление работы у изделия</h1>
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Удаление работы у изделия</h1>
	<div>

		<!--
        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/prodazha-izdelie/">Выбрать другое изделие</a>
            </div>
        </div>
    	-->

        <div>
            <h3 class="uk-card-title"><?php echo $success; ?></h3>
	        <p class="uk-margin-remove">ID записи изделия: <span style="font-weight: 700;"><?php echo $product_page->id; ?></span></p>
	        <p class="uk-margin-remove">Наименование: <span style="font-weight: 700;"><?php echo $product_page->title; ?></span></p>
	        <p class="uk-margin-remove">Измененная текущая цена: <span style="font-weight: 700;"><?php echo number_format($product_page->product_price_buy, 2, '.',' '); ?></span></p>
	        <a class="uk-margin-small uk-button uk-button-default" href="/prodazha-izdelie-vnesti-izmeneniia/?prod_id=<?php echo $addw_id_product; ?>">Вернутся к изделию</a>
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