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
    	<h1 class="uk-heading-hero uk-text-center">Аффинаж зарегестрирован</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title">Потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {

//Формирование данных аффинажа
$id = $_GET['id'];
$affinaj_page = $pages->get('id=' . $id . '');
$affinaj_table = '';
$affinaj_table = '
<div>
    <table class="uk-table-striped">
        <thead>
            <tr>
                <th style="width:20%">По пробам</th>
                <th style="width:20%">Должно быть</th>
                <th style="width:20%">По факту</th>
                <th style="width:20%">В 585 должно быть</th>
                <th style="width:20%">В 585 по факту</th>
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
    //$met_act_name = $itm->title;
    //$met_res_item = $reserv_items->get('title=' . $itm->title . '');
    //$met_act_weight = $itm->remain;
    //$met_res_weight = $met_res_item->remain;
    //$free_metal = $met_act_weight - $met_res_weight;

    $sum585 = '';
    if ($i == 1) {
        //$free_in585 = round($actual_in585 - $reserv_in585, 2);
        $sum585 = '
        <td rowspan="14" align="center">' . $actual_fin585 . '</td>
        <td rowspan="14" align="center">' . $actual_in585 . '</td>
        ';
    }

    $affinaj_table .= '
    <tr>
        <td>' . $itm->proba . '</td>
        <td>' . $itm->fweight . '</td>
        <td>' . $itm->weight . '</td>
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Аффинаж зарегестрирован</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/affinazh-raskhod/">Открытые и отправленные аффинажи</a>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <h4 class="uk-card-title uk-margin-remove">Данные аффинажа</h4>  
                <p class="uk-margin-remove" style="color: green;"><strong>Аффинаж успешно зарегестрирован</strong></p>
                <p class="uk-margin-remove"><strong>ID: </strong><?php echo $id; ?></p>
                <p class="uk-margin-remove"><strong>Дата: </strong><?php echo $affinaj_page->date; ?></p>
                <p class="uk-margin-remove"><strong>Точка: </strong><?php echo $affinaj_page->point; ?></p>
                <p class="uk-margin-remove"><strong>Оператор: </strong><?php echo $affinaj_page->worker; ?></p>
                <p class="uk-margin-remove"><strong>Cтатус: </strong><?php echo $affinaj_page->product_status; ?></p>
                <?php echo $affinaj_table; ?>
                <a class="uk-margin-small uk-button uk-button-default" href="/affinazh-raskhod/">Открытые и отправленные аффинажи</a>
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