<?php

namespace ProcessWire;

require_once 'index.php';

?>

<!doctype html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Изменение цен лома в системе учета AurumERP</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
</head>

<body>
    
<style>
.title {
	width: 1200px;
	padding: 20px;
	margin: auto;
	text-align: center;}
p.text-success {
	color: green;
	font-size: 15px;
	font-weight: 700;}
p.text-error {
	color: red;
	font-size: 15px;
	font-weight: 700;}
.form {
	width: 1200px;
	padding: 20px;
	margin: auto;
    text-align: center;}
.form label {
	font-size: 15px;
	font-weight: 700;}

@media (max-width: 1200px) {
.title {
width: 100%;}
.form {
width: 100%;}
}
</style>
    
    

<div class="title">
	<h1 class="title-text">ИЗМЕНЕНИЕ ЦЕН ЛОМА В СИСТЕМЕ УЧЕТА AURUM-ERP</h1>
</div>



<div class="form">
    
<?php
$gPrice = $_GET['gPrice'];
$sPrice = $_GET['sPrice'];
$platPrice = $_GET['platPrice'];
$palladPrice = $_GET['palladPrice'];

if (isset($gPrice)) { 
    echo '<p style="margin: 0; color: blue; font-weight: bold;">ПРИШЕДШАЯ ЦЕНА НА ЗОЛОТО - ' . $gPrice . '</p><br>';
} else {
    echo '<p style="margin: 0; color: red; font-weight: bold;">ОШИБКА! НЕТ ЦЕНЫ НА ЗОЛОТО' . '</p><br>';
    die ();
}
if (isset($sPrice)) { 
    echo '<p style="margin: 0; color: blue; font-weight: bold;">ПРИШЕДШАЯ ЦЕНА НА СЕРЕБРО - ' . $sPrice . '</p><br>';
} else {
    echo '<p style="margin: 0; color: red; font-weight: bold;">ОШИБКА! НЕТ ЦЕНЫ НА СЕРЕБРО' . '</p><br>';
    die ();
}
if (isset($platPrice)) { 
    echo '<p style="margin: 0; color: blue; font-weight: bold;">ПРИШЕДШАЯ ЦЕНА НА ПЛАТИНУ - ' . $platPrice . '</p><br>';
} else {
    echo '<p style="margin: 0; color: red; font-weight: bold;">ОШИБКА! НЕТ ЦЕНЫ НА ПЛАТИНУ' . '</p><br>';
    die ();
}
if (isset($palladPrice)) { 
    echo '<p style="margin: 0; color: blue; font-weight: bold;">ПРИШЕДШАЯ ЦЕНА НА ПАЛЛАДИЙ - ' . $palladPrice . '</p><br>';
} else {
    echo '<p style="margin: 0; color: red; font-weight: bold;">ОШИБКА! НЕТ ЦЕНЫ НА ПАЛЛАДИЙ' . '</p><br>';
    die ();
}

if ($gPrice <= 0 || $sPrice <= 0 || $platPrice <= 0 || $palladPrice <= 0) {
	echo '<p class="text-error">Обновление цен не прошло,<br> в одном из полей было обноруженно нулевое или отрицательное значение!</p>';
} else {
	$main_price_gold = $gPrice;
	$main_price_gold_999 = $gPrice;
	$main_price_silver = $sPrice;
	$main_price_platinum = $platPrice;
	$main_price_palladium = $palladPrice;

	//Меняем данные
    $edit_page = $main_options = $pages->get('template=main_options');
    $edit_page->of(false);
    $edit_page->main_price_gold = $main_price_gold;
    $edit_page->main_price_gold_999 = $main_price_gold_999;
    $edit_page->main_price_silver = $main_price_silver;
    $edit_page->main_price_platinum = $main_price_platinum;
    $edit_page->main_price_palladium = $main_price_palladium;
    $edit_page->save();

    //Записываем регистрацию  в лог
    $log = '';
    $log .= date("Y-m-d H:i") . ' Были внесены изменения в настройки через zot.moscow . ';
    file_put_contents(__DIR__ . '/site/templates/log_admin_setup.txt', $log . PHP_EOL, FILE_APPEND);
    $log = 'Цена на золото: ' . $main_price_gold;
    file_put_contents(__DIR__ . '/site/templates/log_admin_setup.txt', $log . PHP_EOL, FILE_APPEND);
    $log = 'Цена на золото 999 пробы: ' . $main_price_gold_999;
    file_put_contents(__DIR__ . '/site/templates/log_admin_setup.txt', $log . PHP_EOL, FILE_APPEND);
    $log = 'Цена на серебро: ' . $main_price_silver;
    file_put_contents(__DIR__ . '/site/templates/log_admin_setup.txt', $log . PHP_EOL, FILE_APPEND);
    $log = 'Цена на платину: ' . $main_price_platinum;
    file_put_contents(__DIR__ . '/site/templates/log_admin_setup.txt', $log . PHP_EOL, FILE_APPEND);
    $log = 'Цена на палладий: ' . $main_price_palladium;
    file_put_contents(__DIR__ . '/site/templates/log_admin_setup.txt', $log . PHP_EOL, FILE_APPEND);

	echo '<p class="text-success">Обновление цен прошло успешно!</p>';
	// echo '<p class="text-success">Функционал в разработке, изменения не применяются!</p>';
}
?>

<a type="button" class="btn btn-warning" href="https://zot.moscow/change_price_page.php">Назад<a>
</div>



</body>
</html>