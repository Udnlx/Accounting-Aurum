<?php namespace ProcessWire;

$_SESSION['reload'] = 'off';

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
    	<h1 class="uk-heading-hero uk-text-center">Домашняя страница</h1>
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

if ($startday == '' || $actual == '' || $reserv == '') {
    $menu = '<br>';
    $remain_tables_startday .= '
    <h2 class="uk-margin-remove uk-card-title" style="color:red;font-weight:700;text-align:center;">Произошла ошибка получения остатков!<br>Пожалуйста обратитесь к разработчику!</h2>
    ';
}

if ($startday != '' || $actual != '' || $reserv != '') {
    $actual_date = $startday->actual_date;
    include 'remains_table_archive.php';
    $remain_tables_startday .= '
    <h4 class="uk-card-title uk-margin-remove">Дата таблиц: ' . $actual_date . '</h4><hr>
    ';
    include 'remains_table.php';

    //Формирование меню
    $shift_close = '';
    $shift_message = '';
    $shift_status_page = $pages->get('id_point=' . $selected_id_point . '_startday');
    if ($shift_status_page->shift_status == 'Закрыта') {
        $shift_close = 'no-click';
        $shift_message = '<p class="shift_message">● Смена на точке закрыта, доступ к операциям откроется завтра</p>';
    }

    $admin_btn = '';
    if ($access == 'admin') {
        $admin_btn = '
        <a class="menu-link ' . $shift_close . '" href="/obshchaia-kassa-tip-operatcii/">Общая касса</a>
        <a class="menu-link ' . $shift_close . '" href="/dolgi/">Долги</a>
        <a class="menu-link" href="/adminpanel-meniu/">Админ панель</a>
        <a class="menu-link" href="/osnovnoi-otchet/">Отчет</a>
        ';
    }

    $marker = '';
    $open_request = $pages->find('template=close_day_request_itm, product_status=Открыта');
    if (count($open_request) > 0) {
        $marker = '<div class="marker"></div>';
    }

    $menu_btn = '';
    if ($selected_id_point == 'point1') {
        $menu_btn = '
            <a class="menu-link ' . $shift_close . '" href="/skupka-tip-skupki/">Скупка</a>
            <a class="menu-link ' . $shift_close . '" href="/prodazha-tip-prodazhi/">Продажа</a>
            <a class="menu-link ' . $shift_close . '" href="/rezerv-tip-rezerva/">Резерв</a>
            <a class="menu-link ' . $shift_close . '" href="/affinazh-tip-affinazha/">Аффинаж</a>
            <div class="uk-flex">
            <a class="rqfirst menu-link ' . $shift_close . '" href="/zakrytie-smeny-osnovnaia-otkrytye-zaiavki/">Заявки' . $marker . '</a>
            <a class="rqlast menu-link ' . $shift_close . '" href="/zakrytie-smeny-osnovnaia/">Закрытие смены</a>
            </div>
            <a class="menu-link ' . $shift_close . '" href="/kassa-tip-operatcii/">Касса</a>
            <a class="menu-link ' . $shift_close . '" href="" uk-toggle="target: #modal-help">Техподдержка</a>
        ';
        if ($access == 'receiver') {
            $menu_btn = '
            <a class="menu-link ' . $shift_close . '" href="/skupka-tip-skupki/">Скупка</a>
            <a class="menu-link ' . $shift_close . '" href="/kassa-tip-operatcii/">Касса</a>
            <a class="menu-link ' . $shift_close . '" href="" uk-toggle="target: #modal-help">Техподдержка</a>
            ';
        }
        if ($access == 'seller') {
            $menu_btn = '
            <a class="menu-link ' . $shift_close . '" href="/prodazha-izdelie/">Изделия в наличии</a>
            <a class="menu-link ' . $shift_close . '" href="" uk-toggle="target: #modal-help">Техподдержка</a>
            ';
        }
    }
    if ($selected_id_point == 'point2') {
        $menu_btn = '
            <a class="menu-link ' . $shift_close . '" href="/skupka-tip-skupki/">Скупка</a>
            <a class="menu-link ' . $shift_close . '" href="/prodazha-tip-prodazhi/">Продажа</a>
            <a class="menu-link ' . $shift_close . '" href="/zakrytie-smeny/">Закрытие смены</a>
            <a class="menu-link ' . $shift_close . '" href="/kassa-tip-operatcii/">Касса</a>
            <a class="menu-link ' . $shift_close . '" href="" uk-toggle="target: #modal-help">Техподдержка</a>
        ';
        if ($access == 'receiver') {
            $menu_btn = '
            <a class="menu-link ' . $shift_close . '" href="/skupka-tip-skupki/">Скупка</a>
            <a class="menu-link ' . $shift_close . '" href="/kassa-tip-operatcii/">Касса</a>
            <a class="menu-link ' . $shift_close . '" href="" uk-toggle="target: #modal-help">Техподдержка</a>
            ';
        }
        if ($access == 'seller') {
            $menu_btn = '
            <a class="menu-link ' . $shift_close . '" href="/prodazha-izdelie/">Изделия в наличии</a>
            <a class="menu-link ' . $shift_close . '" href="" uk-toggle="target: #modal-help">Техподдержка</a>
            ';
        }
    }
    if ($selected_id_point == 'point3' || $selected_id_point == 'point4' || $selected_id_point == 'point5') {
        $menu_btn = '
            <a class="menu-link ' . $shift_close . '" href="/skupka-tip-skupki/">Скупка</a>
            <a class="menu-link ' . $shift_close . '" href="/zakrytie-smeny/">Закрытие смены</a>
            <a class="menu-link ' . $shift_close . '" href="/kassa-tip-operatcii/">Касса</a>
            <a class="menu-link ' . $shift_close . '" href="" uk-toggle="target: #modal-help">Техподдержка</a>
        ';
        if ($access == 'receiver') {
            $menu_btn = '
            <a class="menu-link ' . $shift_close . '" href="/skupka-tip-skupki/">Скупка</a>
            <a class="menu-link ' . $shift_close . '" href="/kassa-tip-operatcii/">Касса</a>
            <a class="menu-link ' . $shift_close . '" href="" uk-toggle="target: #modal-help">Техподдержка</a>
            ';
        }
        if ($access == 'seller') {
            $menu_btn = '
            <a class="menu-link ' . $shift_close . '" href="/prodazha-izdelie/">Изделия в наличии</a>
            <a class="menu-link ' . $shift_close . '" href="" uk-toggle="target: #modal-help">Техподдержка</a>
            ';
        }
    }

    $menu = '
    <div>
        <div class="pagemenu uk-width-1-1 uk-flex">
            ' . $menu_btn . '
            ' . $admin_btn . '
        </div>
        ' . $shift_message . '
    </div>
    ';
}

?>

<div id="content">
	<h1 class="uk-margin-remove uk-heading-hero uk-text-center">Домашняя страница</h1>
<!--     <h4 class="uk-margin-remove uk-heading-hero uk-text-center">Подзаголовок</h4> -->
	<div>
	    
        <?php echo $menu; ?>
        
        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <?php echo $remain_tables_startday; ?>
            </div>
        </div>

        <!-- Модальное окно техподдержки-->
        <div id="modal-help" uk-modal>
            <div class="uk-modal-dialog uk-modal-body">
                <button class="uk-modal-close-default" type="button" uk-close></button>
                <h2 class="uk-modal-title">Техподдержка</h2>
                <div id="help_messages" class="messages-block">
                    <p class="messages" style="color: green;"></p>
                </div>
                      
                <form class="uk-flex uk-flex-column" id="help_form" action="/tekhpodderzhka/" method="post" enctype="multipart/form-data">        
                    <div class="uk-margin-small-top">
                        <input class="uk-input" id="name_operator" type="text" name="name_operator" value="<?php echo $operator; ?>" placeholder="Имя оператора" autocomplete="off" required>
                    </div>
                    <div class="uk-margin-small-top">
                        <input class="uk-input" id="contact_operator" type="text" name="contact_operator" value="" placeholder="Укажите почту или телефон для связи" required>
                    </div>
                    <div class="uk-margin-small-top">
                        <textarea class="uk-textarea" rows="7" id="message" name="message" value="" placeholder="Опишите проблему" required></textarea>
                    </div>
                    <div class="uk-margin-small-top">
                        <p class="uk-input-label" style="margin:0;">Прикрепите скриншоты при необходимости:</p>
                        <input class="uk-input" id="files" type="file" name="file[]" value="" multiple>
                    </div>
                    <br>
                    <div class="uk-margin-small-top uk-flex uk-flex-column">
                        <button class="uk-margin-small-top uk-button uk-button-default" type="submit">Отправить</button>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>

<?php   
}
?>