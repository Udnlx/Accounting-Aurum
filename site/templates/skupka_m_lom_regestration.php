<?php namespace ProcessWire;

error_reporting(E_ERROR | E_PARSE);

$date = !empty($_POST['selected_date'])?$_POST['selected_date']:NULL;  
$point = !empty($_POST['selected_point'])?$_POST['selected_point']:NULL;  
$idpoint = !empty($_POST['selected_idpoint'])?$_POST['selected_idpoint']:NULL;  
$worker = !empty($_POST['selected_worker'])?$_POST['selected_worker']:NULL;  
$cart = !empty($_POST['selected_cart'])?$_POST['selected_cart']:NULL;  
$multi_price = !empty($_POST['multi_price'])?$_POST['multi_price']:NULL; 
$cash_card = !empty($_POST['cash_card'])?$_POST['cash_card']:NULL;  
$multisum_nal = !empty($_POST['multisum_nal'])?$_POST['multisum_nal']:NULL;  
$multisum_beznal = !empty($_POST['multisum_beznal'])?$_POST['multisum_beznal']:NULL;  
$description_operation = !empty($_POST['description_operation'])?$_POST['description_operation']:NULL;  

$paytype = !empty($_POST['selected_paytype'])?$_POST['selected_paytype']:NULL;  
$client_name = !empty($_POST['client_name'])?$_POST['client_name']:NULL;  
$client_passport = !empty($_POST['client_passport'])?$_POST['client_passport']:NULL;  
$client_address = !empty($_POST['client_address'])?$_POST['client_address']:NULL;

$success = 'Регистрация мульти скупки прошла успешно';
if ($cart && $_SESSION['reload'] != 'on') {
    //Регестрируем мульти скупку
    $pages->add('operation_itm', 1181 , [
    'title' => date("Y-m-d H:i") . ' Мульти скупка - Лом - ' . $point,
    'type_operation' => 'Мульти скупка',
    'undertype_operation' => 'Лом',
    'date' => $date,
    'point' => $point,
    'id_point' => $idpoint,
    'worker' => $worker,
    'proba' => '',
    'weight' => '',
    'price_gramm' => '',
    'price' => '',
    'pay' => $multi_price,
    'cash_card' => $cash_card,
    'multisum_nal' => $multisum_nal,
    'multisum_beznal' => $multisum_beznal,
    'description_operation' => $description_operation,
    'paytype' => $paytype,
    'client_name' => $client_name,
    'client_passport' => $client_passport,
    'client_address' => $client_address,
    ]);
    $operation_page = $pages->get('title=' . date("Y-m-d H:i") . ' Мульти скупка - Лом - ' . $point . '');
    $operation_id = $operation_page->id;

    //Записываем регистрацию  в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Мульти Скупка - Лом - ' . $point . ' === ';
    $log .= 'Запись занесена: ' . $worker . ', ID записи: ' . $operation_id; 
    file_put_contents(__DIR__ . '/log_operations.txt', $log . PHP_EOL, FILE_APPEND);

    //Регестрируем операцию расхода в кассу
    $page_cash = $pages->get('template=cash_itm, id_point=' . $idpoint . '_cash');
    $pages->add('cash_operation', $page_cash , [
    'title' => date("Y-m-d H:i") . ' Расход - ' . $multi_price . ' - ' . $point,
    'type_operation' => 'Расход',
    'date' => $date,
    'point' => $point,
    'id_point' => $idpoint,
    'worker' => $worker,
    'sum' => $multi_price,
    'cash_card' => $cash_card,
    'note' => 'Расход при мульти скупке лома по операции ID: ' . $operation_id . '',
    ]);
    $cash_operation_page = $pages->get('title=' . date("Y-m-d H:i") . ' Расход - ' . $pay . ' - ' . $point . '');
    $cash_operation_id = $cash_operation_page->id;

    //Записываем операцию расхода в кассу в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Расход - ' . $multi_price . ' - ' . $point . ' === ';
    $log .= 'Операция проведена: ' . $worker . ', ID записи: ' . $cash_operation_id . ', Сумма: ' . $multi_price . ', Вид платежа: ' . $cash_card . ', Описание: Расход при мульти скупке лома по операции ID: ' . $operation_id;
    file_put_contents(__DIR__ . '/log_cash.txt', $log . PHP_EOL, FILE_APPEND);

    //Изменяем остатки в кассе
    $edit_page = $pages->get('template=cash_itm, id_point=' . $idpoint . '_cash');
    if ($cash_card == 'Наличный расчет') {
        $result = $edit_page->sum - $multi_price;
        // echo $result;
        $edit_page->of(false);
        $edit_page->sum = $result;
        $edit_page->save();
    }
    if ($cash_card == 'Безналичный расчет') {
        $result = $edit_page->bn_sum - $multi_price;
        // echo $result;
        $edit_page->of(false);
        $edit_page->bn_sum = $result;
        $edit_page->save();
    }
    if ($cash_card == 'Смешанный расчет') {
        $result = $edit_page->sum - $multisum_nal;
        // echo $result;
        $edit_page->of(false);
        $edit_page->sum = $result;
        $edit_page->save();

        $result = $edit_page->bn_sum - $multisum_beznal;
        // echo $result;
        $edit_page->of(false);
        $edit_page->bn_sum = $result;
        $edit_page->save();
    }

    //Парсим массив
    $cart_array = explode("===", $cart);
    unset($cart_array[0]);
    foreach ($cart_array as $cart_item) {
        $cart_item_array = explode(" — ", $cart_item);
        $proba = $cart_item_array[0];  
        $weight = $cart_item_array[2];  
        $price_gramm = $cart_item_array[1];
        $price = $cart_item_array[3];
        $pay = rtrim($cart_item_array[4], "❌");

        // echo $proba . '<br>';
        // echo $weight . '<br>';
        // echo $price_gramm . '<br>';
        // echo $price . '<br>';
        // echo $pay . '<br>';

        //Регестрируем запись
        $pages->add('operation_itm', $operation_id , [
        'title' => date("Y-m-d H:i") . ' Скупка - Лом - ' . $proba . ' - ' . $weight . 'г - ' . $point,
        'type_operation' => 'Скупка',
        'undertype_operation' => 'Лом',
        'date' => $date,
        'point' => $point,
        'id_point' => $idpoint,
        'worker' => $worker,
        'proba' => $proba,
        'weight' => $weight,
        'price_gramm' => $price_gramm,
        'price' => $price,
        'pay' => $pay,
        'cash_card' => $cash_card,
        'description_operation' => 'Часть мульти скупки - '. $operation_id,
        'paytype' => $paytype,
        'client_name' => $client_name,
        'client_passport' => $client_passport,
        'client_address' => $client_address,
        ]);

        //Изменяем остатки
        $point_actual_table = $pages->get('id_point=' . $idpoint . '_actual');
        $edit_page = $point_actual_table->get('title=' . $proba . '');
        // echo $edit_page . '<br>';
        // echo $edit_page->remain . '<br>';
        // echo $weight . '<br>';
        $result = $edit_page->remain + $weight;
        // echo $result;
        $edit_page->of(false);
        $edit_page->remain = $result;
        $edit_page->save();
    }

    //Предотвращаем повторную регистрацию
    $_SESSION['reload'] = 'on';

    //Функционал распечатки квитанции
    $info_paytype = '';
    if ($paytype == 'Да') {
        $info_paytype = '
        <p class="uk-margin-remove">ФИО клиента: <span style="font-weight: 700;">' . $client_name . '</span></p>
        <p class="uk-margin-remove">Паспорт клиента: <span style="font-weight: 700;">' . $client_passport . '</span></p>
        <p class="uk-margin-remove">Адрес клиента: <span style="font-weight: 700;">' . $client_address . '</span></p>
        <form class="uk-flex uk-flex-column" id="print_receipt" action="/raspechatka-multi-kvitantcii/" method="post">
            <div class="uk-margin-small-top uk-hidden">
                <input class="uk-input" id="print_type" type="text" name="print_type" value="Скупка">
            </div>
            <div class="uk-margin-small-top uk-hidden">
                <input class="uk-input" id="print_undertype" type="text" name="print_undertype" value="Лом">
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
                <input class="uk-input" id="print_cart" type="text" name="print_cart" value="' . $cart . '">
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

include 'skupka_access.php';

if ($operator == 'no_operator' || $selected_point == 'no_point' || $page_access == false) {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Скупка лома - Регистрация мульти скупки</h1>
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Скупка лома - Регистрация мульти скупки</h1>
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