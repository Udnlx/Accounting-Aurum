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

include 'arrears_access.php';

if ($operator == 'no_operator' || $selected_point == 'no_point' || $page_access == false) {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Долги</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title uk-text-center">Нет прав на эту страницу, потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {

//Получение всех долгов
$open_arrears = '';
$open_arrears_itm = $pages->find('template=arrears_itm, product_status=Открыт, sort=-sort');
$open_arrears .= '<div class="scrolling-list" style="max-height: 700px;">';
foreach ($open_arrears_itm as $itm) {
    $all_payments = '';
    $total = 0;
    $remain = 0;
    foreach ($itm->children() as $child) {
        $all_payments .= '<p style="font-size:10px;">' . $child->title . '</p>';
        $total = $total + $child->sum;
    }
    $remain = $itm->sum - $total;
    $remain_title = '';
    $btn_close = '';
    if ($remain > 0) {
        $remain_title = '<p style="font-size:10px;font-weight:700;color:red;">Осталось оплатить: ' . $remain . '</p>';
    } else {
        $remain_title = '<p style="font-size:10px;font-weight:700;color:green;">Долг полностью оплачен, подтвердите закрытие долга!</p>';
        $btn_close = '<a class="product-link-lnk" href="/dolgi-zakrytie/?arrear_id=' . $itm->id . '">Закрыть долг</a>';
    }

    $open_arrears .= '
    <div class="list-product-itm">
        <div class="list-product-itm-text">
            <p>' . $itm->title . '</p>
            <p style="font-size:10px;">' . $itm->description_operation . '</p>
            <p style="font-size:10px;">Статус: ' . $itm->product_status . '</p>
            <p style="font-size:10px;">Завел долг: ' . $itm->worker . '</p>
            <p style="font-size:10px;font-weight:700;">Осуществленные платежи:</p>
            ' . $all_payments . '
            <p style="font-size:10px;font-weight:700;color:green;">Оплаченно: ' . $total . '</p>
            ' . $remain_title . '
            <div class="product-link">
                <a class="product-link-lnk" href="/dolgi-pogashenie-dolga/?arrear_id=' . $itm->id . '">Погашение долга</a>
                ' . $btn_close . '
            </div>
        </div>
    </div>
    ';
}
$open_arrears .= '</div>';

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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Долги</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <h4 class="uk-margin-remove uk-heading-hero">Открытые долги</h4><br>
                <?php echo $open_arrears; ?>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <h4 class="uk-card-title uk-margin-remove">Записать в долг</h4>  
                <div class="uk-margin-small-top uk-hidden">
                    <input class="uk-input" id="arrear_date" type="text" name="arrear_date" value="<?php echo $today; ?>">
                </div>
                <div class="uk-margin-small-top uk-hidden">
                    <input class="uk-input" id="arrear_point" type="text" name="arrear_point" value="<?php echo $selected_point; ?>">
                </div>
                <div class="uk-margin-small-top uk-hidden">
                 <input class="uk-input" id="arrear_idpoint" type="text" name="arrear_idpoint" value="<?php echo $selected_id_point; ?>">
                </div>
                <div class="uk-margin-small-top uk-hidden">
                    <input class="uk-input" id="arrear_worker" type="text" name="arrear_worker" value="<?php echo $operator; ?>">
                </div>
                <div class="uk-margin-small-top">
                    <label for="arrear_person">На кого долг</label>
                    <input class="uk-input" id="arrear_person" type="text" name="arrear_person" value="">
                </div>
                <div class="uk-margin-small-top">
                    <label for="arrear_sum">Сумма долга</label>
                    <input class="uk-input" id="arrear_sum" type="text" name="arrear_sum" value="">
                </div>
                <div class="uk-margin-small-top">
                    <label for="arrear_descript">Описание</label>
                    <input class="uk-input" id="arrear_descript" type="text" name="arrear_descript" value="">
                </div>
                <a id="reg_new_arrear" class="uk-margin-small uk-button uk-button-default">Зарегистрировать новый долг</a>
            </div>
            <div id="result_new_arrear" class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column" style="padding: 0 40px;">
                <p id="result_arrear_add" class="messages" style="color: green;"></p>
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