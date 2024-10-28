<?php namespace ProcessWire;

$date = date("Y-m-d");  
if(isset($_SESSION['point'])){
    $point = $_SESSION['point'];
} else {
    $point = 'no_point';
}
if(isset($_SESSION['id_point'])){
    $idpoint = $_SESSION['id_point'];
} else {
    $idpoint = 'no_id_point';
}

$worker = !empty($_POST['worker'])?$_POST['worker']:NULL;  
$id_product_sell = !empty($_POST['id_product_sell'])?$_POST['id_product_sell']:NULL;  

$pay = !empty($_POST['selected_pay'])?$_POST['selected_pay']:NULL;  
$cash_card = !empty($_POST['cash_card'])?$_POST['cash_card']:NULL;  

$paytype = !empty($_POST['selected_paytype'])?$_POST['selected_paytype']:NULL;  
$client_name = !empty($_POST['client_name'])?$_POST['client_name']:NULL;  
$client_passport = !empty($_POST['client_passport'])?$_POST['client_passport']:NULL;  
$client_address = !empty($_POST['client_address'])?$_POST['client_address']:NULL;  

//Получение страницы продукта
$product_page = $pages->get('id=' . $id_product_sell . '');

$success = 'Регистрация продажи прошла успешно';
if ($worker && $id_product_sell && $pay && $cash_card && $_SESSION['reload'] != 'on') {
	//Изменяем запись
    $edit_page = $pages->get('template=product_itm, id=' . $product_page->id . '');
    $edit_page->of(false);
    $edit_page->worker_sell = $worker;
    $edit_page->product_date_sell = date("d-m-Y");
    $edit_page->product_status = 'продано';
    $edit_page->product_price_sell = $pay;
    $edit_page->cash_card_product_sell = $cash_card;
    $edit_page->paytype_product_sell = $paytype;
    $edit_page->client_name_product_sell = $client_name;
    $edit_page->client_passport_product_sell = $client_passport;
    $edit_page->client_address_product_sell = $client_address;
    $edit_page->save();

    //Записываем регистрацию  в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Продано изделие - ' . $product_page->title . '; ';
    $log .= 'Сотрудник продажи: ' . $worker . ', ID записи: ' . $product_page->id . '; '; 
    $log .= 'Цена изделия: ' . $product_page->product_price_buy . '; '; 
    $log .= 'Цена продажи: ' . $pay . '; '; 
    file_put_contents(__DIR__ . '/log_products_sell.txt', $log . PHP_EOL, FILE_APPEND);

                //Регестрируем операцию прихода в кассу
                $page_cash = $pages->get('template=cash_itm, id_point=' . $idpoint . '_cash');
                $pages->add('cash_operation', $page_cash , [
                'title' => date("Y-m-d H:i") . ' Приход - ' . $pay . ' - ' . $point,
                'type_operation' => 'Приход',
                'date' => $date,
                'point' => $point,
                'id_point' => $idpoint,
                'worker' => $worker,
                'sum' => $pay,
                'note' => 'Приход при продаже изделия по операции ID: ' . $product_page->id . '',
                ]);
                $cash_operation_page = $pages->get('title=' . date("Y-m-d H:i") . ' Приход - ' . $pay . ' - ' . $point . '');
                $cash_operation_id = $cash_operation_page->id;

                //Записываем операцию прихода в кассу в лог
                $log = '';
                $log .= date("Y-m-d H:i") . ' Приход - ' . $pay . ' - ' . $point . ' === ';
                $log .= 'Операция проведена: ' . $worker . ', ID записи: ' . $cash_operation_id . ', Сумма: ' . $pay . ', Описание: Приход при продаже изделия по операции ID: ' . $product_page->id;
                file_put_contents(__DIR__ . '/log_cash.txt', $log . PHP_EOL, FILE_APPEND);

                //Изменяем остатки в кассе
                $edit_page = $pages->get('template=cash_itm, id_point=' . $idpoint . '_cash');
                $result = $edit_page->sum + $pay;
                // echo $result;
                $edit_page->of(false);
                $edit_page->sum = $result;
                $edit_page->save();

    //Предотвращаем повторную регистрацию
    $_SESSION['reload'] = 'on';

    //Функционал распечатки квитанции
    $info_paytype = '';
    if ($paytype == 'Да') {
        $info_paytype = '
        <p class="uk-margin-remove">ФИО клиента: <span style="font-weight: 700;">' . $client_name . '</span></p>
        <p class="uk-margin-remove">Паспорт клиента: <span style="font-weight: 700;">' . $client_passport . '</span></p>
        <p class="uk-margin-remove">Адрес клиента: <span style="font-weight: 700;">' . $client_address . '</span></p>
        <form class="uk-flex uk-flex-column" id="print_receipt" action="/raspechatka-kvitantcii/" method="post">
            <div class="uk-margin-small-top uk-hidden">
                <input class="uk-input" id="operation_id" type="text" name="operation_id" value="' . $product_page->id . '">
            </div>
            <div class="uk-margin-small-top uk-hidden">
                <input class="uk-input" id="print_type" type="text" name="print_type" value="Продажа">
            </div>
            <div class="uk-margin-small-top uk-hidden">
                <input class="uk-input" id="print_undertype" type="text" name="print_undertype" value="Изделие - ' . $product_page->product_name . ' - ' . $product_page->product_description . '">
            </div>
            <div class="uk-margin-small-top uk-hidden">
                <input class="uk-input" id="print_date" type="text" name="print_date" value="' . date("d-m-Y") . '">
            </div>
            <div class="uk-margin-small-top uk-hidden">
                <input class="uk-input" id="print_point" type="text" name="print_point" value="' . $product_page->point . '">
            </div>
            <div class="uk-margin-small-top uk-hidden">
                <input class="uk-input" id="print_client_name" type="text" name="print_client_name" value="' . $client_name . '">
            </div>
            <div class="uk-margin-small-top uk-hidden">
                <input class="uk-input" id="print_client_passport" type="text" name="print_client_passport" value="' . $client_passport . '">
            </div>
            <div class="uk-margin-small-top uk-hidden">
                <input class="uk-input" id="print_client_address" type="text" name="print_client_address" value="' . $client_address . '">
            </div>
            <div class="uk-margin-small-top uk-hidden">
                <input class="uk-input" id="print_pay" type="text" name="print_pay" value="' . $pay . '">
            </div>
            <div class="uk-margin-small-top uk-hidden">
                <input class="uk-input" id="print_proba" type="text" name="print_proba" value="">
            </div>
            <div class="uk-margin-small-top uk-hidden">
                <input class="uk-input" id="print_weight" type="text" name="print_weight" value="' . $product_page->weight . '">
            </div>
            <div class="uk-margin-small-top uk-hidden">
                <input class="uk-input" id="print_worker" type="text" name="print_worker" value="' . $worker . '">
            </div>
            
            <div class="pagemenu uk-width-1-1 uk-flex">
                <button class="menu-link" type="submit">Распечатать квитанцию</button>
            </div>
        </form>
        ';
    }
} else {
	$success = 'Регистрация не прошла!<br>Ошибка в данных';
    if ($_SESSION['reload'] == 'on') {
        $success = 'Повторная отправка данных!';
    }
    $info_paytype = '';
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