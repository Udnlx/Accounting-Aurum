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

include 'adminpanel_access.php';

if ($operator == 'no_operator' || $selected_point == 'no_point' || $page_access == false) {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Панель администратора</h1>
        <h4 class="uk-margin-remove uk-heading-hero uk-text-center">Все изделия</h4>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title uk-text-center">Нет прав на эту страницу, потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {

//Получение всех изделий в наличии
$stock_products = '';
$stock_products_itm = $pages->find('template=product_itm, product_status=в наличии, sort=-sort, limit=20');
$stock_products .= '<div class="scrolling-list" style="max-height: 700px;">';
foreach ($stock_products_itm as $itm) {
    $stock_products .= '
    <div class="list-product-itm">
        <div class="list-product-itm-text">
            <p>' . $itm->title . '</p>
            <p style="font-size:10px;">Порядковый номер: ' . $itm->serial_number . '</p>
            <p style="font-size:10px;">' . $itm->product_description . '</p>
            <p style="font-size:10px;">URL Авито: ' . $itm->url_avito . '</p>
            <p style="font-size:10px;">Оператор скупки: ' . $itm->worker . '</p>
            <p style="font-size:12px; font-weight: 700;">Дата скупки: ' . $itm->product_date_buy . '; Цена скупки: ' . $itm->product_price_buy . '</p>
            <div class="product-link">
                <a class="product-link-lnk" href="/adminpanel-izdelie-vnesti-izmeneniia/?prod_id=' . $itm->id . '">Внести изменения</a>
            </div>
        </div>
        <div class="list-product-itm-image">
            <img class="list-product-itm-image-img" src="' . $itm->url_image . '" alt="">
        </div>
    </div>
    ';
}
$stock_products .= '</div>';

//Получение всех проданных изделий
$sell_products = '';
$sell_products_itm = $pages->find('template=product_itm, product_status=продано, sort=-sort, limit=20');
$sell_products .= '<div class="scrolling-list" style="max-height: 700px;">';
foreach ($sell_products_itm as $itm) {
    $receipt = $itm->product_price_sell - $itm->product_price_buy;
    $sell_products .= '
    <div class="list-product-itm">
        <div class="list-product-itm-text">
            <p>' . $itm->title . '</p>
            <p style="font-size:10px;">Порядковый номер: ' . $itm->serial_number . '</p>
            <p style="font-size:10px;">' . $itm->product_description . '</p>
            <p style="font-size:10px;">URL Авито: ' . $itm->url_avito . '</p>
            <p style="font-size:10px;">Оператор скупки: ' . $itm->worker_sell . '</p>
            <p style="font-size:12px; font-weight: 700;">Дата скупки: ' . $itm->product_date_buy . '; Цена скупки: ' . $itm->product_price_buy . '; Дата продажи: ' . $itm->product_date_sell . '; Цена продажи: ' . $itm->product_price_sell . '; Выручка: ' . $receipt . '</p>
            <div class="product-link">
                <a class="product-link-lnk" href="/skupka-izdelie-vozvrat-v-prodazhu/?prod_id=' . $itm->id . '">Вернуть в продажу</a>
            </div>
        </div>
        <div class="list-product-itm-image">
            <img class="list-product-itm-image-img" src="' . $itm->url_image . '" alt="">
        </div>
    </div>
    ';
}
$sell_products .= '</div>';

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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Панель администратора</h1>
    <h4 class="uk-margin-remove uk-heading-hero uk-text-center">Все изделия</h4>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/adminpanel-meniu/">Админ панель</a>
            </div>
        </div>

        <div>
            <h4 class="uk-card-title uk-margin-remove">Последние 20 изделий в наличии и проданные, укажите период для поиска изделий</h4>
            <div class="filtermenu uk-width-1-1">
                <form class="form-select-date" id="select_period_date" action="/adminpanel-vse-izdeliia-rezul-tat-poiska/" method="post">
                    <div class="uk-flex">
                        <div class="filtermenu-input">
                            <input class="uk-input" id="selected_start_date" type="date" name="selected_start_date" required>
                        </div>
                        <div class="filtermenu-input">
                            <input class="uk-input" id="selected_finish_date" type="date" name="selected_finish_date" required>
                        </div>
                    </div>
                    
                    <div class="uk-margin-small-top uk-width-1-1">
                        <button class="uk-margin-remove uk-button uk-button-default uk-width-1-1" type="submit">Найти</button>
                    </div>
                </form>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <h4 class="uk-margin-remove uk-heading-hero">Изделия в наличии</h4><br>
		        <?php echo $stock_products; ?>
		    </div>
		</div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <h4 class="uk-margin-remove uk-heading-hero">Проданные изделия</h4><br>
                <?php echo $sell_products; ?>
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