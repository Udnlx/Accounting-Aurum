<?php namespace ProcessWire;

if(isset($_GET['logout'])) {
    session_unset();
}

$login = 'off';
$_SESSION['operator'] = 'no_operator';
//echo $_SESSION['operator'];

$point = !empty($_POST['point'])?$_POST['point']:NULL;  
$id_p = '';
if ($point == 'Тверская 20') {
    $id_p = 'point1';
}
if ($point == 'Тверская 14') {
    $id_p = 'point2';
}
if ($point == 'Таганка') {
    $id_p = 'point3';
}
if ($point == 'Комсомолка') {
    $id_p = 'point4';
}
if ($point == 'Митинская 27а') {
    $id_p = 'point5';
}
$user_login = !empty($_POST['user_login'])?$_POST['user_login']:NULL;  
$user_password = !empty($_POST['user_password'])?$_POST['user_password']:NULL;

$all_users_folder = $pages->get('template=users_system');
if (count($all_users_folder->children()) > 0) {
    $all_users = $all_users_folder->children();
    foreach ($all_users as $user) {
        if($user_login == $user->title && $user_password == $user->password) {
            $login = 'on';
            $_SESSION['point'] = $point;
            $_SESSION['id_point'] = $id_p;
            $_SESSION['operator'] = $user->title;
            $_SESSION['access'] = $user->access->title;
        }
    }
}

// if($user_login === 'admin' && $user_password === 'asdasd') {
//     $login = 'on';
//     $_SESSION['point'] = $point;
//     $_SESSION['id_point'] = $id_p;
//     $_SESSION['operator'] = 'admin';
//     $_SESSION['access'] = 'admin';
// }

//echo $login;

$content = '';
if ($login == 'on') {
    $content = '
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Добро пожаловать '. $_SESSION['operator'] .'</h1>
    	
    	<div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <div class="uk-margin-small-top uk-flex uk-flex-column">
                <a class="uk-margin-small-top uk-button uk-button-default" href="/">Перейти на главную</a>
                <a class="uk-margin-small-top uk-button uk-button-default" href="?logout">Выход</a>
            </div>
        </div>
    </div>
    ';
} else {
    $content = '
    <div id="content" style="max-width: 700px;">
    	<h1 class="uk-heading-hero uk-text-center">Вход</h1>
    	
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <form class="uk-flex uk-flex-column" id="select_bus" action="/login/" method="post">
                <div class="uk-margin-small-top">
                    <label for="point">Точка</label>
                    <select class="uk-select" id="point" name="point" required>
                        <option></option>
                        <option value="Тверская 20">Тверская 20</option>
                        <option value="Тверская 14">Тверская 14</option>
                        <option value="Таганка">Таганка</option>
                        <option value="Комсомолка">Комсомолка</option>
                        <option value="Митинская 27а">Митинская 27а</option>
                    </select>
                </div>
                <div class="uk-margin-small-top">
                    <input class="uk-input" id="user_login" type="text" name="user_login" placeholder="Логин" required>
                </div>
                <div class="uk-margin-small-top">
                    <input class="uk-input" id="user_password" type="password" name="user_password" placeholder="Пароль" required>
                </div>
                
                <div class="uk-margin-small-top uk-flex uk-flex-column">
                <button class="uk-margin-small-top uk-button uk-button-default" type="submit">Войти</button>
                </div>
            </form>
        </div>
    </div>
    ';
}

echo $content;

?>