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

$proba = !empty($_POST['selected_proba'])?$_POST['selected_proba']:NULL; 
$weight = !empty($_POST['selected_weight'])?$_POST['selected_weight']:NULL; 
$selected_operation = !empty($_POST['selected_operation'])?$_POST['selected_operation']:NULL; 
$edit_lom_note = !empty($_POST['edit_lom_note'])?$_POST['edit_lom_note']:NULL; 

$success = 'Изменение остатков прошло успешно';
if ($operator != 'no_operator' && $selected_point !== 'no_point' && $proba && $weight && $edit_lom_note && $_SESSION['reload'] != 'on') {
    //Записываем изменения в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Внесены изменения по металлу - ' . $selected_operation . ' - ' . $proba . ' - ' . $weight . 'г - ' . $selected_point . ' === ';
    $log .= 'Комментарий: ' . $edit_lom_note . ' === '; 
    $log .= 'Оператор: ' . $operator; 
    file_put_contents(__DIR__ . '/log_edit_lomcash.txt', $log . PHP_EOL, FILE_APPEND);

    //Изменяем остатки
    $point_actual_table = $pages->get('id_point=' . $selected_id_point . '_actual');
    $edit_page = $point_actual_table->get('title=' . $proba . '');
    if ($selected_operation == 'Приход') {
        $result = $edit_page->remain + $weight;
    }
    if ($selected_operation == 'Расход') {
        $result = $edit_page->remain - $weight;
    }
    $edit_page->of(false);
    $edit_page->remain = $result;
    $edit_page->save();

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
    	<h1 class="uk-heading-hero uk-text-center">Правки по лому - Регистрация изменений</h1>
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Правки по лому - Регистрация изменений</h1>
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
            <p class="uk-margin-remove">Тип операции: <span style="font-weight: 700;">Изменения по лому - <?php echo $selected_operation; ?></span></p>
            <p class="uk-margin-remove">Проба: <span style="font-weight: 700;"><?php echo $proba; ?></span></p>
            <p class="uk-margin-remove">Вес: <span style="font-weight: 700;"><?php echo $weight; ?> г.</span></p>
            <p class="uk-margin-remove">Комментарий: <span style="font-weight: 700;"><?php echo $edit_lom_note; ?></span></p>
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