<?php namespace ProcessWire;

$date = date("d-m-Y");  
if(isset($_SESSION['point'])){
    $point = $_SESSION['point'];
} else {
    $point = 'no_point';
}
if(isset($_SESSION['id_point'])){
    $idpoint = $_SESSION['id_point'];
} else {
    $idpoint = 'no_id_point';
}

$worker = !empty($_POST['worker'])?$_POST['worker']:NULL;  
$id_product_dell = !empty($_POST['id_product_dell'])?$_POST['id_product_dell']:NULL;  

//Получение страницы продукта
$product_page = $pages->get('id=' . $id_product_dell . '');

$success = 'Удаление изделия прошло успешно';
if ($worker && $id_product_dell && $_SESSION['reload'] != 'on') {
	//Удаляем запись
    $del_page = $pages->get('template=product_itm, id=' . $product_page->id . '');
    $title_del_page = $del_page->title;
    $id_del_page = $del_page->id;
    $pages->delete($del_page);

    //Записываем удаление в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Удалено изделие - ' . $title_del_page . '; ';
    $log .= 'Сотрудник удаления: ' . $worker . ', ID удаленной записи: ' . $id_del_page . '; '; 
    file_put_contents(__DIR__ . '/log_products_dell.txt', $log . PHP_EOL, FILE_APPEND);

    //Предотвращаем повторную регистрацию
    $_SESSION['reload'] = 'on';
} else {
	$success = 'Удаление не прошло!<br>Ошибка в данных';
    if ($_SESSION['reload'] == 'on') {
        $success = 'Повторная отправка данных!';
    }
    $info_paytype = '';
}

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

include 'prodaja_access.php';

if ($operator == 'no_operator' || $selected_point == 'no_point' || $page_access == false) {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Удаление изделия - Регистрация удаления</h1>
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Удаление изделия - Регистрация удаления</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
            </div>
        </div>

        <div>
            <h3 class="uk-card-title"><?php echo $product_page->title; ?></h3>
            <h3 class="uk-card-title"><?php echo $success; ?></h3>
            <p class="uk-margin-remove">Дата удаления: <span style="font-weight: 700;"><?php echo $today; ?></span></p>
            <p class="uk-margin-remove">Сотрудник удаления: <span style="font-weight: 700;"><?php echo $worker; ?></span></p>
        </div>

        <br>
        
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