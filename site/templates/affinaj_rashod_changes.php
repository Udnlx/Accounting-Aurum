<?php namespace ProcessWire;

$prod_id = !empty($_GET['prod_id'])?$_GET['prod_id']:NULL;  

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
    	<h1 class="uk-heading-hero uk-text-center">Аффинаж внесение изменений</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title">Потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {

//Формирование таблицы для редактирования
$affinaj_page = $pages->get('id=' . $prod_id . '');
$affinaj_table = '';
$affinaj_table .= '
<div>
    <table class="uk-table-striped">
        <thead>
            <tr>
                <th style="width:16,6%">По пробам</th>
                <th style="width:16,6%">Должно быть</th>
                <th style="width:16,6%">По факту<br>старое значение</th>
                <th style="width:16,6%">По факту<br>новое значение</th>
                <th style="width:16,6%">В 585 должно быть</th>
                <th style="width:16,6%">В 585 по факту<br>старое значение</th>
            </tr>
        </thead>
        <tbody>
';

$fin585 = 0;
foreach ($affinaj_page->affinaj_table as $itm) {
    $fin585 = $fin585 + ($itm->fweight/585*$itm->proba);
}
$actual_fin585 = round($fin585, 2);

$in585 = 0;
foreach ($affinaj_page->affinaj_table as $itm) {
    $in585 = $in585 + ($itm->weight/585*$itm->proba);
}
$actual_in585 = round($in585, 2);

$i = 1;
foreach ($affinaj_page->affinaj_table as $itm) {
    $sum585 = '';
    if ($i == 1) {
        $sum585 = '
        <td rowspan="14" align="center">' . $actual_fin585 . '</td>
        <td rowspan="14" align="center">' . $actual_in585 . '</td>
        ';
    }
    $affinaj_table .= '
    <tr>
        <td>' . $itm->proba . '</td>
        <td id="free_for_affinaj_' . $itm->proba . '">' . $itm->fweight . '</td>
        <td id="old_for_affinaj_' . $itm->proba . '">' . $itm->weight . '</td>
        <td id="new_for_affinaj_' . $itm->proba . '">
            <input class="uk-input edit_weight_affinaj" id="new_weight_affinaj_' . $itm->proba . '" type="text" name="new_weight_for_affinaj_' . $itm->proba . '" value="' . $itm->weight . '">
        </td>
        ' . $sum585 . '
    </tr>
    ';
    $i++;
}
$affinaj_table .= '
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Аффинаж внесение изменений</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/affinazh-raskhod/">Выбрать другой аффинаж</a>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <h4 class="uk-card-title uk-margin-remove">Внесение изменений</h4>
                <p class="uk-margin-remove"><strong>Аффинаж: </strong><?php echo $affinaj_page->title; ?></p>
                <p class="uk-margin-remove"><strong>ID аффинажа: </strong><?php echo $prod_id; ?></p>
                <div class="uk-margin-small-top uk-hidden">
                    <input class="uk-input" id="affinaj_date" type="text" name="affinaj_date" value="<?php echo $today; ?>">
                </div>
                <div class="uk-margin-small-top uk-hidden">
                    <input class="uk-input" id="affinaj_point" type="text" name="affinaj_point" value="<?php echo $selected_point; ?>">
                </div>
                <div class="uk-margin-small-top uk-hidden">
                 <input class="uk-input" id="affinaj_idpoint" type="text" name="affinaj_idpoint" value="<?php echo $selected_id_point; ?>">
                </div>
                <div class="uk-margin-small-top uk-hidden">
                    <input class="uk-input" id="affinaj_worker" type="text" name="affinaj_worker" value="<?php echo $operator; ?>">
                </div>
                <div class="uk-margin-small-top uk-hidden">
                    <input class="uk-input" id="affinaj_id" type="text" name="affinaj_id" value="<?php echo $prod_id; ?>">
                </div>
                <?php echo $affinaj_table; ?>
                <a id="edit_affinaj" class="uk-margin-small uk-button uk-button-default" href="">Внести изменения</a>
            </div>
            <div id="result_edit_affinaj" class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column" style="padding: 0 40px;">
                <p id="result_add" class="messages" style="color: green;"></p>
            </div>
        </div>
        
        <div>
            <div id="remain_tables" class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <?php echo $remain_tables_startday; ?>
            </div>
        </div>
        
    </div>
</div>

<!-- <script>
document.onkeydown = function (e) {
    if (e.keyCode === 116) {
    return false;
}};
</script> -->

<?php   
}
?>