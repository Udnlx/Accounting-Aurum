<?php namespace ProcessWire;

$date = !empty($_POST['close_date'])?$_POST['close_date']:NULL;  
$point = !empty($_POST['close_point'])?$_POST['close_point']:NULL;  
$idpoint = !empty($_POST['close_idpoint'])?$_POST['close_idpoint']:NULL;  
$worker = !empty($_POST['close_worker'])?$_POST['close_worker']:NULL;   

$p375 = $_POST['weight_for_close_375'];
$p333 = $_POST['weight_for_close_333'];
$p417 = $_POST['weight_for_close_417'];
$p500 = $_POST['weight_for_close_500'];
$p585 = $_POST['weight_for_close_585'];
$p620 = $_POST['weight_for_close_620'];
$p750 = $_POST['weight_for_close_750'];
$p800 = $_POST['weight_for_close_800'];
$p850 = $_POST['weight_for_close_850'];
$p875 = $_POST['weight_for_close_875'];
$p900 = $_POST['weight_for_close_900'];
$p916 = $_POST['weight_for_close_916'];
$p958 = $_POST['weight_for_close_958'];
$p990 = $_POST['weight_for_close_990'];
$p999 = $_POST['weight_for_close_999'];
$pag = $_POST['weight_for_close_Ag'];
$ppt = $_POST['weight_for_close_Pt'];
$ppd = $_POST['weight_for_close_Pd'];

$array = array(
    '375' => $p375,
    '333' => $p333,
    '417' => $p417,
    '500' => $p500,
    '585' => $p585,
    '620' => $p620,
    '750' => $p750,
    '800' => $p800,
    '850' => $p850,
    '875' => $p875,
    '900' => $p900,
    '916' => $p916,
    '958' => $p958,
    '990' => $p990,
    '999' => $p999,
    'Ag' => $pag,
    'Pt' => $ppt,
    'Pd' => $ppd,
);

//echo $p375 . $p333 . $p417 . $p500 . $p585 . $p620 . $p750 . $p800 . $p850 . $p875 . $p900 . $p916 . $p958 . $p990 . $p999 . $pag . $ppt . $ppd;

$success = 'Регистрация данных прошла успешно';
if ($p375 == '' || $p333 == '' || $p417 == '' || $p500 == '' || $p585 == '' || $p620 == '' || $p750 == '' || $p800 == '' || $p850 == '' || $p875 == '' || $p900 == '' || $p916 == '' || $p958 == '' || $p990 == '' || $p999 == '' || $pag == '' || $ppt == '' || $ppd == '' || $_SESSION['reload'] == 'on') {
    $success = 'Регистрация не прошла!<br>Ошибка в данных';
    if ($_SESSION['reload'] == 'on') {
        $success = 'Повторная отправка данных!<br>Запись уже существует, регистрация записи повторно не проведена';
    }    
} else {
    //Регестрируем запись
    $pages->add('close_day_request_itm', 1755 , [
    'title' => date("Y-m-d H:i") . ' Закрытие смены ' . $date . ' - ' . $point . '',
    'product_status' => 'Открыта',
    'date' => $date,
    'point' => $point,
    'id_point' => $idpoint,
    'worker' => $worker,
    ]);
    $operation_page = $pages->get('title=' . date("Y-m-d H:i") . ' Закрытие смены ' . $date . ' - ' . $point . '');
    $operation_id = $operation_page->id;

    //Добавляем позиции в запись и вычитаем материал из остатков
    foreach ($array as $key => $value) {
        $closeadd = $pages->get('id=' . $operation_id . '')->close_table->getNew();
        $closeadd->proba = $key;
        $closeadd->weight = $value;
            //Изменяем остатки
            $point_actual_table = $pages->get('id_point=' . $idpoint . '_actual');
            $edit_page = $point_actual_table->get('title=' . $closeadd->proba . '');
            $result = $edit_page->remain - $closeadd->weight;
            $edit_page->of(false);
            $edit_page->remain = $result;
            $edit_page->save();
        $closeadd->save();
        $pages->get('id=' . $operation_id . '')->close_table->add($closeadd);
    }

    //Записываем регистрацию в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Закрытие смены ' . $date . ' - ' . $point . ' === ';
    $log .= 'Запись занесена: ' . $worker . ', ID записи: ' . $operation_id; 
    file_put_contents(__DIR__ . '/log_close.txt', $log . PHP_EOL, FILE_APPEND);

    //Предотвращаем повторную регистрацию
    $_SESSION['reload'] = 'on';
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
	        <p class="uk-margin-remove">Дата: <span style="font-weight: 700;"><?php echo $date; ?></span></p>
	        <p class="uk-margin-remove">Точка: <span style="font-weight: 700;"><?php echo $point; ?></span></p>
	        <p class="uk-margin-remove">ID точки: <span style="font-weight: 700;"><?php echo $idpoint; ?></span></p>
	        <p class="uk-margin-remove">Сотрудник: <span style="font-weight: 700;"><?php echo $worker; ?></span></p>
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