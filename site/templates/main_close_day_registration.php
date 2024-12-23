<?php namespace ProcessWire;

$date = date("d-m-Y");

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

$success = 'Закрытие смены прошло успешно';
if ($_SESSION['reload'] == 'on') {
    $success = 'Повторная отправка данных!<br>Закрытие смены повторно не проведено';   
} else {
    //Создаем архив данных по металлу
    $data_archive = '';
    $remains_parent = $pages->get('template=remains');
    $remains_points = $remains_parent->children('template=remains_point, id_point*=' . $selected_id_point . '');
    foreach ($remains_points as $remains_point) {
        $data_archive .= ':::' . $remains_point->title . '===' . $remains_point->type_remains . '===';
        $ramains_items = $remains_point->children();
        foreach ($ramains_items as $ramains_item) {
        $data_archive .= '/' . $ramains_item->title . '-' . $ramains_item->remain . '';
        }
    }

    $archive_group = $pages->get('template=remains_archive_group, id_point=' . $selected_id_point . '');
    $archive_group_id = $archive_group->id;
    $pages->add('remains_archive_itm', $archive_group_id , [
    'title' => $date,
    'data_archive' => $data_archive,
    ]);

    //Переносим текущие остатки по металлу на остатки начала дня
    $actual_rew = $pages->get('id_point=point1_actual');
    $startday_rew = $pages->get('id_point=point1_startday');
    $actual_items = $actual_rew->children();
    $startday_items = $startday_rew->children();
    foreach ($actual_items as $itm) {
        $met_start_item = $startday_items->get('title=' . $itm->title . '');
        $met_start_item->of(false);
        $met_start_item->remain = $itm->remain;
        $met_start_item->save();
    }

    //Создаем архив данных по кассе';
    $cash_data_archive = '';
    $cash_remains_parent = $pages->get('template=cash');
    $cash_remains_point = $cash_remains_parent->get('template=cash_itm, id_point=' . $selected_id_point . '_cash');
    $cash_data_archive .= '' . $cash_remains_point->title . '===' . $cash_remains_point->sum . '===' . $cash_remains_point->bn_sum . '===';

    $cash_archive_group = $pages->get('template=cash_archive_group, id_point=' . $selected_id_point . '');
    $cash_archive_group_id = $cash_archive_group->id;
    $pages->add('remains_archive_itm', $cash_archive_group_id , [
    'title' => $date,
    'data_archive' => $cash_data_archive,
    ]);

    //Создаем архив данных по общей кассе';
    $cash_data_archive = '';
    $cash_remains_parent = $pages->get('template=cash');
    $cash_remains_point = $cash_remains_parent->get('template=cash_itm, id_point=all_cash');
    $cash_data_archive .= '' . $cash_remains_point->title . '===' . $cash_remains_point->sum . '===' . $cash_remains_point->bn_sum . '===';

    $cash_archive_group = $pages->get('template=cash_archive_group, id_point=all');
    $cash_archive_group_id = $cash_archive_group->id;
    $pages->add('remains_archive_itm', $cash_archive_group_id , [
    'title' => $date,
    'data_archive' => $cash_data_archive,
    ]);

    //Переносим текущие остатки по кассе на остатки начала дня
    $cash_remains_point = $pages->get('id_point=point1_cash');
    $sum_on_startday = $cash_remains_point->sum;
    $bn_sum_on_startday = $cash_remains_point->bn_sum;
    $cash_remains_point->of(false);
    $cash_remains_point->cash_remain_startday = $sum_on_startday;
    $cash_remains_point->bn_cash_remain_startday = $bn_sum_on_startday;
    $cash_remains_point->save();

    //Переносим текущие остатки по общей кассе на остатки начала дня
    $cash_remains_point = $pages->get('id_point=all');
    $sum_on_startday = $cash_remains_point->sum;
    $bn_sum_on_startday = $cash_remains_point->bn_sum;
    $cash_remains_point->of(false);
    $cash_remains_point->cash_remain_startday = $sum_on_startday;
    $cash_remains_point->bn_cash_remain_startday = $bn_sum_on_startday;
    $cash_remains_point->save();

    //Записываем регистрацию в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Закрытие смены ' . $date . ' - ' . $selected_point . ' === ';
    $log .= 'Смену закрыл: ' . $operator . ''; 
    file_put_contents(__DIR__ . '/log_main_close.txt', $log . PHP_EOL, FILE_APPEND);

    //Устананвливаем статус закрытия смены
    $shift_status_page = $pages->get('id_point=' . $selected_id_point . '_startday');
    $shift_status_page->of(false);
    $shift_status_page->shift_status = 'Закрыта';
    $shift_status_page->save();

    //Предотвращаем повторную регистрацию
    $_SESSION['reload'] = 'on';
}

if ($operator == 'no_operator' || $selected_point == 'no_point') {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Закрытие смены - Регистрация данных</h1>
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Закрытие смены - Регистрация данных</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
            </div>
        </div>

        <div>
            <h3 class="uk-card-title"><?php echo $success; ?></h3>
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