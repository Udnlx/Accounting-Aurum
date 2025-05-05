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

$cash_card = !empty($_POST['cash_card'])?$_POST['cash_card']:NULL; 
$selected_operation = !empty($_POST['selected_operation'])?$_POST['selected_operation']:NULL; 
$sum = !empty($_POST['selected_sum'])?$_POST['selected_sum']:NULL;  
$edit_cash_note = !empty($_POST['edit_cash_note'])?$_POST['edit_cash_note']:NULL; 

$success = 'Изменение остатков прошло успешно';
if ($operator != 'no_operator' && $selected_point !== 'no_point' && $cash_card && $sum && $edit_cash_note && $_SESSION['reload'] != 'on') {
    //Записываем изменения в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Внесены изменения по кассе - ' . $selected_operation . ' - ' . $cash_card . ' - ' . $sum . 'р - ' . $selected_point . ' === ';
    $log .= 'Комментарий: ' . $edit_cash_note . ' === ';
    $log .= 'Оператор: ' . $operator; 
    file_put_contents(__DIR__ . '/log_edit_lomcash.txt', $log . PHP_EOL, FILE_APPEND);

    //Изменяем остатки
    $edit_page = $pages->get('template=cash_itm, id_point=' . $selected_id_point . '_cash');
    if ($cash_card == 'Наличный расчет') {
        if ($selected_operation == 'Приход') {
            $result = $edit_page->sum + $sum;
        }
        if ($selected_operation == 'Расход') {
            $result = $edit_page->sum - $sum;
        }
        $edit_page->of(false);
        $edit_page->sum = $result;
        $edit_page->save();
    }
    if ($cash_card == 'Безналичный расчет') {
        if ($selected_operation == 'Приход') {
            $result = $edit_page->bn_sum + $sum;
        }
        if ($selected_operation == 'Расход') {
            $result = $edit_page->bn_sum - $sum;
        }
        $edit_page->of(false);
        $edit_page->bn_sum = $result;
        $edit_page->save();
    }

    //Предотвращаем повторную регистрацию
    $_SESSION['reload'] = 'on';
} else {
    $success = 'Изменение остатков не прошли!<br>Ошибка в данных';
    if ($_SESSION['reload'] == 'on') {
        $success = 'Повторная отправка данных!<br>Изменения уже внесены, регистрация изменений повторно не проведена';
    }
}

if ($operator == 'no_operator' || $selected_point == 'no_point' || $access != 'admin') {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Правки по кассе - Регистрация изменений</h1>
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Правки по кассе - Регистрация изменений</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/pravki-po-lomu-i-kassam-formy/">Правки по лому и кассам</a>
            </div>
        </div>

        <div>
            <h3 class="uk-card-title"><?php echo $success; ?></h3>
            <p class="uk-margin-remove">Точка: <span style="font-weight: 700;"><?php echo $selected_point; ?></span></p>
            <p class="uk-margin-remove">ID точки: <span style="font-weight: 700;"><?php echo $selected_id_point; ?></span></p>
            <p class="uk-margin-remove">Сотрудник: <span style="font-weight: 700;"><?php echo $operator; ?></span></p>
            <br>
            <p class="uk-margin-remove">Тип операции: <span style="font-weight: 700;">Изменения по кассе - <?php echo $selected_operation; ?></span></p>
            <p class="uk-margin-remove">Тип платежа: <span style="font-weight: 700;"><?php echo $cash_card; ?></span></p>
            <p class="uk-margin-remove">Сумма: <span style="font-weight: 700;"><?php echo $sum; ?> р.</span></p>
            <p class="uk-margin-remove">Комментарий: <span style="font-weight: 700;"><?php echo $edit_cash_note; ?></span></p>
            <a class="uk-width-1-1 uk-margin-small-top uk-button uk-button-default" href="/pravki-po-lomu-i-kassam-formy/">Добавить еще правки по лому и кассам</a>
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