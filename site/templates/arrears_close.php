<?php namespace ProcessWire;

$arrear_id = !empty($_GET['arrear_id'])?$_GET['arrear_id']:NULL;  

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

//Получение страницы долга
$arrear_page = $pages->get('id=' . $arrear_id . '');

if ($operator == 'no_operator' || $selected_point == 'no_point') {
?>
    <div id="content" style="max-width: 700px;">
        <h1 class="uk-heading-hero uk-text-center">Закрытие долга - Регистрация</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title">Потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php    
} else {
$success = 'Регистрация закрытия долга прошла успешно';
if ($arrear_id && $_SESSION['reload'] != 'on') {
    //Изменяем запись
    $arrear_page = $pages->get('id=' . $arrear_id . '');
    $arrear_page->of(false);
    $arrear_page->product_status = 'Закрыт';
    $arrear_page->save();

    //Записываем регистрацию в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' ДОЛГ ЗАКРЫТ - ' . $arrear_page->client_name . ' - ' . $arrear_page->sum . ' ';
    $log .= 'Запись закрыта: ' . $operator . ', ID записи: ' . $arrear_id . ', Описание долга: ' . $arrear_page->description_operation; 
    file_put_contents(__DIR__ . '/log_arrears.txt', $log . PHP_EOL, FILE_APPEND);

    //Предотвращаем повторную регистрацию
    $_SESSION['reload'] = 'on';
} else {
    $success = 'Регистрация закрытия долга не прошла!<br>Ошибка в данных';
    if ($_SESSION['reload'] == 'on') {
        $success = 'Повторная отправка данных!';
    }
}

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
    <h1 class="uk-margin-remove uk-heading-hero uk-text-center">Закрытие долга - Регистрация</h1>
    <div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
            </div>
        </div>

        <div>
            <h3 class="uk-card-title"><?php echo $arrear_page->title; ?></h3>
            <h3 class="uk-card-title"><?php echo $success; ?></h3>
            <p class="uk-margin-remove">Описание закрытого долга: <span style="font-weight: 700;"><?php echo $arrear_page->description_operation; ?></span></p>
            <br>
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