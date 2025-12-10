<?php namespace ProcessWire;

$main_options = $pages->get('template=main_options');
$main_price_gold = number_format($main_options->main_price_gold, 2, '.',' ');

$today = date("d-m-Y"); 

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

$page_cash = $pages->get('template=cash_itm, id_point=' . $selected_id_point . '_cash');
$cash = number_format($page_cash->sum, 2, '.',' ');

$cash_point1 = $pages->get('template=cash_itm, id_point=point1_cash');
$cash_point2 = $pages->get('template=cash_itm, id_point=point2_cash');
$cash_point3 = $pages->get('template=cash_itm, id_point=point3_cash');
$cash_point4 = $pages->get('template=cash_itm, id_point=point4_cash');
$cash_point5 = $pages->get('template=cash_itm, id_point=point5_cash');
$all_page_cash = $pages->get('template=cash_itm, id_point=all_cash');
$total_sum = $all_page_cash->sum + $cash_point1->sum + $cash_point2->sum + $cash_point3->sum + $cash_point4->sum + $cash_point5->sum;

$title = '';
$title .= $cash_point1->title . ' - ' . number_format($cash_point1->sum, 2, '.',' ') . '; ';
$title .= $cash_point2->title . ' - ' . number_format($cash_point2->sum, 2, '.',' ') . '; ';
$title .= $cash_point3->title . ' - ' . number_format($cash_point3->sum, 2, '.',' ') . '; ';
$title .= $cash_point4->title . ' - ' . number_format($cash_point4->sum, 2, '.',' ') . '; ';
$title .= $cash_point5->title . ' - ' . number_format($cash_point5->sum, 2, '.',' ') . '; ';
$title .= $all_page_cash->title . ' - ' . number_format($all_page_cash->sum, 2, '.',' ') . '; ';

$all_cash = '';
$newpoint_menu = '';
if ($access == 'admin') {
    $all_cash = '
    <p class="uk-margin-remove uk-text-bold" style="cursor: help;" title="' . $title . '">В сейфе: ' . number_format($total_sum, 2, '.',' ') . ';</p>
    ';
    $newpoint_menu = '
    <br><br>
    <form class="uk-flex uk-flex-column" id="select_nemwpoint" action="/domashniaia-novaia-tochka/" method="post">
        <div class="uk-margin-small-top">
            <label for="point">Точка</label>
            <select class="new_point_select" id="point" name="point" required>
                <option></option>
                <option value="Тверская 14">Тверская 14</option>
                <option value="Таганка">Таганка</option>
                <option value="Новослободская">Новослободская</option>
                <option value="Митинская 27а">Митинская 27а</option>
            </select>
        </div>
        
        <div class="uk-margin-small-top uk-flex uk-flex-column">
        <button class="new_point_btn" type="submit">Переключиться</button>
        </div>
    </form>
    ';
}

$url = $_SERVER['REQUEST_URI'];
$url = explode('?', $url);
$url = $url[0];
//echo $url;
$menu = '
    <div class="uk-navbar-left">
        <a href="#offcanvas-usage" uk-toggle><i class="fa-solid fa-bars"></i></a>

        <div id="offcanvas-usage" uk-offcanvas>
            <div class="uk-offcanvas-bar">
                <button class="uk-offcanvas-close" type="button" uk-close></button>
                <br>
                <a class="uk-margin-small uk-button uk-button-default" href="/">Домашняя страница</a>
                ' . $newpoint_menu . '
            </div>
        </div>

        <a href="/"><i class="fa-solid fa-house"></i></a>
        <p class="uk-margin-remove uk-text-bold">Дата: ' . $today . ';</p>
        <p class="uk-margin-remove uk-text-bold">Точка: ' . $selected_point . ';</p>
        <p class="uk-margin-remove uk-text-bold">ID точки: ' . $selected_id_point . ';</p>
        <p class="uk-margin-remove uk-text-bold">Сотрудник: ' . $operator . ';</p>
        <p class="uk-margin-remove uk-text-bold">Наличка в точке: ' . $cash . ';</p>
        ' . $all_cash . '
        <a href="/login/?logout" title="Выход из системы"><i class="fa-solid fa-right-from-bracket"></i></a>
    </div>
   ';
if ($url == '/login/') {
   $menu = '';
}

?>

    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Система учета Aurum</title>
        <meta name="Description" content="Программа для ведения учета Aurum">
        
        <link rel="stylesheet" href="<?php echo $config->urls->templates; ?>styles/uikit.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $config->urls->templates; ?>styles/main.css?v=<?php echo uniqid(); ?>">
        
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.1.2/css/all.css">
        
        <script src="<?php echo $config->urls->templates; ?>scripts/uikit.min.js"></script>
    </head>
    <body>
        
        
        
        
    <div class="uk-container">
        <nav class="uk-navbar-container uk-padding-small" uk-navbar>
            <?php echo $menu; ?>
        </nav>
    </div>