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

if ($operator == 'no_operator' || $selected_point == 'no_point') {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Закрытие смены</h1>
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

//Получение открытых заявок закрытия смены по точке
$all_requests = '';
$all_requests_itm = $pages->find('template=close_day_request_itm, product_status=Открыта, id_point=' . $selected_id_point . ', sort=-sort');
foreach ($all_requests_itm as $itm) {
    $all_requests .= '
        <p class="uk-margin-remove">' . $itm->title . '</p>
        <p class="reserv_id_note">Оператор: ' . $itm->worker . '</p>
        <br>
    ';
}
$open_request = '';
if ($all_requests) {
    $open_request .= '<h4 class="uk-card-title uk-margin-remove">Открытые заявки</h4>';
    $open_request .= $all_requests; 
}

//Таблица металла для закрытия смены
$metal_close = '';
$actual_items = $actual->children();
$reserv_items = $reserv->children();
$metal_close .= '
<div>
    <table class="uk-table-striped">
        <thead>
            <tr>
                <th style="width:20%">Проба</th>
                <th style="width:20%">Вес</th>
                <th style="width:20%" class="uk-hidden">Вес на закрытие</th>
                <th style="width:20%">В 585</th>
            </tr>
        </thead>
        <tbody>
';

$i = 1;
foreach ($actual_items as $itm) {
    if ($itm->title=='') {
    //echo 'Не выводим значения';
    } else {
    $met_act_name = $itm->title;
    $met_res_item = $reserv_items->get('title=' . $itm->title . '');
    $met_act_weight = $itm->remain;
    $met_res_weight = $met_res_item->remain;
    $free_metal = $met_act_weight - $met_res_weight;

    $sum585 = '';
    if ($i == 1) {
        $free_in585 = round($actual_in585 - $reserv_in585, 2);
        $sum585 = '
        <td rowspan="15" align="center">' . number_format($free_in585, 2, '.', ' ') . '</td>
        ';
    }

    $metal_close .= '
    <tr>
        <td>' . $itm->title . '</td>
        <td id="free_for_close_' . $itm->title . '">' . number_format($free_metal, 2, '.', ' ') . '</td>
        <td id="edit_for_close_' . $itm->title . '" class="uk-hidden">
            <input class="uk-input selected_weight_close" id="weight_close_' . $itm->title . '" type="text" name="weight_for_close_' . $itm->title . '" value="' . $itm->remain . '">
        </td>
        ' . $sum585 . '
    </tr>
    ';
    $i++;
    }
}

$metal_close .= '
        </tbody>
    </table>
</div>
';

//Данные о кассе
$cash_page = $pages->get('template=cash_itm, id_point=' . $selected_id_point . '_cash');
$cash = $cash_page->sum;
$bn_cash = $cash_page->bn_sum;

?>

<div id="content">
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Закрытие смены</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column" style="align-items:center;">
                <h4 class="uk-card-title uk-margin-remove" style="color:red; text-align:center;">
                    Убедитесь, прежде чем закрывать смену, что все операции по дню завершены.<br>
                    После закрытия смены будет создан архив текущего дня и операции станут не доступны до следующего дня.
                </h4>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <form class="uk-flex uk-flex-column" id="select_seat" action="/zakrytie-smeny-registratciia/" method="post">
                    <?php echo $open_request; ?>
                    <h4 class="uk-card-title uk-margin-remove">Данные по металлу</h4>
                    <div class="uk-margin-small-top uk-hidden">
                        <input class="uk-input" id="close_date" type="text" name="close_date" value="<?php echo $actual_date; ?>">
                    </div>
                    <div class="uk-margin-small-top uk-hidden">
                        <input class="uk-input" id="close_point" type="text" name="close_point" value="<?php echo $selected_point; ?>">
                    </div>
                    <div class="uk-margin-small-top uk-hidden">
                     <input class="uk-input" id="close_idpoint" type="text" name="close_idpoint" value="<?php echo $selected_id_point; ?>">
                    </div>
                    <div class="uk-margin-small-top uk-hidden">
                        <input class="uk-input" id="close_worker" type="text" name="close_worker" value="<?php echo $operator; ?>">
                    </div>
                    <?php echo $metal_close; ?>
                    <br>
                    <h4 class="uk-card-title uk-margin-remove">Данные по кассе</h4>
                    <div class="uk-margin-small-top uk-hidden">
                        <input class="uk-input" id="close_cash" type="text" name="close_cash" value="<?php echo $cash; ?>">
                    </div>
                    <h2 class="uk-card-title uk-margin-remove" style="color: green;font-weight: 700;">В Кассе наличка: <?php echo $cash; ?></h2>
                    <div class="uk-margin-small-top uk-hidden">
                        <input class="uk-input" id="close_bn_cash" type="text" name="close_bn_cash" value="<?php echo $bn_cash; ?>">
                    </div>
                    <h2 class="uk-card-title uk-margin-remove" style="color: green;font-weight: 700;">В Кассе безнал: <?php echo $bn_cash; ?></h2>
                    <div class="uk-margin-small-top uk-flex uk-flex-column">
                        <button class="uk-margin-small-top uk-button uk-button-default" type="submit">Закрыть смену</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div>
            <div id="remain_tables" class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <?php echo $remain_tables_startday; ?>
            </div>
        </div>
        
    </div>
</div>

<?php   
}
?>