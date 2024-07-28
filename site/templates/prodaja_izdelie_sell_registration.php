<?php namespace ProcessWire;

$worker = !empty($_POST['worker'])?$_POST['worker']:NULL;  
$id_product_sell = !empty($_POST['id_product_sell'])?$_POST['id_product_sell']:NULL;  

$pay = !empty($_POST['selected_pay'])?$_POST['selected_pay']:NULL;  
$cash_card = !empty($_POST['cash_card'])?$_POST['cash_card']:NULL;  

$paytype = !empty($_POST['selected_paytype'])?$_POST['selected_paytype']:NULL;  
$client_name = !empty($_POST['client_name'])?$_POST['client_name']:NULL;  
$client_passport = !empty($_POST['client_passport'])?$_POST['client_passport']:NULL;  
$client_address = !empty($_POST['client_address'])?$_POST['client_address']:NULL;  

$info_paytype = '';
if ($paytype == 'Да') {
    $info_paytype = '
    <p class="uk-margin-remove">ФИО клиента: <span style="font-weight: 700;">' . $client_name . '</span></p>
    <p class="uk-margin-remove">Паспорт клиента: <span style="font-weight: 700;">' . $client_passport . '</span></p>
    <p class="uk-margin-remove">Адрес клиента: <span style="font-weight: 700;">' . $client_address . '</span></p>
    <div class="pagemenu uk-width-1-1 uk-flex">
        <a class="menu-link" href="">Распечатать квитанцию</a>
    </div>
    ';
}

//Получение страницы продукта
$product_page = $pages->get('id=' . $id_product_sell . '');

$success = 'Регистрация продажи прошла успешно';
if ($worker && $id_product_sell && $pay && $cash_card && $_SESSION['reload'] != 'on') {
	//Изменяем запись
    // $edit_page = $pages->get('template=product_itm, id=' . $product_page->id . '');
    // $edit_page->of(false);
    // $edit_page->product_description = $new_product_description;
    // $edit_page->url_avito = $new_product_url_avito;
    // $edit_page->save();

    //Записываем регистрацию  в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Продано изделие - ' . $product_page->title . '; ';
    $log .= 'Сотрудник продажи: ' . $worker . ', ID записи: ' . $product_page->id . '; '; 
    $log .= 'Цена изделия: ' . $product_page->product_price_buy . '; '; 
    $log .= 'Цена продажи: ' . $pay . '; '; 
    file_put_contents(__DIR__ . '/log_products_sell.txt', $log . PHP_EOL, FILE_APPEND);

    //Предотвращаем повторную регистрацию
    $_SESSION['reload'] = 'on';
} else {
	$success = 'Регистрация не прошла!<br>Ошибка в данных';
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
    	<h1 class="uk-heading-hero uk-text-center">Продажа изделия - Регистрация продажи</h1>
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Продажа изделия - Регистрация продажи</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
            </div>
        </div>

        <div>
            <h3 class="uk-card-title"><?php echo $product_page->title; ?></h3>
            <h3 class="uk-card-title"><?php echo $success; ?></h3>
            <p class="uk-margin-remove">Дата: <span style="font-weight: 700;"><?php echo $today; ?></span></p>
            <p class="uk-margin-remove">Сотрудник продажи: <span style="font-weight: 700;"><?php echo $worker; ?></span></p>
            <br>
            <p class="uk-margin-remove">Вес: <span style="font-weight: 700;"><?php echo $product_page->weight; ?></span></p>
            <p class="uk-margin-remove">Цена скупки изделия: <span style="font-weight: 700;"><?php echo $product_page->product_price_buy; ?></span></p>
            <p class="uk-margin-remove">Цена продажи изделия: <span style="font-weight: 700;"><?php echo $pay; ?></span></p>
            <p class="uk-margin-remove">Вид платежа: <span style="font-weight: 700;"><?php echo $cash_card; ?></span></p>
            <br>
            <p class="uk-margin-remove">Квитанция: <span style="font-weight: 700;"><?php echo $paytype; ?></span></p>
            <?php echo $info_paytype; ?>
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