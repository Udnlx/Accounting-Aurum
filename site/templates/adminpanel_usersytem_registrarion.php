<?php namespace ProcessWire;

$edit_operator = !empty($_POST['edit_operator'])?$_POST['edit_operator']:NULL;  
$user_id = !empty($_POST['user_id'])?$_POST['user_id']:NULL;
$user_login = !empty($_POST['user_login'])?$_POST['user_login']:NULL;  
$user_password = !empty($_POST['user_password'])?$_POST['user_password']:NULL;  
$user_role = !empty($_POST['user_role'])?$_POST['user_role']:NULL;  

$success = 'Регистрация новых данных прошла успешно';
if ($edit_operator && $user_login && $user_password && $user_role && $_SESSION['reload'] != 'on') {
	//Меняем данные
    $edit_page = $pages->get('id=' . $user_id . '');
    $edit_page->of(false);
    $edit_page->title = $user_login;
    $edit_page->password = $user_password;
    $edit_page->access = $user_role;
    $edit_page->save();

    //Записываем регистрацию  в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Были внесены изменения в пользовательские настройки. ';
    $log .= 'Настройки изменены: ' . $edit_operator . '. ';
    $log .= 'Изменения пользователя ID: ' . $edit_page->id . '; '. $edit_page->title . '; '. $edit_page->access->title;
    file_put_contents(__DIR__ . '/log_user_setup.txt', $log . PHP_EOL, FILE_APPEND);

    //Предотвращаем повторную регистрацию
    $_SESSION['reload'] = 'on';
} else {
	$success = 'Регистрация новых данных не прошла!<br>Ошибка в данных';
    if ($_SESSION['reload'] == 'on') {
        $success = 'Повторная отправка данных!<br>Изменения уже занесены, регистрация записи повторно не проведена';
    }
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

include 'adminpanel_access.php';

if ($operator == 'no_operator' || $selected_point == 'no_point' || $page_access == false) {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Регистрация новых данных по пользователю</h1>
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Регистрация новых данных по пользователю</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/adminpanel-meniu/">Админ панель</a>
                <a class="menu-link" href="/adminpanel-pol-zovateli-sistemy/">Пользователи системы</a>
            </div>
        </div>

        <div>
            <h3 class="uk-card-title"><?php echo $success; ?></h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/adminpanel-pol-zovateli-sistemy/">Пользователи системы</a>
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