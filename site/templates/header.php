<?php namespace ProcessWire;

$today = date("d-m-Y"); 

if(isset($_SESSION['operator'])){
    $operator = $_SESSION['operator'];
} else {
    $operator = 'no_operator';
}

if(isset($_SESSION['point'])){
    $selected_point = $_SESSION['point'];
    if ($selected_point == 'точка 1') {
        $selected_point = 'ул. Ушакова 23';
    }
    if ($selected_point == 'точка 2') {
        $selected_point = 'ул. Пушкина 24';
    }
    if ($selected_point == 'точка 3') {
        $selected_point = 'ул. Московская 35';
    }
} else {
    $selected_point = 'no_point';
}

if(isset($_SESSION['id_point'])){
    $selected_id_point = $_SESSION['id_point'];
} else {
    $selected_id_point = 'no_id_point';
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
            </div>
        </div>

        <a href="/"><i class="fa-solid fa-house"></i></a>
        <p class="uk-margin-remove uk-text-bold ">Дата: ' . $today . '</p>
        <p class="uk-margin-remove uk-text-bold ">Точка: ' . $selected_point . '</p>
        <p class="uk-margin-remove uk-text-bold ">ID точки: ' . $selected_id_point . '</p>
        <p class="uk-margin-remove uk-text-bold ">Сотрудник: ' . $operator . '</p>
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