<?php namespace ProcessWire;

error_reporting(E_ERROR | E_PARSE);

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

include 'affinaj_access.php';

if ($operator == 'no_operator' || $selected_point == 'no_point' || $page_access == false) {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Аффинаж Au расход</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title uk-text-center">Нет прав на эту страницу, потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {

//Получение всех открытых и отпраленных аффинажей по золоту
$all_notend_affinaj = '';
$all_notend_affinaj_itm = $pages->find('template=affinaj_itm, id_point=' . $selected_id_point . ', sort=-id');
$all_notend_affinaj .= '<div class="scrolling-list" style="max-height: 700px;">';
foreach ($all_notend_affinaj_itm as $itm) {
    if ($itm->product_status == 'Открыт') {
        $all_notend_affinaj .= '<p>' . $itm->title . '</p>';
        $all_notend_affinaj .= '<p style="font-size:10px;">ID аффинажа: ' . $itm->id . '</p>';
        $all_notend_affinaj .= '<p style="font-size:14px;font-weight:700;">Статус: ' . $itm->product_status . '</p>';
        $all_notend_affinaj .= '
        <div class="affinaj-link">
            <a class="affinaj-link-lnk" href="/affinazh-raskhod-otpravka/?id=' . $itm->id . '">Отправить</a>
            <a class="affinaj-link-lnk" href="/affinazh-raskhod-vnesti-izmeneniia/?prod_id=' . $itm->id . '">Внести изменения</a>
        </div><hr>
        ';
    }
    if ($itm->product_status == 'Отправлен') {
        $all_notend_affinaj .= '<p>' . $itm->title . '</p>';
        $all_notend_affinaj .= '<p style="font-size:10px;">ID аффинажа: ' . $itm->id . '</p>';
        $all_notend_affinaj .= '<p style="font-size:14px;font-weight:700;">Статус: ' . $itm->product_status . '</p>';
        $all_notend_affinaj .= '
        <div class="affinaj-link">
            <a class="affinaj-link-lnk" href="/affinazh-prikhod-zakrytie/?id=' . $itm->id . '">Закрыть</a>
            <a class="affinaj-link-lnk" href="/affinazh-raskhod-vnesti-izmeneniia/?prod_id=' . $itm->id . '">Внести изменения</a>
        </div><hr>
        ';
    }
}
$all_notend_affinaj .= '</div>';

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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Аффинаж Au расход</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/affinazh-tip-affinazha/">Выбрать другой тип аффинажа</a>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <h4 class="uk-card-title uk-margin-remove">Открытые и отправленные аффинажи по золоту</h4>
                <hr>
                <div id="all_open_affinaj">
                    <?php echo $all_notend_affinaj; ?>
                </div> 
                <a class="uk-margin-small uk-button uk-button-default" href="/affinazh-raskhod-sozdanie-novogo/">Новый аффинаж</a>      
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