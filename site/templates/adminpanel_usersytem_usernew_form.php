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

include 'adminpanel_access.php';

if ($operator == 'no_operator' || $selected_point == 'no_point') {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Добавление нового пользователя</h1>
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Добавление нового пользователя</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/adminpanel-meniu/">Админ панель</a>
                <a class="menu-link" href="/adminpanel-pol-zovateli-sistemy/">Пользователи системы</a>
            </div>
        </div>

        <div>
	        <p class="uk-margin-remove">Точка: <span style="font-weight: 700;"><?php echo $selected_point; ?></span></p>
	        <p class="uk-margin-remove">ID точки: <span style="font-weight: 700;"><?php echo $selected_id_point; ?></span></p>
	        <p class="uk-margin-remove">Сотрудник: <span style="font-weight: 700;"><?php echo $operator; ?></span></p>
        </div>

        <br>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <h4 class="uk-margin-remove uk-heading-hero">Роли доступа</h4>
                <br>
                <p class="uk-margin-remove uk-text-warning uk-text-bold">admin - Полный доступ</p>
                <p class="uk-margin-remove uk-text-warning uk-text-bold">controller - Доступно: Все кроме Общей кассы, Админ панели и Отчетов</p>
                <p class="uk-margin-remove uk-text-warning uk-text-bold">receiver - Доступно: Скупки, Касса</p>
                <p class="uk-margin-remove uk-text-warning uk-text-bold">seller - Доступно: Просмотр металла и изделий</p>
                <br>
                <h4 class="uk-margin-remove uk-heading-hero">Данные</h4>
                <br>
                <form class="uk-flex uk-flex-column" id="edit_cash" action="/adminpanel-novyi-pol-zovatel-registratciia/" method="post">
                    <div class="uk-margin-small-bottom uk-hidden">
                        <input class="uk-input" id="add_operator" type="text" name="add_operator" value="<?php echo $operator; ?>">
                    </div>
                    <div class="uk-margin-small-bottom">
                        <label for="description_operation">Логин пользователя</label>
                        <input class="uk-input" id="user_login" type="text" name="user_login" value="" autocomplete="off" required>
                    </div>
                    <div class="uk-margin-small-bottom">
                        <label for="description_operation">Пароль пользователя</label>
                        <input class="uk-input" id="user_password" type="text" name="user_password" value="" autocomplete="off" required>
                    </div>
                    <?php
                    $field = $fields->get('name=access');
                    $all_roles = $field->type->getOptions($field);
                    $roles = '';
                    foreach ($all_roles as $role) {
                        $roles .= '
                        <option value="' . $role->id . '">' . $role->title . '</option>
                        ';
                    }
                    ?>
                    <div class="uk-margin-small-top">
                        <label for="user_role">Доступ</label>
                        <select class="uk-select" id="user_role" name="user_role" required>
                            <option value="">Выберите роль доступа</option>
                            <?php echo $roles; ?>
                        </select>
                    </div>
                    <div class="uk-margin-small-top uk-flex uk-flex-column">
                        <button class="uk-margin-small-top uk-button uk-button-default" type="submit">Добавить пользователя</button>
                    </div>
                </form>
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