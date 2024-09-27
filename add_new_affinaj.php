<?php

namespace ProcessWire;

require_once 'index.php';





$p375 = $_POST['p375'];
$p333 = $_POST['p333'];
$p417 = $_POST['p417'];
$p500 = $_POST['p500'];
$p585 = $_POST['p585'];
$p620 = $_POST['p620'];
$p750 = $_POST['p750'];
$p800 = $_POST['p800'];
$p850 = $_POST['p850'];
$p875 = $_POST['p875'];
$p900 = $_POST['p900'];
$p916 = $_POST['p916'];
$p958 = $_POST['p958'];
$p990 = $_POST['p990'];

if ($p375 == '' || $p333 == '' || $p417 == '' || $p500 == '' || $p585 == '' || $p620 == '' || $p750 == '' || $p800 == '' || $p850 == '' || $p875 == '' || $p900 == '' || $p916 == '' || $p958 == '' || $p990 == '') {
    echo '<p id="result_add" class="messages" style="color: red;">Ошибка. Аффинаж не зарегестрирован.<br>Проверьте заполненность полей и повторите попытку.</p>';    
} else {
	echo '<p id="result_add" class="messages" style="color: green;">Аффинаж зарегестрирован</p>';
	echo '<p class="messages uk-margin-remove">ID: 0000000</p>';
	echo '<p class="messages uk-margin-remove">Cтатус: Открыт</p>';
	echo '<a class="uk-margin-small uk-button uk-button-default" href="/affinazh-raskhod/">Открытые и отправленные аффинажи</a>';
}