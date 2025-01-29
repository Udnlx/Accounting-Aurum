<?php namespace ProcessWire;

$main_options = $pages->get('template=main_options');
$main_price_gold = $main_options->main_price_gold;
$main_price_gold_999 = $main_options->main_price_gold_999;
$main_price_silver = $main_options->main_price_silver;
$main_price_platinum = $main_options->main_price_platinum;
$main_price_palladium = $main_options->main_price_palladium;

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
    	<h1 class="uk-heading-hero uk-text-center">Отмена продажи лома - Внести изменения</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title">Потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {

//Получение страницы продукта
$operation_page = $pages->get('id=' . $operation_id . '');

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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Отмена продажи лома - Внести изменения</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/otmena-prodazha-lom/">Выбрать другую продажу</a>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <h3 class="uk-margin-remove uk-card-title"><?php echo $operation_page->title; ?></h3>
                <p style="margin:0; font-size:16px; font-weight: 700;">ID операции: <?php echo $operation_page->id; ?>; Цена продажи: <?php echo $operation_page->pay; ?>; Оператор продажи: <?php echo $operation_page->worker; ?></p>
                <p class="textwarning">ВНИМАНИЕ! При внесении изменений операция под номером <?php echo $operation_page->id; ?> будет отменена, проба <?php echo $operation_page->proba; ?> в размере <?php echo $operation_page->weight; ?> г. будет возвращена в текущие остатки, в тоже время будет создана новая запись о продаже от текущей даты и с вычетом выбранной пробы, выбранного размера и указанных параметров, с комментарием, что скупка произведена в счет изменений операции <?php echo $operation_page->id; ?>.</p>
                <br>
                <form class="uk-flex uk-flex-column" id="select_seat" action="/otmena-prodazha-lom-registratciia-izmenenii/" method="post">
                    <p style="margin:0; font-size:20px; font-weight: 700;">Данные отменяемой записи</p>
                    <p style="margin:0; font-size:16px; font-weight: 700;">Проба: <?php echo $operation_page->proba; ?></p>
                    <p style="margin:0; font-size:16px; font-weight: 700;">Вес: <?php echo $operation_page->weight; ?></p>
                    <p style="margin:0; font-size:16px; font-weight: 700;">Вид платежа: <?php echo $operation_page->cash_card; ?></p>
                    <p style="margin:0; font-size:16px; font-weight: 700;">Квитанция: <?php echo $operation_page->paytype; ?></p>
                    <p style="margin:0; font-size:16px; font-weight: 700;">ФИО клиента: <?php echo $operation_page->client_name; ?></p>
                    <p style="margin:0; font-size:16px; font-weight: 700;">Паспорт клиента: <?php echo $operation_page->client_passport; ?></p>
                    <p style="margin:0; font-size:16px; font-weight: 700;">Адрес клиента: <?php echo $operation_page->client_address; ?></p>
                	<div class="uk-margin-small-top uk-hidden">
                        <input class="uk-input" id="id_operation_changes" type="text" name="id_operation_changes" value="<?php echo $operation_page->id; ?>">
                    </div>
                    <div class="uk-margin-small-top uk-hidden">
                        <input class="uk-input" id="proba_changes" type="text" name="proba_changes" value="<?php echo $operation_page->proba; ?>">
                    </div>
                    <div class="uk-margin-small-top uk-hidden">
                        <input class="uk-input" id="weight_changes" type="text" name="weight_changes" value="<?php echo $operation_page->weight; ?>">
                    </div>
                    <div class="uk-margin-small-top">
                        <label for="reason_cancel">Укажите причину отмены</label>
                        <input class="uk-input" id="reason_cancel" type="text" name="reason_cancel" value="" autocomplete="off" required>
                    </div>
                    <br>

                    <p style="margin:0; font-size:20px; font-weight: 700;">Данные новой записи</p>
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
                        <input class="uk-input" id="main_price_gold" type="text" name="main_price_gold" value="<?php echo $main_price_gold; ?>">
                    </div>
                    <div class="uk-margin-small-top uk-hidden">
                        <input class="uk-input" id="main_price_gold_999" type="text" name="main_price_gold_999" value="<?php echo $main_price_gold_999; ?>">
                    </div>
                    <div class="uk-margin-small-top uk-hidden">
                        <input class="uk-input" id="main_price_silver" type="text" name="main_price_silver" value="<?php echo $main_price_silver; ?>">
                    </div>
                    <div class="uk-margin-small-top uk-hidden">
                        <input class="uk-input" id="main_price_platinum" type="text" name="main_price_platinum" value="<?php echo $main_price_platinum; ?>">
                    </div>
                    <div class="uk-margin-small-top uk-hidden">
                        <input class="uk-input" id="main_price_palladium" type="text" name="main_price_palladium" value="<?php echo $main_price_palladium; ?>">
                    </div>

                    <div class="uk-margin-small-top">
                        <label for="selected_proba">Выберите пробу</label>
                        <select class="uk-select" id="selected_proba" name="selected_proba">
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
                            <option>Pt</option>
                            <option>Pd</option>
                        </select>
                    </div>
                    <div class="uk-margin-small-top">
                        <input class="uk-input custom1" id="selected_weight" type="text" name="selected_weight" value="" placeholder="Вес" autocomplete="off" required>
                    </div>
                    <div class="uk-margin-small-top">
                        <label for="price_gramm">Цена за грамм</label>
                        <input class="uk-input readonly" id="price_gramm" type="text" name="price_gramm" value="<?php echo number_format(round(($main_price_gold/585)*585), 2, '.',''); ?>" autocomplete="off" required>
                    </div>
                    <div class="uk-margin-small-top">
                        <label for="selected_price">Стоимость</label>
                        <input class="uk-input readonly" id="selected_price" type="text" name="selected_price" value="0.00" autocomplete="off" required>
                    </div>
                    <div class="uk-margin-small-top">
                        <input class="uk-input" id="selected_pay" type="text" name="selected_pay" value="" placeholder="Сумма продажи" autocomplete="off" required>
                    </div>
                    <div class="uk-margin-small-top">
                        <label for="cash_card">Вид платежа</label>
                        <select class="uk-select" id="cash_card" name="cash_card">
                            <option>Наличный расчет</option>
                            <option>Безналичный расчет</option>
                        </select>
                    </div>
                    <div class="uk-margin-small-top">
                        <label for="selected_paytype">Квитанция</label>
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
                    
                    <div class="uk-margin-small-top uk-flex uk-flex-column">
                        <button class="uk-margin-small-top uk-button uk-button-default" type="submit">Внести изменения</button>
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