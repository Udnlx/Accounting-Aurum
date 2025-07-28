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
        <h1 class="uk-heading-hero uk-text-center">Правки по лому и кассам</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
           <h3 class="uk-card-title uk-text-center">Нет прав на эту страницу, потеряна сессия или точка, перезайти</h3>
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

?>

<div id="content">
    <h1 class="uk-margin-remove uk-heading-hero uk-text-center">Правки по лому и кассам</h1>
    <div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/adminpanel-meniu/">Админ панель</a>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <h4 class="uk-card-title uk-margin-remove">Изменения по лому</h4>
                <form class="uk-flex uk-flex-column" id="edit_lom" action="/pravki-po-lomu-registratciia/" method="post">
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
                            <option>Ag-875</option>
                            <option>Ag-925</option>
                            <option>Ag-999</option>
                            <option>Pt</option>
                            <option>Pd</option>
                        </select>
                    </div>
                    <div class="uk-margin-small-top">
                        <select class="uk-select" id="selected_operation" name="selected_operation" required>
                            <option value="">Вид операции</option>
                            <option value="Приход">Приход</option>
                            <option value="Расход">Расход</option>
                        </select>
                    </div>
                    <div class="uk-margin-small-top">
                        <input class="uk-input custom1" id="selected_weight" type="text" name="selected_weight" value="" placeholder="Вес" autocomplete="off" required>
                    </div>
                    <div class="uk-margin-small-top">
                        <label for="reserv_note">Комментарий</label>
                        <input class="uk-input" id="edit_lom_note" type="text" name="edit_lom_note" value="" autocomplete="off" required>
                    </div>

                    <div class="uk-margin-small-top uk-flex uk-flex-column">
                        <button class="uk-margin-small-top uk-button uk-button-default" type="submit">Зарегистрировать</button>
                    </div>
                </form>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <h4 class="uk-card-title uk-margin-remove">Изменения по кассе</h4>
                <form class="uk-flex uk-flex-column" id="edit_cash" action="/pravki-po-kasse-registratciia/" method="post">
                    <div class="uk-margin-small-top">
                        <label for="cash_card">Вид кассы</label>
                        <select class="uk-select" id="cash_card" name="cash_card">
                            <option>Наличный расчет</option>
                            <option>Безналичный расчет</option>
                        </select>
                    </div>
                    <div class="uk-margin-small-top">
                        <select class="uk-select" id="selected_operation" name="selected_operation" required>
                            <option value="">Вид операции</option>
                            <option value="Приход">Приход</option>
                            <option value="Расход">Расход</option>
                        </select>
                    </div>
                    <div class="uk-margin-small-top">
                        <input class="uk-input" id="selected_sum" type="text" name="selected_sum" value="" placeholder="Сумма" autocomplete="off" required>
                    </div>
                    <div class="uk-margin-small-top">
                        <label for="reserv_note">Комментарий</label>
                        <input class="uk-input" id="edit_cash_note" type="text" name="edit_cash_note" value="" autocomplete="off" required>
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