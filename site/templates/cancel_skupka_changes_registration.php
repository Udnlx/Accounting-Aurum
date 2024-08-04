<?php namespace ProcessWire;
 
$id_operation_changes = !empty($_POST['id_operation_changes'])?$_POST['id_operation_changes']:NULL;  
$proba_changes = !empty($_POST['proba_changes'])?$_POST['proba_changes']:NULL;
$weight_changes = !empty($_POST['weight_changes'])?$_POST['weight_changes']:NULL;
$reason_cancel = !empty($_POST['reason_cancel'])?$_POST['reason_cancel']:NULL;

$date = !empty($_POST['selected_date'])?$_POST['selected_date']:NULL;
$point = !empty($_POST['selected_point'])?$_POST['selected_point']:NULL;
$idpoint = !empty($_POST['selected_idpoint'])?$_POST['selected_idpoint']:NULL;
$worker = !empty($_POST['selected_worker'])?$_POST['selected_worker']:NULL;  
$proba = !empty($_POST['selected_proba'])?$_POST['selected_proba']:NULL;  
$weight = !empty($_POST['selected_weight'])?$_POST['selected_weight']:NULL;  
$price_gramm = !empty($_POST['price_gramm'])?$_POST['price_gramm']:NULL;  
$price = !empty($_POST['selected_price'])?$_POST['selected_price']:NULL;  
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
$operation_page = $pages->get('id=' . $id_operation_changes . '');

$success = 'Изменения прошли успешно';
if ($worker && $proba && $weight && $price_gramm && $price && $pay && $cash_card && $_SESSION['reload'] != 'on') {
	//Изменяем отмененную запись
    $edit_page = $pages->get('template=operation_itm, id=' . $operation_page->id . '');
    $edit_page->of(false);
    $edit_page->product_status = 'Отменена';
    $edit_page->reason_cancel = $reason_cancel;
    $edit_page->new_operation = 'Новая скупка при отмене: ';
    $edit_page->save();

    //Создание записи о вычете материала
    $pages->add('operation_itm', 1181 , [
    'title' => date("Y-m-d H:i") . ' Правка скупки - Лом - ' . $operation_page->proba . ' - ' . $operation_page->weight . 'г - ' . $point,
    'type_operation' => 'Правка скупки',
    'undertype_operation' => 'Лом',
    'date' => $date,
    'point' => $point,
    'id_point' => $idpoint,
    'worker' => $worker,
    'proba' => $operation_page->proba,
    'weight' => $operation_page->weight,
    'note' => 'Правка скупки в счет отмены операции ' . $operation_page->id . ': ' . $operation_page->title,
    ]);
    $created_page = $pages->get('title=' . date("Y-m-d H:i") . ' Правка скупки - Лом - ' . $operation_page->proba . ' - ' . $operation_page->weight . 'г - ' . $point . '');
    $created_page_id = $created_page->id;

    //Записываем Создание записи о вычете материала в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Правка скупки - Лом - ' . $operation_page->proba . ' - ' . $operation_page->weight . 'г - ' . $point . ' === ';
    $log .= 'Запись занесена: ' . $worker . ', ID записи: ' . $created_page_id . ' '; 
    $log .= 'Примечания: Правка скупки в счет отмены операции ' . $operation_page->id . ', ' . $operation_page->title; 
    file_put_contents(__DIR__ . '/log_operations.txt', $log . PHP_EOL, FILE_APPEND);

    //Вычет материала
    $point_actual_table = $pages->get('id_point=' . $idpoint . '_actual');
    $edit_page = $point_actual_table->get('title=' . $operation_page->proba . '');
    // echo $edit_page . '<br>';
    // echo $edit_page->remain . '<br>';
    // echo $weight . '<br>';
    $result = $edit_page->remain - $operation_page->weight;
    // echo $result;
    $edit_page->of(false);
    $edit_page->remain = $result;
    $edit_page->save();

    //Создание новой записи о Скупка в счет изменений
    $pages->add('operation_itm', 1181 , [
    'title' => date("Y-m-d H:i") . ' Скупка в счет изменений - Лом - ' . $proba . ' - ' . $weight . 'г - ' . $point,
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
    'paytype' => $paytype,
    'client_name' => $client_name,
    'client_passport' => $client_passport,
    'client_address' => $client_address,
    'old_operation' => 'Скупка в счет изменений: ' . $operation_page->id,
    ]);
    $new_operation_page = $pages->get('title=' . date("Y-m-d H:i") . ' Скупка в счет изменений - Лом - ' . $proba . ' - ' . $weight . 'г - ' . $point . '');
    $new_operation_id = $new_operation_page->id;

    //Создание новой записи о Скупка в счет изменений в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Скупка в счет изменений - Лом - ' . $proba . ' - ' . $weight . 'г - ' . $point . ' === ';
    $log .= 'Запись занесена: ' . $worker . ', ID записи: ' . $new_operation_id . ' '; 
    $log .= 'Запись в счет изменений: ' . $operation_page->id . ', ' . $operation_page->title; 
    file_put_contents(__DIR__ . '/log_operations.txt', $log . PHP_EOL, FILE_APPEND);

    //Прибавка материала
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

    //Перезапись поля ID созданной операции в замен у отменённой операции
    $edit_page = $pages->get('template=operation_itm, id=' . $operation_page->id . '');
    $edit_page->of(false);
    $edit_page->new_operation = 'Новая скупка при отмене: ' . $new_operation_id;
    $edit_page->save();

    //Записываем регистрацию  в лог
    $log = '';
    $log .= date("Y-m-d H:i") . '; ';
    $log .= 'Оператор изменений: ' . $worker . '; '; 
    $log .= 'Отменена операция: ' . $operation_page->id . ' = ' . $operation_page->title . '; '; 
    $log .= 'В счет отмены создана новая запись: ' . $new_operation_page->id . ' = ' . $new_operation_page->title . '; '; 
    file_put_contents(__DIR__ . '/log_operations_changes.txt', $log . PHP_EOL, FILE_APPEND);

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
	        <p class="uk-margin-remove">ID записи отменненой операции: <span style="font-weight: 700;"><?php echo $operation_page->id; ?></span></p>
            <p class="uk-margin-remove">Проба: <span style="font-weight: 700;"><?php echo $operation_page->proba; ?></span></p>
            <p class="uk-margin-remove">Вес: <span style="font-weight: 700;"><?php echo $operation_page->weight; ?></span></p>
            <p class="uk-margin-remove">Причина отмены: <span style="font-weight: 700;"><?php echo $operation_page->reason_cancel; ?></span></p>
            <p class="uk-margin-remove">Новая скупка при отмене: <span style="font-weight: 700;"><?php echo $new_operation_page->id; ?></span></p>
            <br>
            <h3 class="uk-card-title">Данные новой, сформированной операции</h3>
            <p class="uk-margin-remove">ID записи новой операции: <span style="font-weight: 700;"><?php echo $new_operation_page->id; ?></span></p>
            <p class="uk-margin-remove">Дата: <span style="font-weight: 700;"><?php echo $date; ?></span></p>
            <p class="uk-margin-remove">Точка: <span style="font-weight: 700;"><?php echo $point; ?></span></p>
            <p class="uk-margin-remove">ID точки: <span style="font-weight: 700;"><?php echo $idpoint; ?></span></p>
            <p class="uk-margin-remove">Сотрудник: <span style="font-weight: 700;"><?php echo $worker; ?></span></p>
            <p class="uk-margin-remove">Скупка произведена в счет изменений операции: <span style="font-weight: 700;"><?php echo $operation_page->id; ?></span></p>
            <br>
            <p class="uk-margin-remove">Проба: <span style="font-weight: 700;"><?php echo $proba; ?></span></p>
            <p class="uk-margin-remove">Вес: <span style="font-weight: 700;"><?php echo $weight; ?></span></p>
            <p class="uk-margin-remove">Цена за грамм: <span style="font-weight: 700;"><?php echo $price_gramm; ?></span></p>
            <p class="uk-margin-remove">Итоговая стоимость: <span style="font-weight: 700;"><?php echo $price; ?></span></p>
            <p class="uk-margin-remove">Сколько отдали: <span style="font-weight: 700;"><?php echo $pay; ?></span></p>
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