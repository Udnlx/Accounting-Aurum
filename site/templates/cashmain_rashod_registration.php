<?php namespace ProcessWire;

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

$date = !empty($_POST['selected_date'])?$_POST['selected_date']:NULL;  
$point = !empty($_POST['selected_point'])?$_POST['selected_point']:NULL;  
$idpoint = !empty($_POST['selected_idpoint'])?$_POST['selected_idpoint']:NULL;  
$worker = !empty($_POST['selected_worker'])?$_POST['selected_worker']:NULL;  
$sum = !empty($_POST['selected_sum'])?$_POST['selected_sum']:NULL;  
$cash_card = !empty($_POST['cash_card'])?$_POST['cash_card']:NULL; 
$description = !empty($_POST['cash_description'])?$_POST['cash_description']:NULL;  

$success = 'Операция расхода по общей кассе проведена';
if ($worker && $sum && $description && $_SESSION['reload'] != 'on') {
	//Регестрируем запись
    $page_cash = $pages->get('template=cash_itm, id_point=all_cash');
    $pages->add('cash_operation', $page_cash , [
    'title' => date("Y-m-d H:i") . ' Расход - ' . $sum . ' - ' . $point,
    'type_operation' => 'Расход',
    'date' => $date,
    'point' => $point,
    'id_point' => $idpoint,
    'worker' => $worker,
    'sum' => $sum,
    'cash_card' => $cash_card,
    'note' => $description,
    ]);
    $operation_page = $pages->get('title=' . date("Y-m-d H:i") . ' Расход - ' . $sum . ' - ' . $point . '');
    $operation_id = $operation_page->id;

    //Записываем добавление в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Расход - ' . $sum . ' - ' . $point . ' === ';
    $log .= 'Операция проведена: ' . $worker . ', ID записи: ' . $operation_id . ', Сумма: ' . $sum . ', Вид платежа: ' . $cash_card . ', Описание: ' . $description;
    file_put_contents(__DIR__ . '/log_cashmain.txt', $log . PHP_EOL, FILE_APPEND);

    //Изменяем остатки
    $edit_page = $pages->get('template=cash_itm, id_point=all_cash');
    if ($cash_card == 'Наличный расчет') {
        $result = $edit_page->sum - $sum;
        // echo $result;
        $edit_page->of(false);
        $edit_page->sum = $result;
        $edit_page->save();
    }
    if ($cash_card == 'Безналичный расчет') {
        $result = $edit_page->bn_sum - $sum;
        // echo $result;
        $edit_page->of(false);
        $edit_page->bn_sum = $result;
        $edit_page->save();
    }

    //Предотвращаем повторную регистрацию
    $_SESSION['reload'] = 'on';
} else {
	$success = 'Операция расхода по общей кассе не проведена!<br>Ошибка в данных';
    if ($_SESSION['reload'] == 'on') {
        $success = 'Повторная отправка данных!<br>Запись уже существует, операция повторно по общей кассе не проведена';
    }
}

if ($operator == 'no_operator' || $selected_point == 'no_point' || $access != 'admin') {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Операция расхода по общей кассе - Регистрация</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title">Потеряна сессия или точка, <br>возможно нет прав на эту страницу.</h3>
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Операция расхода по общей кассе - Регистрация</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/obshchaia-kassa-tip-operatcii/">Касса</a>
            </div>
        </div>

        <div>
            <h3 class="uk-card-title"><?php echo $success; ?></h3>
	        <p class="uk-margin-remove">Дата: <span style="font-weight: 700;"><?php echo $date; ?></span></p>
	        <p class="uk-margin-remove">Точка: <span style="font-weight: 700;"><?php echo $point; ?></span></p>
	        <p class="uk-margin-remove">ID точки: <span style="font-weight: 700;"><?php echo $idpoint; ?></span></p>
	        <p class="uk-margin-remove">Сотрудник: <span style="font-weight: 700;"><?php echo $worker; ?></span></p>
	        <br>
            <p class="uk-margin-remove">Тип операции: <span style="font-weight: 700;">Расход</span></p>
            <p class="uk-margin-remove">Тип платежа: <span style="font-weight: 700;"><?php echo $cash_card; ?></span></p>
	        <p class="uk-margin-remove">Сумма: <span style="font-weight: 700;"><?php echo $sum; ?></span></p>
	        <p class="uk-margin-remove">Описание: <span style="font-weight: 700;"><?php echo $description; ?></span></p>
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