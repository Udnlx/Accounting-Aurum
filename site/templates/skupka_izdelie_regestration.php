<?php namespace ProcessWire;

$date = !empty($_POST['selected_date'])?$_POST['selected_date']:NULL;  
$point = !empty($_POST['selected_point'])?$_POST['selected_point']:NULL;  
$idpoint = !empty($_POST['selected_idpoint'])?$_POST['selected_idpoint']:NULL;  
$worker = !empty($_POST['selected_worker'])?$_POST['selected_worker']:NULL;  
$product = !empty($_POST['product'])?$_POST['product']:NULL;  
$product_description = !empty($_POST['product_description'])?$_POST['product_description']:NULL;  
$weight = !empty($_POST['selected_weight'])?$_POST['selected_weight']:NULL;  
$pay = !empty($_POST['selected_pay'])?$_POST['selected_pay']:NULL;  
$cash_card = !empty($_POST['cash_card'])?$_POST['cash_card']:NULL;  

$paytype = !empty($_POST['selected_paytype'])?$_POST['selected_paytype']:NULL;  
$client_name = !empty($_POST['client_name'])?$_POST['client_name']:NULL;  
$client_passport = !empty($_POST['client_passport'])?$_POST['client_passport']:NULL;  
$client_address = !empty($_POST['client_address'])?$_POST['client_address']:NULL;  

$success = 'Регистрация скупки прошла успешно';
if ($worker && $product && $weight && $pay && $cash_card && $_SESSION['reload'] != 'on') {
    $all_product_itm = count($pages->find('template=product_itm'));
    $serial_number = $all_product_itm + 1;
	//Регестрируем запись
    $pages->add('product_itm', 1218 , [
    'title' => $product . ' - ' . $weight . 'г - ' . $point . ' - Купленно: ' . date("Y-m-d H:i"),
    'serial_number' => $serial_number,
    'date' => $date,
    'point' => $point,
    'id_point' => $idpoint,
    'worker' => $worker,
    'product_date_buy' => $date,
    'product_status' => 'в наличии',
    'product_price_buy' => $pay,
    'product_name' => $product,
    'product_description' => $product_description,
    'weight' => $weight,
    'cash_card' => $cash_card,
    'paytype' => $paytype,
    'client_name' => $client_name,
    'client_passport' => $client_passport,
    'client_address' => $client_address,
    ]);
    $operation_page = $pages->get('title=' . $product . ' - ' . $weight . 'г - ' . $point . ' - Купленно: ' . date("Y-m-d H:i") . '');
    $operation_id = $operation_page->id;

    //Записываем регистрацию  в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Скупка - Изделие - ' . $product . ' - ' . $weight . 'г - ' . $point . ' === ';
    $log .= 'Запись занесена: ' . $worker . ', ID записи: ' . $operation_id . ', Порядковый номер: ' . $serial_number; 
    file_put_contents(__DIR__ . '/log_products.txt', $log . PHP_EOL, FILE_APPEND);

                //Регестрируем операцию расхода в кассу
                $page_cash = $pages->get('template=cash_itm, id_point=' . $idpoint . '_cash');
                $pages->add('cash_operation', $page_cash , [
                'title' => date("Y-m-d H:i") . ' Расход - ' . $pay . ' - ' . $point,
                'type_operation' => 'Расход',
                'date' => $date,
                'point' => $point,
                'id_point' => $idpoint,
                'worker' => $worker,
                'sum' => $pay,
                'note' => 'Расход при скупке изделия по операции ID: ' . $operation_id . '',
                ]);
                $cash_operation_page = $pages->get('title=' . date("Y-m-d H:i") . ' Расход - ' . $pay . ' - ' . $point . '');
                $cash_operation_id = $cash_operation_page->id;

                //Записываем операцию расхода в кассу в лог
                $log = '';
                $log .= date("Y-m-d H:i") . ' Расход - ' . $pay . ' - ' . $point . ' === ';
                $log .= 'Операция проведена: ' . $worker . ', ID записи: ' . $cash_operation_id . ', Сумма: ' . $pay . ', Описание: Расход при скупке изделия по операции ID: ' . $operation_id;
                file_put_contents(__DIR__ . '/log_cash.txt', $log . PHP_EOL, FILE_APPEND);

                //Изменяем остатки в кассе
                $edit_page = $pages->get('template=cash_itm, id_point=' . $idpoint . '_cash');
                $result = $edit_page->sum - $pay;
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
                <input class="uk-input" id="operation_id" type="text" name="operation_id" value="' . $operation_id . '">
            </div>
            <div class="uk-margin-small-top uk-hidden">
                <input class="uk-input" id="print_type" type="text" name="print_type" value="Скупка">
            </div>
            <div class="uk-margin-small-top uk-hidden">
                <input class="uk-input" id="print_undertype" type="text" name="print_undertype" value="Изделие - ' . $product . ' - ' . $product_description . '">
            </div>
            <div class="uk-margin-small-top uk-hidden">
                <input class="uk-input" id="print_date" type="text" name="print_date" value="' . $date . '">
            </div>
            <div class="uk-margin-small-top uk-hidden">
                <input class="uk-input" id="print_point" type="text" name="print_point" value="' . $point . '">
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
                <input class="uk-input" id="print_weight" type="text" name="print_weight" value="' . $weight . '">
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
        $success = 'Повторная отправка данных!<br>Запись уже существует, регистрация записи повторно не проведена';
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
    	<h1 class="uk-heading-hero uk-text-center">Скупка изделия - Регистрация скупки</h1>
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Скупка изделия - Регистрация скупки</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
            </div>
        </div>

        <div>
            <h3 class="uk-card-title"><?php echo $success; ?></h3>
	        <p class="uk-margin-remove">Дата: <span style="font-weight: 700;"><?php echo $date; ?></span></p>
	        <p class="uk-margin-remove">Точка: <span style="font-weight: 700;"><?php echo $point; ?></span></p>
	        <p class="uk-margin-remove">ID точки: <span style="font-weight: 700;"><?php echo $idpoint; ?></span></p>
	        <p class="uk-margin-remove">Сотрудник: <span style="font-weight: 700;"><?php echo $worker; ?></span></p>
	        <br>
	        <p class="uk-margin-remove">Изделие: <span style="font-weight: 700;"><?php echo $product; ?></span></p>
            <p class="uk-margin-remove">Описание: <span style="font-weight: 700;"><?php echo $product_description; ?></span></p>
	        <p class="uk-margin-remove">Вес: <span style="font-weight: 700;"><?php echo $weight; ?></span></p>
	        <p class="uk-margin-remove">Цена скупки изделия: <span style="font-weight: 700;"><?php echo $pay; ?></span></p>
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