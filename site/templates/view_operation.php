<?php namespace ProcessWire;

$operation_id = !empty($_GET['operation_id'])?$_GET['operation_id']:NULL;  

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
    	<h1 class="uk-heading-hero uk-text-center">Просмотр операции</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title">Потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {

//Получение страницы продукта
$operation_page = $pages->get('id=' . $operation_id . '');

//Получение данных о продукте
$type_operation = $operation_page->type_operation;  
$undertype_operation = $operation_page->undertype_operation;  
$date = $operation_page->date;  
$point = $operation_page->point;  
$idpoint = $operation_page->id_point;  
$worker = $operation_page->worker;  
$proba = $operation_page->proba;  
$weight = $operation_page->weight;  
$price_gramm = $operation_page->price_gramm;  
$price = $operation_page->price;  
$pay = $operation_page->pay;  
$cash_card = $operation_page->cash_card; 
$description_operation = $operation_page->description_operation; 

$paytype = $operation_page->paytype;  
$client_name = $operation_page->client_name;  
$client_passport = $operation_page->client_passport;  
$client_address = $operation_page->client_address; 

//Функционал распечатки квитанции
$info_paytype = '';
if ($paytype == 'Да') {
    $info_paytype = '
    <p class="uk-margin-remove">ФИО клиента: <span style="font-weight: 700;">' . $client_name . '</span></p>
    <p class="uk-margin-remove">Паспорт клиента: <span style="font-weight: 700;">' . $client_passport . '</span></p>
    <p class="uk-margin-remove">Адрес клиента: <span style="font-weight: 700;">' . $client_address . '</span></p>
    <form target="_blank" class="uk-flex uk-flex-column" id="print_receipt" action="/raspechatka-kvitantcii/" method="post">
        <div class="uk-margin-small-top uk-hidden">
            <input class="uk-input" id="operation_id" type="text" name="operation_id" value="' . $operation_id . '">
        </div>
        <div class="uk-margin-small-top uk-hidden">
            <input class="uk-input" id="print_type" type="text" name="print_type" value="' . $type_operation . '">
        </div>
        <div class="uk-margin-small-top uk-hidden">
            <input class="uk-input" id="print_undertype" type="text" name="print_undertype" value="' . $undertype_operation . '">
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
            <input class="uk-input" id="print_proba" type="text" name="print_proba" value="' . $proba . '">
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Просмотр операции</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/adminpanel-meniu/">Админ панель</a>
                <a class="menu-link" href="/adminpanel-vse-operatcii/">Все операции</a>
            </div>
        </div>

        <div>
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
            <p class="uk-margin-remove">Вид платежа: <span style="font-weight: 700;"><?php echo $cash_card; ?></span></p>
            <p class="uk-margin-remove">Описание операции: <span style="font-weight: 700;"><?php echo $description_operation; ?></span></p>
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