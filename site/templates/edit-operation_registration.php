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

$date = date("d-m-Y"); 
$id_edit_operation = !empty($_POST['id_edit_operation'])?$_POST['id_edit_operation']:NULL; 
$old_selected_proba = !empty($_POST['old_selected_proba'])?$_POST['old_selected_proba']:NULL; 
$new_selected_proba = !empty($_POST['new_selected_proba'])?$_POST['new_selected_proba']:NULL; 
$old_selected_weight = !empty($_POST['old_selected_weight'])?$_POST['old_selected_weight']:NULL; 
$new_selected_weight = !empty($_POST['new_selected_weight'])?$_POST['new_selected_weight']:NULL; 
$old_price_gramm = !empty($_POST['old_price_gramm'])?$_POST['old_price_gramm']:NULL; 
$new_price_gramm = !empty($_POST['new_price_gramm'])?$_POST['new_price_gramm']:NULL; 
$old_selected_price = !empty($_POST['old_selected_price'])?$_POST['old_selected_price']:NULL; 
$new_selected_price = !empty($_POST['new_selected_price'])?$_POST['new_selected_price']:NULL; 
$old_selected_pay = !empty($_POST['old_selected_pay'])?$_POST['old_selected_pay']:NULL; 
$new_selected_pay = !empty($_POST['new_selected_pay'])?$_POST['new_selected_pay']:NULL; 
$description_changes = !empty($_POST['description_changes'])?$_POST['description_changes']:NULL; 

$page_edit_operation = $pages->get('template=operation_itm, id=' . $id_edit_operation . '');

$success = 'Регистрация изменений в операции прошла успешно';
if ($id_edit_operation && $_SESSION['reload'] != 'on') {

    //Получаем, что было измененно
    $changed = '';
    $edit_lom = 0;
    $edit_cash = 0;
    $cash_operation_id = 0;
    if ($old_selected_proba != $new_selected_proba) {
        $changed .= 'Проба изменилась с ' . $old_selected_proba . ' на ' . $new_selected_proba . '; ';
    }
    if ($old_selected_weight != $new_selected_weight) {
        $changed .= 'Вес изменился с ' . $old_selected_weight . ' на ' . $new_selected_weight . '; ';
        $edit_lom = $new_selected_weight - $old_selected_weight;
    }
    if ($old_price_gramm != $new_price_gramm) {
        $changed .= 'Цена за грамм изменилась с ' . $old_price_gramm . ' на ' . $new_price_gramm . '; ';
    }
    if ($old_selected_price != $new_selected_price) {
        $changed .= 'Итоговая стоимость изменилась с ' . $old_selected_price . ' на ' . $new_selected_price . '; ';
    }
    if ($old_selected_pay != $new_selected_pay) {
        $changed .= 'Сумма скупки изменилась с ' . $old_selected_pay . ' на ' . $new_selected_pay . '; ';
        $edit_cash = $new_selected_pay - $old_selected_pay;
        $cash_operation = $pages->get('template=cash_operation, note$=' . $page_edit_operation->id . '');
        $cash_operation_id = $cash_operation->id;
    }

    $btn_edit_block = '';
    if ($old_selected_proba != $new_selected_proba) {
        $btn_edit_block .= '
        <p class="uk-margin-remove">Вернуть лом ' . $old_selected_proba . ' пробы в размере ' . $old_selected_weight . 'г.</p>
        <input class="uk-input" id="edit_return" type="text" name="edit_return" value="' . $old_selected_weight . '">
        <a id="btn_edit_return" class="uk-margin-small-top uk-button uk-button-default uk-width-1-1">Вернуть</a>
        <br><br>
        <p class="uk-margin-remove">Забрать лом ' . $new_selected_proba . ' пробы в размере ' . $new_selected_weight . 'г.</p>
        <input class="uk-input" id="edit_pick" type="text" name="edit_pick" value="' . $new_selected_weight . '">
        <a id="btn_edit_pick" class="uk-margin-small-top uk-button uk-button-default uk-width-1-1">Забрать</a>
        <br><br>
        ';
        if ($edit_cash != 0) {
            $btn_edit_block .= '
            <p class="uk-margin-remove">Изменить кассу на ' . $edit_cash . 'р.</p>
            <input class="uk-input" id="id_cash" type="text" name="id_cash" value="' . $cash_operation_id . '">
            <input class="uk-input" id="edit_cash" type="text" name="edit_cash" value="' . $edit_cash . '">
            <a id="btn_edit_pick" class="uk-margin-small-top uk-button uk-button-default uk-width-1-1">Изменить</a>
            <br><br>
            ';
        }
    } else {
        if ($edit_lom != 0) {
            $btn_edit_block .= '
            <p class="uk-margin-remove">Изменить лом на ' . $edit_lom . 'г.</p>
            <input class="uk-input" id="edit_lom" type="text" name="edit_lom" value="' . $edit_lom . '">
            <a id="btn_edit_pick" class="uk-margin-small-top uk-button uk-button-default uk-width-1-1">Изменить</a>
            <br><br>
            ';
        }
        if ($edit_cash != 0) {
            $btn_edit_block .= '
            <p class="uk-margin-remove">Изменить кассу на ' . $edit_cash . 'р.</p>
            <input class="uk-input" id="id_cash" type="text" name="id_cash" value="' . $cash_operation_id . '">
            <input class="uk-input" id="edit_cash" type="text" name="edit_cash" value="' . $edit_cash . '">
            <a id="btn_edit_pick" class="uk-margin-small-top uk-button uk-button-default uk-width-1-1">Изменить</a>
            <br><br>
            ';
        }
    }

    //Регестрируем изменения в операции
    $edit_page = $pages->get('template=operation_itm, id=' . $id_edit_operation . '');
    $changes_desc = '===' . date("Y-m-d H-i") . ': ' . $changed . '. Причина: ' . $description_changes;
    $edit_page_title = $edit_page->title;
    $data_array = explode(" - ", $edit_page_title);
    $new_title = $data_array[0] . ' - ' . $data_array[1] . ' - ' . $new_selected_proba . ' - ' . $new_selected_weight . 'г - ' . $data_array[4] . ' - Операция изменялась';
    $edit_page->of(false);
    $edit_page->title = $new_title;
    $edit_page->proba = $new_selected_proba;
    $edit_page->weight = $new_selected_weight;
    $edit_page->price_gramm = $new_price_gramm;
    $edit_page->price = $new_selected_price;
    $edit_page->pay = $new_selected_pay;
    $edit_page->changes = $edit_page->changes . $changes_desc;
    $edit_page->save();

    //Заводим новую запись об изменениях в реестре изменений
    $pages->add('reestr_changes_itm', 4308 , [
    'title' => date("Y-m-d H:i") . ' Изменения в операции - ' . $id_edit_operation,
    'date' => $date,
    'point' => $selected_point,
    'id_point' => $selected_id_point,
    'worker' => $operator,
    'type_operation' => $id_edit_operation,
    'description_operation' => $description_changes,
    'changes' => $changed,
    ]);

    //Записываем изменения в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Внесены изменения по операции - ' . $page_edit_operation->id . ' === ';
    $log .= $changed . ' === '; 
    $log .= 'Причина изменений: ' . $description_changes . ' === ';
    $log .= 'Точка: ' . $selected_point . ' === '; 
    $log .= 'Оператор: ' . $operator; 
    file_put_contents(__DIR__ . '/log_edit_operations.txt', $log . PHP_EOL, FILE_APPEND);
    
    //Предотвращаем повторную регистрацию
    $_SESSION['reload'] = 'on';

} else {
	$success = 'Регистрация изменений в операции не прошла!<br>Ошибка в данных';
    $changed = '';
    $btn_edit_block = '';
    if ($_SESSION['reload'] == 'on') {
        $success = 'Повторная отправка данных!<br>Регистрация изменений в операции повторно не проведена';
        $btn_edit_block = '';
    }
}

if ($operator == 'no_operator' || $selected_point == 'no_point' || $access != 'admin') {
?>
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Внесение правок в операцию - Регистрация</h1>
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
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Внесение правок в операцию - Регистрация</h1>
	<div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
                <a class="menu-link" href="/adminpanel-meniu/">Админ панель</a>
                <a class="menu-link" href="/pravka-operatcii-poisk/">Правки в операциях</a>
            </div>
        </div>

        <div>
            <h3 class="uk-card-title"><?php echo $success; ?></h3>
	        <p class="uk-margin-remove">Дата изменений: <span style="font-weight: 700;"><?php echo $date; ?></span></p>
	        <p class="uk-margin-remove">Точка: <span style="font-weight: 700;"><?php echo $selected_point; ?></span></p>
	        <p class="uk-margin-remove">ID точки: <span style="font-weight: 700;"><?php echo $selected_id_point; ?></span></p>
	        <p class="uk-margin-remove">Оператор изменений: <span style="font-weight: 700;"><?php echo $operator; ?></span></p>
	        <br>
            <p class="uk-margin-remove">ID изменяемой операции: <span style="font-weight: 700;"><?php echo $page_edit_operation->id; ?></span></p>
            <p class="uk-margin-remove">Операция: <span style="font-weight: 700;"><?php echo $page_edit_operation->title; ?></span></p>
	        <p class="uk-margin-remove"><span style="font-weight: 700;">Изменения</span> которые были зарегистрированы: </p>
            <?php echo $changed; ?>
            <br>
            <p class="uk-margin-remove" style="color:red;"><span style="font-weight: 700;font-size: 20px;">Внимание!</span> При изменении данных в операции возможно нужно изменить остатки по лому и кассам!</p>
            <?php echo $btn_edit_block; ?>
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