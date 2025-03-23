<?php namespace ProcessWire;

$date = !empty($_POST['selected_date'])?$_POST['selected_date']:NULL;  
$point = !empty($_POST['selected_point'])?$_POST['selected_point']:NULL;  
$idpoint = !empty($_POST['selected_idpoint'])?$_POST['selected_idpoint']:NULL;  
$worker = !empty($_POST['selected_worker'])?$_POST['selected_worker']:NULL;  
$proba = !empty($_POST['selected_proba'])?$_POST['selected_proba']:NULL;  
$weight = !empty($_POST['selected_weight'])?$_POST['selected_weight']:NULL;  
$description_operation = !empty($_POST['description_operation'])?$_POST['description_operation']:NULL;  
$reserv_note = !empty($_POST['reserv_note'])?$_POST['reserv_note']:NULL;  

$success = 'Добавление резерва прошло успешно';
if ($worker && $proba && $weight  && $reserv_note && $_SESSION['reload'] != 'on') {
	//Регестрируем запись
    $pages->add('reserv_itm', 1300 , [
    'title' => date("Y-m-d H:i") . ' Резерв - ' . $proba . ' - ' . $weight . 'г - ' . $point,
    'type_operation' => 'Резерв',
    'date' => $date,
    'point' => $point,
    'id_point' => $idpoint,
    'worker' => $worker,
    'proba' => $proba,
    'weight' => $weight,
    'description_operation' => $description_operation,
    'reserv_note' => $reserv_note,
    ]);
    $operation_page = $pages->get('title=' . date("Y-m-d H:i") . ' Резерв - ' . $proba . ' - ' . $weight . 'г - ' . $point . '');
    $operation_id = $operation_page->id;

    //Записываем добавление в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Резерв - Добавление - ' . $proba . ' - ' . $weight . 'г - ' . $point . ' === ';
    $log .= 'Добавил резерв: ' . $worker . ', ID резерва: ' . $operation_id . ', Для кого резерв: ' . $description_operation . ', Комментарий резерва: ' . $reserv_note;
    file_put_contents(__DIR__ . '/log_reserv.txt', $log . PHP_EOL, FILE_APPEND);

    //Меняем таблицу резерва
    $point_actual_table = $pages->get('id_point=' . $idpoint . '_reserv');
    $edit_page = $point_actual_table->get('title=' . $proba . '');
    // echo $edit_page . '<br>';
    // echo $edit_page->remain . '<br>';
    // echo $weight . '<br>';
    $result = $edit_page->remain + $weight;
    // echo $result;
    $edit_page->of(false);
    $edit_page->remain = $result;
    $edit_page->save();

    //Предотвращаем повторную регистрацию
    $_SESSION['reload'] = 'on';
} else {
	$success = 'Добавление резерва не прошло!<br>Ошибка в данных';
    if ($_SESSION['reload'] == 'on') {
        $success = 'Повторная отправка данных!<br>Запись уже существует, регистрация записи повторно не проведена';
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
    	<h1 class="uk-heading-hero uk-text-center">Добавление резерва - Регистрация</h1>
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Добавление резерва - Регистрация</h1>
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
	        <p class="uk-margin-remove">Проба: <span style="font-weight: 700;"><?php echo $proba; ?></span></p>
	        <p class="uk-margin-remove">Вес: <span style="font-weight: 700;"><?php echo $weight; ?></span></p>
            <p class="uk-margin-remove">Комментарий: <span style="font-weight: 700;"><?php echo $reserv_note; ?></span></p>
            <p class="uk-margin-remove">Для кого резерв: <span style="font-weight: 700;"><?php echo $description_operation; ?></span></p>
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