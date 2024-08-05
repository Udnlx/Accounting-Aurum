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
    	<h1 class="uk-heading-hero uk-text-center">Аффинаж расход</h1>
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

?>

<div id="content">
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Аффинаж расход</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/affinazh-tip-affinazha/">Выбрать другой тип аффинажа</a>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <div class="uk-flex uk-flex-column" id="select_affinaj">
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
                        <label for="selected_proba_affinaj">Выберите пробу</label>
                        <select class="uk-select" id="selected_proba_affinaj" name="selected_proba_affinaj">
                            <option>375</option>
                            <option>333</option>
                            <option>417</option>
                            <option>500</option>
                            <option>585</option>
                            <option>620</option>
                            <option>750</option>
                            <option>800</option>
                            <option>850</option>
                            <option>875</option>
                            <option>900</option>
                            <option>916</option>
                            <option>958</option>
                            <option>990</option>
                            <option>999.9</option>
                        </select>
                    </div>
                    <div class="uk-margin-small-top">
                        <input class="uk-input custom1" id="selected_weight_affinaj" type="text" name="selected_weight_affinaj" value="" placeholder="Вес" autocomplete="off" required>
                    </div>
                    
                    <div class="uk-margin-small-top uk-flex uk-flex-column">
                        <button class="add-affilaj uk-margin-small-top uk-button uk-button-default">Добавить</button>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <h4 class="uk-card-title uk-margin-remove">Набранный аффинаж</h4>
                <hr>
                <div id="all_affinaj"></div>
                <div class="uk-margin-small-top uk-flex uk-flex-column">
                    <button id="affilaj-reg-btn" class="uk-margin-small-top uk-button uk-button-default uk-hidden">Зарегистрировать</button>
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