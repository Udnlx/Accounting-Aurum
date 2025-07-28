<?php namespace ProcessWire;

$_SESSION['reload'] = 'off';

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

if ($operator == 'no_operator' || $selected_point == 'no_point' || $access != 'admin') {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Внесение правок в операцию</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title uk-text-center">Нет прав на эту страницу, потеряна сессия или точка, перезайти</h3>
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Внесение правок в операцию</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/adminpanel-meniu/">Админ панель</a>
                <a class="menu-link" href="/pravka-operatcii-poisk/">Правки в операциях</a>
            </div>
        </div>

        <div>
	        <p class="uk-margin-remove">Дата: <span style="font-weight: 700;"><?php echo $date; ?></span></p>
	        <p class="uk-margin-remove">Точка: <span style="font-weight: 700;"><?php echo $point; ?></span></p>
	        <p class="uk-margin-remove">ID точки: <span style="font-weight: 700;"><?php echo $idpoint; ?></span></p>
	        <p class="uk-margin-remove">Сотрудник: <span style="font-weight: 700;"><?php echo $worker; ?></span></p>
            <p class="uk-margin-remove">Тип операции: <span style="font-weight: 700;"><?php echo $type_operation; ?></span></p>
	        <br>
            <form class="uk-flex uk-flex-column" id="select_seat" action="/pravka-operatcii-registratciia/" method="post">
                <div class="uk-margin-small-bottom uk-hidden">
                    <input class="uk-input" id="id_edit_operation" type="text" name="id_edit_operation" value="<?php echo $operation_id; ?>">
                </div>
    	        <p class="uk-margin-remove">Проба: <span id="proba" style="font-weight: 700;"><?php echo $proba; ?></span></p>
                <div class="uk-margin-small-bottom uk-hidden">
                    <input class="uk-input custom1" id="old_selected_proba" type="text" name="old_selected_proba" value="<?php echo $proba; ?>" autocomplete="off" required>
                </div>
                <div class="uk-margin-small-bottom">
                    <select class="uk-select" id="new_selected_proba" name="new_selected_proba">
                        <option>585</option>
                        <option>375</option>
                        <option>333</option>
                        <option>417</option>
                        <option>500</option>
                        <option>620</option>
                        <option>750</option>
                        <option>800</option>
                        <option>850</option>
                        <option>875</option>
                        <option>900</option>
                        <option>916</option>
                        <option>958</option>
                        <option>990</option>
                        <option>999</option>
                        <option>Ag</option>
                        <option>Ag-875</option>
                        <option>Ag-925</option>
                        <option>Ag-999</option>
                        <option>Pt</option>
                        <option>Pd</option>
                    </select>
                </div>

                <p class="uk-margin-remove">Цена за грамм: <span style="font-weight: 700;"><?php echo $price_gramm; ?></span></p>
                <div class="uk-margin-small-bottom uk-hidden">
                    <input class="uk-input custom1" id="old_price_gramm" type="text" name="old_price_gramm" value="<?php echo $price_gramm; ?>" autocomplete="off" required>
                </div>
                <div class="uk-margin-small-bottom">
                    <input class="uk-input custom1" id="price_gramm" type="text" name="new_price_gramm" value="<?php echo $price_gramm; ?>" autocomplete="off" required>
                </div>

    	        <p class="uk-margin-remove">Вес: <span style="font-weight: 700;"><?php echo $weight; ?></span></p>
                <div class="uk-margin-small-bottom uk-hidden">
                    <input class="uk-input custom1" id="old_selected_weight" type="text" name="old_selected_weight" value="<?php echo $weight; ?>" autocomplete="off" required>
                </div>
                <div class="uk-margin-small-bottom">
                    <input class="uk-input custom1" id="selected_weight" type="text" name="new_selected_weight" value="<?php echo $weight; ?>" placeholder="Вес" autocomplete="off" required>
                </div>

    	        <p class="uk-margin-remove">Итоговая стоимость: <span style="font-weight: 700;"><?php echo $price; ?></span></p>
                <div class="uk-margin-small-bottom uk-hidden">
                    <input class="uk-input" id="old_selected_price" type="text" name="old_selected_price" value="<?php echo $price; ?>" autocomplete="off" required>
                </div>
                <div class="uk-margin-small-bottom">
                    <input class="uk-input" id="selected_price" type="text" name="new_selected_price" value="<?php echo $price; ?>" autocomplete="off" required>
                </div>

    	        <p class="uk-margin-remove">Сумма: <span style="font-weight: 700;"><?php echo $pay; ?></span></p>
                <div class="uk-margin-small-bottom uk-hidden">
                    <input class="uk-input" id="old_selected_pay" type="text" name="old_selected_pay" value="<?php echo $pay; ?>" autocomplete="off" required>
                </div>
                <div class="uk-margin-small-bottom">
                    <input class="uk-input" id="selected_pay" type="text" name="new_selected_pay" value="<?php echo $pay; ?>" autocomplete="off" required>
                </div>

                <div class="uk-margin-small-bottom">
                    <label for="description_operation">Причина изменений (*обязательное поле)</label>
                    <input class="uk-input" id="description_changes" type="text" name="description_changes" autocomplete="off" required>
                </div>

                <p class="uk-margin-remove">Вид платежа: <span style="font-weight: 700;"><?php echo $cash_card; ?></span></p>
                <p class="uk-margin-remove">Описание операции: <span style="font-weight: 700;"><?php echo $description_operation; ?></span></p>
                <div class="uk-margin-small-top uk-flex uk-flex-column">
                    <button class="uk-margin-small-top uk-button uk-button-default" type="submit">Внести изменения</button>
                </div>
            </form>
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