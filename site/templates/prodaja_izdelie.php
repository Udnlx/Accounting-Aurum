<?php namespace ProcessWire;

$_SESSION['reload'] = 'off';

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

$role_menu = '';
if ($access != 'seller') {
    $role_menu = '<a class="menu-link" href="/prodazha-tip-prodazhi/">Выбрать другой тип продажи</a>';
}

if ($operator == 'no_operator' || $selected_point == 'no_point') {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Продажа изделия</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title">Потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {

//Получение всех изделий в наличии
$stock_products = '';
//$stock_products_itm = $pages->find('template=product_itm, id_point=' . $selected_id_point . ', product_status=в наличии, sort=-sort');
$stock_products_itm = $pages->find('template=product_itm, product_status=в наличии, sort=-sort');
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
    ';
    if ($access != 'seller') {
       $stock_products .= '
            <div class="product-link">
                <a class="product-link-lnk" href="/prodazha-izdelie-prodat/?prod_id=' . $itm->id . '">Продать</a>
                <a class="product-link-lnk" href="/prodazha-izdelie-vnesti-izmeneniia/?prod_id=' . $itm->id . '">Внести изменения</a>
            </div>
       '; 
    }
    $stock_products .= '
        </div>
        <div class="list-product-itm-image">
            <img class="list-product-itm-image-img" src="' . $itm->url_image . '" alt="">
        </div>
    </div>
    ';
}
$stock_products .= '</div>';

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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Продажа изделия</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <?php echo $role_menu; ?>
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
                <?php echo $remain_tables_startday; ?>
            </div>
        </div>
        
    </div>
</div>

<?php   
}
?>