<?php namespace ProcessWire;

$point = !empty($_POST['selected_point'])?$_POST['selected_point']:NULL;  
$idpoint = !empty($_POST['selected_idpoint'])?$_POST['selected_idpoint']:NULL;  
$worker = !empty($_POST['selected_worker'])?$_POST['selected_worker']:NULL;  

$newpoint_name = !empty($_POST['newpoint_name'])?$_POST['newpoint_name']:NULL;

//Валидация перед созданием точки
$validation = false;
$error = '';
$value = trim($newpoint_name);
if (!preg_match('/^[А-Яа-яЁё0-9.\- ]+$/u', $value)) {
    $validation = false;
    $error = 'Обноружены недопустимые символы в наименовании точки';
} else {
    $validation = true;
}
$exist_point = $pages->get('template=points_itm, title=' . $newpoint_name);
if ($exist_point->id) {
    //echo $exist_point->id;
    $validation = false;
    $error = 'Точка с таким наименование уже существует в системе.<br>Обратитесь к администратору системы.';
}

$success = 'Регистрация новой точки "' . $newpoint_name . '" прошла успешно';
if ($newpoint_name && $validation == true) {
	//Запускаем скрипт по созданию новой точки
    $all_points = $pages->find('template=points_itm, include=all');
    $num_point = count($all_points);
    $num_point = $num_point + 1;
    $id_point = 'point' . $num_point;
    $parent_point = $pages->get('template=points');
    $pages->add('points_itm', $parent_point->id , [
    'title' => $newpoint_name,
    'id_point' => $id_point,
    'worker' => $worker,
    ]);

    $remains_folder = $pages->get('template=remains');   
    $date_create = date("d-m-Y");
    $pages->add('remains_point', $remains_folder->id , [
    'title' => $newpoint_name,
    'actual_date' => $date_create,
    'id_point' => $id_point . '_startday',
    'type_remains' => 'Остаток на начало дня',
    ]);
    $pages->add('remains_point', $remains_folder->id , [
    'title' => $newpoint_name,
    'actual_date' => $date_create,
    'id_point' => $id_point . '_actual',
    'type_remains' => 'Текущий остаток',
    ]);
    $pages->add('remains_point', $remains_folder->id , [
    'title' => $newpoint_name,
    'actual_date' => $date_create,
    'id_point' => $id_point . '_reserv',
    'type_remains' => 'Резерв',
    ]);

    $remains_point_startday = $pages->get('template=remains_point, id_point=' . $id_point . '_startday');
    $arr_proba = ['375','333','417','500','585','620','750','800','850','875','900','916','958','990','999','Ag','Ag-800','Ag-875','Ag-925','Ag-999','Pt','Pd'];
    foreach ($arr_proba as $proba_itm) {
        //echo $proba_itm . '<br>';
        $pages->add('remains_point_itm', $remains_point_startday->id , [
        'title' => $proba_itm,
        'remain' => 0,
        ]);
    }

    $remains_point_actual = $pages->get('template=remains_point, id_point=' . $id_point . '_actual');
    $arr_proba = ['375','333','417','500','585','620','750','800','850','875','900','916','958','990','999','Ag','Ag-800','Ag-875','Ag-925','Ag-999','Pt','Pd'];
    foreach ($arr_proba as $proba_itm) {
        //echo $proba_itm . '<br>';
        $pages->add('remains_point_itm', $remains_point_actual->id , [
        'title' => $proba_itm,
        'remain' => 0,
        ]);
    }

    $remains_point_reserv = $pages->get('template=remains_point, id_point=' . $id_point . '_reserv');
    $arr_proba = ['375','333','417','500','585','620','750','800','850','875','900','916','958','990','999','Ag','Ag-800','Ag-875','Ag-925','Ag-999','Pt','Pd'];
    foreach ($arr_proba as $proba_itm) {
        //echo $proba_itm . '<br>';
        $pages->add('remains_point_itm', $remains_point_reserv->id , [
        'title' => $proba_itm,
        'remain' => 0,
        ]);
    }
    //Запускаем скрипт по созданию новой точки
} else {
	$success = 'Регистрация новой точки "' . $newpoint_name . '" не прошла!<br>Ошибка в данных';
    $success .= '<br>' . $error;
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

include 'adminpanel_access.php';

if ($operator == 'no_operator' || $selected_point == 'no_point' || $page_access == false) {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Регистрация новой точки</h1>
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Регистрация новой точки</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
            </div>
        </div>

        <div>
            <h3 class="uk-card-title"><?php echo $success; ?></h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/">Вернутся на главную страницу</a>
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