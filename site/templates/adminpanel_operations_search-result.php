<?php namespace ProcessWire;

$selected_start_date = !empty($_POST['selected_start_date'])?$_POST['selected_start_date']:NULL;
$selected_finish_date = !empty($_POST['selected_finish_date'])?$_POST['selected_finish_date']:NULL;

$f_point = !empty($_POST['f_point'])?$_POST['f_point']:NULL;
$f_worker = !empty($_POST['f_worker'])?$_POST['f_worker']:NULL;
$f_proba = !empty($_POST['f_proba'])?$_POST['f_proba']:NULL;

//Параметры фильтра по операциям
$filter = '';
$filter_point = '';
if ($f_point == 'Все точки') {
    $filter_point = '';
} else {
    $filter_point = 'point=' . $f_point . ', ';
}

$filter_worker = '';
if ($f_worker == 'Все операторы') {
    $filter_worker = '';
} else {
    $filter_worker = 'worker=' . $f_worker . ', ';
}

$filter_proba = '';
if ($f_proba == 'Все пробы') {
    $filter_proba = '';
} else {
    $filter_proba = 'proba=' . $f_proba . ', ';
}
$filter = $filter_point . $filter_worker . $filter_proba;
//Параметры фильтра по операциям

//Параметры фильтра по изделиям в наличии
$filter_stock = '';
$filter_point = '';
if ($f_point == 'Все точки') {
    $filter_point = '';
} else {
    $filter_point = 'point=' . $f_point . ', ';
}

$filter_worker = '';
if ($f_worker == 'Все операторы') {
    $filter_worker = '';
} else {
    $filter_worker = 'worker=' . $f_worker . ', ';
}
$filter_stock = $filter_point . $filter_worker;
//Параметры фильтра по изделиям в наличии

//Параметры фильтра по проданным изделиям
$filter_sell = '';
$filter_point = '';
if ($f_point == 'Все точки') {
    $filter_point = '';
} else {
    $filter_point = 'point=' . $f_point . ', ';
}

$filter_worker = '';
if ($f_worker == 'Все операторы') {
    $filter_worker = '';
} else {
    $filter_worker = 'worker_sell=' . $f_worker . ', ';
}
$filter_sell = $filter_point . $filter_worker;
//Параметры фильтра по проданным изделиям

function get_dates($start, $end, $format = 'd.m.Y') {
    $day = 86400;
    $start = strtotime($start . ' -1 days');
    $end = strtotime($end . ' +1 days');
    $nums = round(($end - $start) / $day); 
    $days = array();
    for ($i = 1; $i < $nums; $i++) { 
        $days[] = date($format, ($start + ($i * $day)));
    }
    return $days;
}
 
$dates = get_dates($selected_start_date, $selected_finish_date);
//print_r($dates);

$std = date('d-m-Y', strtotime($selected_start_date));
$fid = date('d-m-Y', strtotime($selected_finish_date));

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
    	<h1 class="uk-heading-hero uk-text-center">Панель администратора</h1>
        <h3 class="uk-margin-remove uk-heading-hero uk-text-center">Найденные операции</h3>
        <h4 class="uk-margin-remove uk-heading-hero uk-text-center">За период с <?php echo $std; ?> по <?php echo $fid; ?></h4>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title">Потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {

//Получение всех Юзеров
$all_users_folder = $pages->get('template=users_system');
$all_users = $all_users_folder->children();
$option_workers = '';
foreach ($all_users as $user) {
    $option_workers .= '<option value="' . $user->title . '">' . $user->title . '</option>';
}

//Получение операций
$null_result = '<h4 class="uk-card-title uk-margin-remove">Операций за этот период не найдено</h4>';
$all_operations = '';
$all_operations .= '<div class="scrolling-list" style="max-height: 700px;">';

foreach ($dates as $day_itm) {
    $start_day_for_report = date('d-m-Y', strtotime($day_itm));
    $all_operations_itm = $pages->find('template=operation_itm, date=' . $start_day_for_report . ', ' . $filter . '');
    foreach ($all_operations_itm as $itm) {
        $null_result = '';
        $all_operations .= '
        <div class="list-operation-itm">
        <a class="admin-link-itm" href="/prosmotr-operatcii/?operation_id=' . $itm->id . '">
            <p>' . $itm->title . '</p>
            <p class="reserv_id_note">Оператор: ' . $itm->worker . '</p>
        </a>
        </div>
        ';
    }
}

$all_operations .= $null_result;
$all_operations .= '</div>';

//Получение всех изделий в наличии
$null_result = '<h4 class="uk-card-title uk-margin-remove">Изделий в наличии за этот период не найдено</h4>';
$stock_products = '';
$stock_products .= '<div class="scrolling-list" style="max-height: 700px;">';

foreach ($dates as $day_itm) {
    $start_day_for_report = date('d-m-Y', strtotime($day_itm));
    $stock_products_itm = $pages->find('template=product_itm, product_status=в наличии, date=' . $start_day_for_report . ', ' . $filter_stock . '');
    foreach ($stock_products_itm as $itm) {
        $null_result = '';
        $stock_products .= '
        <div class="list-product-itm">
            <div class="list-product-itm-text">
                <p>' . $itm->title . '</p>
                <p style="font-size:10px;">Порядковый номер: ' . $itm->serial_number . '</p>
                <p style="font-size:10px;">' . $itm->product_description . '</p>
                <p style="font-size:10px;">URL Авито: ' . $itm->url_avito . '</p>
                <p style="font-size:10px;">Оператор скупки: ' . $itm->worker . '</p>
                <p style="font-size:12px; font-weight: 700;">Дата скупки: ' . $itm->product_date_buy . '; Цена скупки: ' . $itm->product_price_buy . '</p>
            </div>
            <div class="list-product-itm-image">
                <img class="list-product-itm-image-img" src="' . $itm->url_image . '" alt="">
            </div>
        </div>
        ';
    }
}

$stock_products .= $null_result;
$stock_products .= '</div>';

//Получение всех проданных изделий
$null_result = '<h4 class="uk-card-title uk-margin-remove">Проданных изделий за этот период не найдено</h4>';
$sell_products = '';
$sell_products .= '<div class="scrolling-list" style="max-height: 700px;">';

foreach ($dates as $day_itm) {
    $start_day_for_report = date('d-m-Y', strtotime($day_itm));
    $sell_products_itm = $pages->find('template=product_itm, product_status=продано, date=' . $start_day_for_report . ', ' . $filter_sell . '');
    foreach ($sell_products_itm as $itm) {
        $null_result = '';
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
}

$sell_products .= $null_result;
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
	<h1 class="uk-heading-hero uk-text-center">Панель администратора</h1>
    <h3 class="uk-margin-remove uk-heading-hero uk-text-center">Найденные операции</h3>
    <h4 class="uk-margin-remove uk-heading-hero uk-text-center">За период с <?php echo $std; ?> по <?php echo $fid; ?></h4>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/adminpanel-meniu/">Админ панель</a>
            </div>
        </div>

        <div>
            <h4 class="uk-card-title uk-margin-remove">Укажите параметры для поиска операций</h4>
            <p id="post_point" class="uk-hidden"><?php echo $f_point; ?></p>
            <p id="post_worker" class="uk-hidden"><?php echo $f_worker; ?></p>
            <p id="post_proba" class="uk-hidden"><?php echo $f_proba; ?></p>
            <div class="filtermenu uk-width-1-1">
                <form class="form-select-date" id="select_period_date" action="" method="post">
                    <p class="uk-margin-remove">Дата</p>
                    <div class="uk-flex">
                        <div class="filtermenu-input">
                            <input class="uk-input" id="selected_start_date" type="date" value="<?php echo $selected_start_date; ?>" name="selected_start_date" required>
                        </div>
                        <div class="filtermenu-input">
                            <input class="uk-input" id="selected_finish_date" type="date" value="<?php echo $selected_finish_date; ?>" name="selected_finish_date" required>
                        </div>
                    </div>
                    <div class="uk-margin-small-top">
                        <label for="f_point">Точка</label>
                        <select class="uk-select" id="f_point" name="f_point" required>
                            <option value="Все точки">Все точки</option>
                            <option value="Тверская 20">Тверская 20</option>
                            <option value="Тверская 14">Тверская 14</option>
                            <option value="Таганка">Таганка</option>
                            <option value="Комсомолка">Комсомолка</option>
                            <option value="Митинская 27а">Митинская 27а</option>
                        </select>
                    </div>
                    <div class="uk-margin-small-top">
                        <label for="f_worker">Оператор</label>
                        <select class="uk-select" id="f_worker" name="f_worker" required>
                            <option value="Все операторы">Все операторы</option>
                            <?php echo $option_workers; ?>
                        </select>
                    </div>
                    <div class="uk-margin-small-top">
                        <label for="f_proba">Проба</label>
                        <select class="uk-select" id="f_proba" name="f_proba">
                            <option value="Все пробы">Все пробы</option>
                            <option value="585">585</option>
                            <option value="375">375</option>
                            <option value="333">333</option>
                            <option value="417">417</option>
                            <option value="500">500</option>
                            <option value="620">620</option>
                            <option value="750">750</option>
                            <option value="800">800</option>
                            <option value="850">850</option>
                            <option value="875">875</option>
                            <option value="900">900</option>
                            <option value="916">916</option>
                            <option value="958">958</option>
                            <option value="990">990</option>
                            <option value="999">999</option>
                            <option value="Ag">Ag</option>
                            <option value="Pt">Pt</option>
                            <option value="Pd">Pd</option>
                        </select>
                    </div>

                    <div class="uk-margin-small-top uk-width-1-1">
                        <button class="uk-margin-remove uk-button uk-button-default uk-width-1-1" type="submit">Найти</button>
                    </div>
                </form>
            </div>
        </div>

        <div>
            <ul class="uk-subnav uk-subnav-pill" uk-switcher>
                <li><a href="#">Операции</a></li>
                <li><a href="#">Изделия в наличии</a></li>
                <li><a href="#">Проданные изделия</a></li>
            </ul>

            <div class="uk-switcher uk-margin">
                <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                    <?php echo $all_operations; ?>
                </div>
                <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                    <?php echo $stock_products; ?>
                </div>
                <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                    <?php echo $sell_products; ?>
                </div>
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