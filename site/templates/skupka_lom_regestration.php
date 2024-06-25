<?php namespace ProcessWire;

$date = !empty($_POST['selected_date'])?$_POST['selected_date']:NULL;  
$point = !empty($_POST['selected_point'])?$_POST['selected_point']:NULL;  
$idpoint = !empty($_POST['selected_idpoint'])?$_POST['selected_idpoint']:NULL;  
$worker = !empty($_POST['selected_worker'])?$_POST['selected_worker']:NULL;  
$proba = !empty($_POST['selected_proba'])?$_POST['selected_proba']:NULL;  
$weight = !empty($_POST['selected_weight'])?$_POST['selected_weight']:NULL;  
$price_gramm = !empty($_POST['price_gramm'])?$_POST['price_gramm']:NULL;  
$price = !empty($_POST['selected_price'])?$_POST['selected_price']:NULL;  
$pay = !empty($_POST['selected_pay'])?$_POST['selected_pay']:NULL;  

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
	';
}

$success = 'Регистрация скупки прошла успешно';
if ($worker && $proba && $weight && $price_gramm && $price && $pay) {
	//Регестрируем билет
} else {
	$success = 'Регистрация не прошла!<br>Ошибка в данных';
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
    	<h1 class="uk-heading-hero uk-text-center">Скупка лома - Регистрация скупки</h1>
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Скупка лома - Регистрация скупки</h1>
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
	        <p class="uk-margin-remove">Цена за грамм: <span style="font-weight: 700;"><?php echo $price_gramm; ?></span></p>
	        <p class="uk-margin-remove">Итоговая стоимость: <span style="font-weight: 700;"><?php echo $price; ?></span></p>
	        <p class="uk-margin-remove">Сколько отдали: <span style="font-weight: 700;"><?php echo $pay; ?></span></p>
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