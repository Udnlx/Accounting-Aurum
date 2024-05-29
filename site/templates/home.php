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
?>

<div id="content">
	<h1 class="uk-heading-hero uk-text-center">Домашняя страница</h1>
	<div class="uk-child-width-1-2@m" uk-grid>
	    
        <div>
            <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
		        <h3 class="uk-card-title">Выберите действие</h3>
		        <a class="uk-margin-small uk-button uk-button-default" href="">Скупка</a>
		        <a class="uk-margin-small uk-button uk-button-default" href="">Продажа</a>
		        <a class="uk-margin-small uk-button uk-button-default" href="">Аффинаж</a>
		        <a class="uk-margin-small uk-button uk-button-default" href="">Админ панель</a>
		    </div>
        </div>
        
        <div>
            <div class="uk-card uk-card-default uk-card-body uk-flex uk-flex-column">
                <h3 class="uk-margin-remove uk-card-title">Таблицы остатков</h3>
            </div>
        </div>
        
    </div>
</div>

<?php   
}
?>