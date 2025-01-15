<?php namespace ProcessWire;

$prod_id = !empty($_GET['prod_id'])?$_GET['prod_id']:NULL;  

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
    	<h1 class="uk-heading-hero uk-text-center">Скупка изделия, возврат в продажу</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title">Потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {

//Получение страницы продукта
$product_page = $pages->get('id=' . $prod_id . '');

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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Скупка изделия, возврат в продажу</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/adminpanel-vse-izdeliia/">Выбрать другое изделие</a>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <form class="uk-flex uk-flex-column" id="select_seat" action="" method="post">
                    <div class="uk-margin-small-top uk-hidden">
                        <input class="uk-input" id="selected_date" type="text" name="selected_date" value="<?php echo $today; ?>">
                    </div>
                    <div class="uk-margin-small-top uk-hidden">
                        <input class="uk-input" id="selected_point" type="text" name="selected_point" value="<?php echo $selected_point; ?>">
                    </div>
                    <div class="uk-margin-small-top uk-hidden">
                        <input class="uk-input" id="selected_idpoint" type="text" name="selected_idpoint" value="<?php echo $selected_id_point; ?>">
                    </div>
                    <div class="uk-margin-small-top uk-hidden">
                        <input class="uk-input" id="selected_worker" type="text" name="selected_worker" value="<?php echo $operator; ?>">
                    </div>

                    <div class="uk-margin-small-top uk-hidden">
                        <input class="uk-input" id="serial_number" type="number" name="serial_number" value="<?php echo $product_page->serial_number; ?>">
                    </div>
                    <div class="uk-margin-small-top">
                        <input class="uk-input" id="product" type="text" name="product" value="<?php echo $product_page->product_name; ?>" placeholder="Изделие" autocomplete="off" required>
                    </div>
                    <div class="uk-margin-small-top">
                        <input class="uk-input" id="product_description" type="text" name="product_description" value="<?php echo $product_page->product_description; ?>" placeholder="Описание" autocomplete="off" required>
                    </div>
                    <div class="uk-margin-small-top">
                        <input class="uk-input custom1" id="selected_weight" type="text" name="selected_weight" value="<?php echo $product_page->weight; ?>" placeholder="Вес" autocomplete="off" required>
                    </div>
                    <div class="uk-margin-small-top">
                        <input class="uk-input" id="selected_pay" type="text" name="selected_pay" value="<?php echo $product_page->product_price_buy; ?>" placeholder="Сколько отдали" autocomplete="off" required>
                    </div>
                    <div class="uk-margin-small-top">
                        <label for="cash_card">Вид платежа</label>
                        <select class="uk-select" id="cash_card" name="cash_card">
                            <option>Наличный расчет</option>
                            <option>Безналичный расчет</option>
                        </select>
                    </div>
                    <div class="uk-margin-small-top">
                        <label for="selected_proba">Квитанция</label>
                        <select class="uk-select" id="selected_paytype" name="selected_paytype">
                            <option>Нет</option>
                            <option>Да</option>
                        </select>
                    </div>

                    <div id="data_client" class="uk-hidden">
                        <div class="uk-margin-small-top">
                            <input class="uk-input" id="client_name" type="text" name="client_name" value="" placeholder="ФИО клиента" autocomplete="off">
                        </div>
                        <div class="uk-margin-small-top">
                            <input class="uk-input" id="client_passport" type="text" name="client_passport" value="" placeholder="Паспорт клиента" autocomplete="off">
                        </div>
                        <div class="uk-margin-small-top">
                            <input class="uk-input" id="client_address" type="text" name="client_address" value="" placeholder="Адрес клиента" autocomplete="off">
                        </div>
                    </div>

                    <div class="uk-margin-small-top">
                        <input class="uk-input" id="url_avito" type="text" name="url_avito" value="<?php echo $product_page->url_avito; ?>" placeholder="URL объявления на Авито" autocomplete="off">
                    </div>
                    <div class="uk-margin-small-top">
                        <input class="uk-input" id="url_image" type="text" name="url_image" value="<?php echo $product_page->url_image; ?>" placeholder="URL изображения" autocomplete="off">
                    </div>
                    
                    <div class="uk-margin-small-top uk-flex uk-flex-column">
                        <button class="uk-margin-small-top uk-button uk-button-default" type="submit">Зарегистрировать</button>
                    </div>
                </form>
            </div>
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