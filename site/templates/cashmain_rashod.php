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

if ($operator == 'no_operator' || $selected_point == 'no_point' || $access != 'admin') {
?>
    <div id="content" style="max-width: 700px;">
        <h1 class="uk-heading-hero uk-text-center">Новая операция расхода по общей кассе</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title">Потеряна сессия или точка, <br>возможно нет прав на эту страницу.</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {

$page_cash = $pages->get('template=cash_itm, id_point=all_cash');
$cash = number_format($page_cash->sum, 2, '.',' ');
$cash_startday = number_format($page_cash->cash_remain_startday, 2, '.',' ');
$bn_cash = number_format($page_cash->bn_sum, 2, '.',' ');
$bn_cash_startday = number_format($page_cash->bn_cash_remain_startday, 2, '.',' ');

//Получение всех операций по кассе
$all_cash_operation = '';
$all_cash_operation_itm = $pages->get('template=cash_itm, id_point=all_cash');
$all_operation = $all_cash_operation_itm->children('sort=-id, type_operation=Расход, limit=20');

$all_cash_operation .= '<div class="scrolling-list" style="max-height: 700px;">';
foreach ($all_operation as $itm) {
    $all_cash_operation .= '
        <p>' . $itm->title . '</p>
        <p style="font-size:10px;">ID операции: ' . $itm->id . '</p>
        <p style="font-size:10px;">Оператор: ' . $itm->worker . '</p>
        <p style="font-size:10px;">Тип платежа: ' . $itm->cash_card . '</p>
        <p style="font-size:14px;font-weight:700;">Тип операции: ' . $itm->type_operation . ' - ' . $itm->sum . '</p>
        <p style="font-size:10px;">Описание: ' . $itm->note . '</p>
        <hr>
    ';
}
$all_cash_operation .= '</div>';

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
    <h1 class="uk-margin-remove uk-heading-hero uk-text-center">Новая операция расхода</h1>
    <h3 class="uk-margin-remove uk-heading-hero uk-text-center">Общая касса</h3>
    <div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/obshchaia-kassa-tip-operatcii/">Выбрать другой тип операции</a>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <p class="uk-margin-remove">Касса наличка на начало дня: <?php echo $cash_startday; ?></p>
                <p class="uk-margin-remove">Касса безнал на начало дня: <?php echo $bn_cash_startday; ?></p>
                <h2 class="uk-card-title uk-margin-remove" style="color: green;font-weight: 700;">В Кассе наличка: <?php echo $cash; ?></h2>
                <h2 class="uk-card-title uk-margin-remove" style="color: green;font-weight: 700;">В Кассе безнал: <?php echo $bn_cash; ?></h2>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column" style="padding: 0 40px 40px 40px;">
                <form class="uk-flex uk-flex-column" id="select_seat" action="/obshchaia-kassa-raskhod-registratciia/" method="post">
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

                    <div class="uk-margin-small-top">
                        <input class="uk-input" id="selected_sum" type="text" name="selected_sum" value="" placeholder="Сумма" autocomplete="off" required>
                    </div>
                    <div class="uk-margin-small-top">
                        <label for="cash_card">Вид платежа</label>
                        <select class="uk-select" id="cash_card" name="cash_card">
                            <option>Наличный расчет</option>
                            <option>Безналичный расчет</option>
                        </select>
                    </div>
                    <div class="uk-margin-small-top">
                        <input class="uk-input" id="cash_description" type="text" name="cash_description" value="" placeholder="Описание" autocomplete="off" required>
                    </div>
                    
                    <div class="uk-margin-small-top uk-flex uk-flex-column">
                        <button class="uk-margin-small-top uk-button uk-button-default" type="submit">Зарегистрировать</button>
                    </div>
                </form>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <h4 class="uk-card-title uk-margin-remove">Последние 20 операций расхода по общей кассе</h4>
                <hr>
                <div id="all_cash_operation">
                    <?php echo $all_cash_operation; ?>
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