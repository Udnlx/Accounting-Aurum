<?php

namespace ProcessWire;

$addw_worker = !empty($_POST['addw_worker'])?$_POST['addw_worker']:NULL; 
$addw_id_product = !empty($_POST['addw_id_product'])?$_POST['addw_id_product']:NULL; 
$addw_description = !empty($_POST['addw_description'])?$_POST['addw_description']:NULL; 
$addw_sum = !empty($_POST['addw_sum'])?$_POST['addw_sum']:NULL; 

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

include 'addw_access.php';

//Получение страницы продукта
$product_page = $pages->get('id=' . $addw_id_product . '');

$success = 'Работа к изделию добавлена успешно';
if ($addw_worker && $addw_id_product && $addw_description && $addw_sum && $_SESSION['reload'] != 'on') {
	//Изменяем запись
    $edit_page = $pages->get('template=product_itm, id=' . $addw_id_product . '');
    $edit_page->of(false);
    $edit_page->product_price_buy = $edit_page->product_price_buy + $addw_sum;
    $edit_page->save();

    $addw_add = $pages->get('template=product_itm, id=' . $addw_id_product . '')->addw_table->getNew();
    $addw_add->description_operation = $addw_description;
    $addw_add->sum = $addw_sum;
    $addw_add->save();

    //Регестрируем операцию расхода в кассу
    $page_cash = $pages->get('template=cash_itm, id_point=' . $selected_id_point . '_cash');
    $pages->add('cash_operation', $page_cash , [
    'title' => date("Y-m-d H:i") . ' Расход - ' . $addw_sum . ' - ' . $selected_point,
    'type_operation' => 'Расход',
    'date' => date("d-m-Y"),
    'point' => $selected_point,
    'id_point' => $selected_id_point,
    'worker' => $addw_worker,
    'sum' => $addw_sum,
    'cash_card' => 'Наличный расчет',
    'note' => 'Расход на дополнительную работу ' . $addw_description . ' к изделию ' . $product_page->title . '. ID изделия: ' . $product_page->id . '',
    ]);
    $cash_operation_page = $pages->get('title=' . date("Y-m-d H:i") . ' Расход - ' . $addw_sum . ' - ' . $selected_point . '');
    $cash_operation_id = $cash_operation_page->id;

    //Записываем операцию расхода в кассу в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Расход - ' . $addw_sum . ' - ' . $selected_point . ' === ';
    $log .= 'Операция проведена: ' . $addw_worker . ', ID записи: ' . $cash_operation_id . ', Сумма: ' . $addw_sum . ', Вид платежа: Наличный расчет, Описание: Расход на дополнительную работу ' . $addw_description . ' к изделию ' . $product_page->title . '. ID изделия: ' . $product_page->id;
    file_put_contents(__DIR__ . '/log_cash.txt', $log . PHP_EOL, FILE_APPEND);

    //Изменяем остатки в кассе
    $edit_page = $pages->get('template=cash_itm, id_point=' . $selected_id_point . '_cash');
    $result = $edit_page->sum - $addw_sum;
    // echo $result;
    $edit_page->of(false);
    $edit_page->sum = $result;
    $edit_page->save();

    //Записываем регистрацию  в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Внесены изменения в продукт - ' . $product_page->title . '; ';
    $log .= 'Запись изменена: ' . $addw_worker . ', ID записи: ' . $product_page->id . '; '; 
    $log .= 'К изделию добавлена работа: ' . $addw_description . '; '; 
    $log .= 'Сумма добавленной работы: ' . $addw_sum . '; '; 
    file_put_contents(__DIR__ . '/log_addw_changes.txt', $log . PHP_EOL, FILE_APPEND);

    //Предотвращаем повторную регистрацию
    $_SESSION['reload'] = 'on';
} else {
	$success = 'Работа не добавленна!<br>Ошибка в данных';
    if ($_SESSION['reload'] == 'on') {
        $success = 'Повторная отправка данных!';
    }
}

if ($operator == 'no_operator' || $selected_point == 'no_point' || $page_access == false) {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Добавление работы к изделию</h1>
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Добавление работы к изделию</h1>
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