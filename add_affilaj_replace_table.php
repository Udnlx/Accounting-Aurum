<?php

namespace ProcessWire;

require_once 'index.php';





$idpoint = $_POST['idpoint'];

//Формирование таблицы с остатками
$remain_tables_startday = '';
$startday = $pages->get('id_point=' . $idpoint . '_startday');
$actual = $pages->get('id_point=' . $idpoint . '_actual');
$reserv = $pages->get('id_point=' . $idpoint . '_reserv');

if ($startday != '' || $actual != '' || $reserv != '') {
$actual_date = $startday->actual_date;
include 'site/templates/remains_table_archive.php';
$remain_tables_startday .= '<h4 class="uk-card-title uk-margin-remove">Дата таблиц: ' . $actual_date . '</h4><hr>';
}

if ($startday == '' || $actual == '' || $reserv == '') {
    $remain_tables_startday .= '
    <h2 class="uk-margin-remove uk-card-title" style="color:red;font-weight:700;text-align:center;">Произошла ошибка получения остатков!<br>Пожалуйста обратитесь к разработчику!</h2>
    ';
} else {
    include 'site/templates/remains_table.php';
}

echo $remain_tables_startday;