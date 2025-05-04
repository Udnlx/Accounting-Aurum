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

include 'main_close_access.php';

if ($operator == 'no_operator' || $selected_point == 'no_point' || $page_access == false) {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Заявка проверка</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title uk-text-center">Нет прав на эту страницу, потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {

//Формирование таблицы для редактирования
$id = $_GET['id'];
$close_request_page = $pages->get('id=' . $id . '');
$metal_close = '';
$metal_close .= '
<div>
    <table class="uk-table-striped">
        <thead>
            <tr>
                <th style="width:20%">Проба</th>
                <th style="width:20%">Вес</th>
                <th style="width:20%">Вес на закрытие</th>
            </tr>
        </thead>
        <tbody>
';

$i = 1;
foreach ($close_request_page->close_table as $itm) {
    $metal_close .= '
    <tr>
        <td>' . $itm->proba . '</td>
        <td id="free_for_close_' . $itm->proba . '">' . number_format($itm->weight, 2, '.', ' ') . '</td>
        <td id="edit_for_close_' . $itm->proba . '">
            <input class="uk-input edit_weight_close" id="weight_close_' . $itm->proba . '" type="text" name="weight_for_close_' . $itm->proba . '" value="' . $itm->weight . '">
        </td>
    </tr>
    ';
    $i++;
}
$metal_close .= '
        </tbody>
    </table>
</div>
';

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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Заявка проверка</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/zakrytie-smeny-osnovnaia-otkrytye-zaiavki/">Открытые заявки</a>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <h4 class="uk-card-title uk-margin-remove">Проверка</h4>
                <p class="uk-margin-remove"><strong>Заявка: </strong><?php echo $close_request_page->title; ?></p>
                <p class="uk-margin-remove"><strong>Оператор заявки: </strong><?php echo $close_request_page->worker; ?></p>
                <p class="uk-margin-remove"><strong>ID заявки: </strong><?php echo $id; ?></p>
                <br>
                <form class="uk-flex uk-flex-column" id="select_seat" action="/zakrytie-smeny-osnovnaia-zaiavka-zakrytie/" method="post">
                    <h4 class="uk-card-title uk-margin-remove">Данные по металлу</h4>
                    <div class="uk-margin-small-top uk-hidden">
                        <input class="uk-input" id="id_request" type="text" name="id_request" value="<?php echo $id; ?>">
                    </div>
                    <?php echo $metal_close; ?>
                    <br>
                    <h4 class="uk-card-title uk-margin-remove">Данные по кассе</h4>
                    <h2 class="uk-card-title uk-margin-remove" style="color: green;font-weight: 700;">В Кассе наличка: <?php echo $close_request_page->sum; ?></h2>
                    <div class="uk-margin-small-top">
                        <input class="uk-input" id="close_cash" type="text" name="close_cash" value="<?php echo $close_request_page->sum; ?>">
                    </div>
                    <h2 class="uk-card-title uk-margin-remove" style="color: green;font-weight: 700;">В Кассе безнал: <?php echo $close_request_page->bn_sum; ?></h2>
                    <div class="uk-margin-small-top">
                        <input class="uk-input" id="close_bn_cash" type="text" name="close_bn_cash" value="<?php echo $close_request_page->bn_sum; ?>">
                    </div>
                    <div class="uk-margin-small-top uk-flex uk-flex-column">
                        <button class="uk-margin-small-top uk-button uk-button-default" type="submit">Подтвердить и закрыть</button>
                    </div>
                </form>
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
                <a id="reg_new_arrear" class="uk-margin-small uk-button uk-button-default">Зарегестрировать новый долг</a>
            </div>
            <div id="result_new_arrear" class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column" style="padding: 0 40px;">
                <p id="result_arrear_add" class="messages" style="color: green;"></p>
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