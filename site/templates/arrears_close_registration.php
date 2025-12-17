<?php namespace ProcessWire;

$arrear_point = !empty($_POST['arrear_point'])?$_POST['arrear_point']:NULL;  
$arrear_idpoint = !empty($_POST['arrear_idpoint'])?$_POST['arrear_idpoint']:NULL;  
$arrear_worker = !empty($_POST['arrear_worker'])?$_POST['arrear_worker']:NULL;  
$arrear_id = !empty($_POST['arrear_id'])?$_POST['arrear_id']:NULL;  
$selected_operation = !empty($_POST['selected_operation'])?$_POST['selected_operation']:NULL;  
$sum_part_arrear = !empty($_POST['sum_part_arrear'])?$_POST['sum_part_arrear']:NULL;  
$note_part_arrear = !empty($_POST['note_part_arrear'])?$_POST['note_part_arrear']:NULL;  

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

include 'arrears_access.php';

//Получение страницы долга
$arrear_page = $pages->get('id=' . $arrear_id . '');

if ($operator == 'no_operator' || $selected_point == 'no_point' || $page_access == false) {
?>
    <div id="content" style="max-width: 700px;">
        <h1 class="uk-heading-hero uk-text-center">Погашение долга - Регистрация операции</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title uk-text-center">Нет прав на эту страницу, потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {
$success = 'Регистрация погашения долга прошла успешно';
if ($arrear_point && $arrear_idpoint && $arrear_worker && $arrear_id && $selected_operation && $sum_part_arrear && $_SESSION['reload'] != 'on') {
    //Создаем запись частичного платежа
    $pages->add('arrears_payment', $arrear_id , [
    'title' => date("Y-m-d H:i") . ' Погашение долга на ' . $sum_part_arrear,
    'point' => $arrear_point,
    'id_point' => $arrear_idpoint,
    'worker' => $arrear_worker,
    'cash_card' => $selected_operation,
    'sum' => $sum_part_arrear,
    'note' => $note_part_arrear,
    ]);

    //Записываем операцию в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Частичное погашение долга - ' . $arrear_page->client_name . ' на сумму ' . $sum_part_arrear . ' ';
    $log .= 'Платеж проведен: ' . $arrear_worker . ', ID записи: ' . $arrear_id . ', Описание долга: ' . $arrear_page->description_operation; 
    file_put_contents(__DIR__ . '/log_arrears.txt', $log . PHP_EOL, FILE_APPEND);

    //Заносим изменения в кассу
    $edit_page = $pages->get('template=cash_itm, id_point=' . $selected_id_point . '_cash');
    if ($selected_operation == 'Наличный расчет') {
        $result = $edit_page->sum + $sum_part_arrear;
        // echo $result;
        $edit_page->of(false);
        $edit_page->sum = $result;
        $edit_page->save();
    }
    if ($selected_operation == 'Безналичный расчет') {
        $result = $edit_page->bn_sum + $sum_part_arrear;
        // echo $result;
        $edit_page->of(false);
        $edit_page->bn_sum = $result;
        $edit_page->save();
    }

    //Предотвращаем повторную регистрацию
    $_SESSION['reload'] = 'on';
} else {
    $success = 'Регистрация погашения долга не прошла!<br>Ошибка в данных';
    if ($_SESSION['reload'] == 'on') {
        $success = 'Повторная отправка данных!';
    }
}

//Расчитываем сколько осталось
$all_payments = '';
$total = 0;
$remain = 0;
foreach ($arrear_page->children() as $child) {
    $all_payments .= '<p class="uk-margin-remove" style="font-size:14px;">' . $child->title . '</p>';
    $total = $total + $child->sum;
}
$remain = $arrear_page->sum - $total;
$remain_title = '';
if ($remain > 0) {
    $remain_title = '<p class="uk-margin-remove" style="font-size:14px;font-weight:700;color:red;">Осталось оплатить: ' . $remain . '</p>';
} else {
    $remain_title = '<p class="uk-margin-remove" style="font-size:14px;font-weight:700;color:green;">Внимание!!! Долг полностью оплачен, платежи больше не требуются! Закройте долг на странице всех долгов</p>';
}

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
    <h1 class="uk-margin-remove uk-heading-hero uk-text-center">Погашение долга - Регистрация операции</h1>
    <div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/dolgi/">Долги</a>
            </div>
        </div>

        <div>
            <h3 class="uk-card-title"><?php echo $arrear_page->title; ?></h3>
            <p class="uk-margin-remove">
                Описание долга: <span style="font-weight: 700;"><?php echo $arrear_page->description_operation; ?></span>
            </p>
            <h3 class="uk-card-title"><?php echo $success; ?></h3> 
            <p class="uk-margin-remove">Вид операции: <span style="font-weight: 700;"><?php echo $selected_operation; ?></span></p>
            <p class="uk-margin-remove">Сумма погашения: <span style="font-weight: 700;"><?php echo $sum_part_arrear; ?></span></p>
            <p class="uk-margin-remove">Комментарий: <span style="font-weight: 700;"><?php echo $note_part_arrear; ?></span></p>
            <br>
            <p class="uk-margin-remove" style="font-size:14px;font-weight:700;">Осуществленные платежи:</p>
            <?php echo $all_payments; ?>
            <p class="uk-margin-remove" style="font-size:14px;font-weight:700;color:green;">Оплаченно: <?php echo $total; ?></p>
            <?php echo $remain_title; ?>
            <br>
        </div>
        
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