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
        <h1 class="uk-heading-hero uk-text-center">Закрытие резерва</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title">Потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {

//Формирование открытого аффинажа
$all_open_reserv = '';
$all_open_reserv_itm = $pages->find('template=reserv_itm, product_status= , sort=affinaj_id');
$all_open_reserv .= '<div class="scrolling-list" style="max-height: 700px;">';
foreach ($all_open_reserv_itm as $itm) {
    $all_open_reserv .= '
    <p class="reserv_id" reserv_id="' . $itm->id . '" proba="' . $itm->proba . '" weight="' . $itm->weight . '">' . $itm->id . ' - ' . $itm->title . '</p>
    <p class="reserv_id_note">' . $itm->reserv_note . '</p>
    ';
}
$all_open_reserv .= '</div>';

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
    <h1 class="uk-margin-remove uk-heading-hero uk-text-center">Закрытие резерва</h1>
    <div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/rezerv-tip-rezerva/">Выбрать другую операцию резерва</a>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <h4 class="uk-card-title uk-margin-remove">Открытый резерв</h4>
                <hr>
                <div id="all_open_reserv">
                    <?php echo $all_open_reserv; ?>
                </div>             
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <form class="uk-flex uk-flex-column" id="select_seat" action="/rezerv-zakrytie-registratciia/" method="post">
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
                        <input class="uk-input readonly" id="reserv_id" type="text" name="reserv_id" value="" placeholder="Идентификатор открытого резерва" autocomplete="off" required>
                    </div>
                    <div class="uk-margin-small-top">
                        <label for="selected_proba">Проба</label>
                        <input class="uk-input readonly" id="selected_proba" type="text" name="selected_proba" value="" autocomplete="off" required>
                    </div>
                    <div class="uk-margin-small-top">
                        <label for="selected_weight">Вес</label>
                        <input class="uk-input readonly" id="selected_weight" type="text" name="selected_weight" value="" autocomplete="off" required>
                    </div>

                    <div class="uk-margin-small-top uk-flex uk-flex-column">
                        <button class="uk-margin-small-top uk-button uk-button-default" type="submit">Закрыть резерв</button>
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