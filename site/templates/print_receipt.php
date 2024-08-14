<?php 

$operation_id = !empty($_POST['operation_id'])?$_POST['operation_id']:NULL;  
$type = !empty($_POST['print_type'])?$_POST['print_type']:NULL;  
$undertype = !empty($_POST['print_undertype'])?$_POST['print_undertype']:NULL;  
$date = !empty($_POST['print_date'])?$_POST['print_date']:NULL;
$point = !empty($_POST['print_point'])?$_POST['print_point']:NULL;
$client_name = !empty($_POST['print_client_name'])?$_POST['print_client_name']:NULL;
$client_passport = !empty($_POST['print_client_passport'])?$_POST['print_client_passport']:NULL;
$client_address = !empty($_POST['print_client_address'])?$_POST['print_client_address']:NULL;
$pay = !empty($_POST['print_pay'])?$_POST['print_pay']:NULL;
$proba = !empty($_POST['print_proba'])?$_POST['print_proba']:NULL;
$weight = !empty($_POST['print_weight'])?$_POST['print_weight']:NULL;
$worker = !empty($_POST['print_worker'])?$_POST['print_worker']:NULL;

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
        <h1 class="uk-heading-hero uk-text-center">Распечатка квитанции</h1>
        <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-flex uk-flex-column">
            <h3 class="uk-card-title">Потеряна сессия или точка, перезайти</h3>
            <a class="uk-margin-small uk-button uk-button-default" href="/login/">Перезайти</a>
        </div>
    </div>
<?php   

} else {

$doc = '
<div class="doc__header">
    <div class="doc__header-text">
        <p>ООО "АУРУМ 24", ИНН 9709082673, КПП 770901001</p>
        <p>' . $point . '</p>
        <p style="font-size:20px;font-weight:700;">Скупочная квитанция №' . $operation_id . '</p>
    </div>
    <div class="doc__header-code">
        <div class="doc__header-code-itm">
            <p class="doc__header-code-itm-txt">Вид услуги</p>
            <p class="doc__header-code-itm-type">' . $type . '</p>
        </div>
        <div class="doc__header-code-itm">
            <p class="doc__header-code-itm-txt">Дата приема</p>
            <p class="doc__header-code-itm-date">' . $date . '</p>
        </div>
        <div class="doc__header-code-itm">
            <p class="doc__header-code-itm-txt">ОКПО</p>
            <p class="doc__header-code-itm-okpo">56653079</p>
        </div>
    </div>
</div>

<div class="doc__body">
    <div class="doc__body-itm">
        <p class="doc__body-itm-title">Сдатчик:</p>
        <p class="doc__body-itm-text">' . $client_name . '</p>
    </div>
    <div class="doc__body-itm">
        <p class="doc__body-itm-title"></p>
        <p class="doc__body-itm-text">' . $client_passport . '</p>
    </div>
    <div class="doc__body-itm">
        <p class="doc__body-itm-title">Адрес</p>
        <p class="doc__body-itm-text">' . $client_address . '</p>
    </div>
    <div class="doc__body-itm">
        <p class="doc__body-itm-title">Сумма</p>
        <p class="doc__body-itm-text">' . $pay . '</p>
    </div>
</div>

<div class="doc__table">
    <table class="uk-table uk-table-striped">
        <thead>
            <tr>
                <th width="700">Наименование и описание вещей (драгоценностей)</th>
                <th>Проба</th>
                <th>Вес,  гр.</th>
                <th>Сумма, руб.</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>' . $type . ' - ' . $undertype . '</td>
                <td>' . $proba . '</td>
                <td>' . $weight . '</td>
                <td>' . $pay . '</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="doc__footer">
    <div class="doc__footer-itm">
        <p class="doc__footer-itm-text">С описанием вещей и суммой их оценки согласен.</p>
    </div>
    <div class="doc__footer-itm">
        <p class="doc__footer-itm-text">С использованием персональных данных согласен.</p>
    </div>
    <div class="doc__footer-itm">
        <p class="doc__footer-itm-text" style="font-weight:700;">Изделия,  принятые в скупку возврату не подлежат.</p>
    </div>
    <br>
    <div class="doc__footer-itm" style="width: 47%;">
        <p class="doc__footer-itm-title" style="font-weight:700;">Сдатчик</p>
        <p class="doc__footer-itm-ultext">' . $client_name . '</p>       
    </div>
    <div class="doc__footer-helpitm" style="width: 47%;">
        <p class="doc__footer-itm-helptitle"></p>
        <p class="doc__footer-itm-helptext">(расшифровка подписи)</p>      
        <p class="doc__footer-itm-helptext">(подпись)</p>  
    </div>
    <div class="doc__footer-itm">
        <p class="doc__footer-itm-title">Сумму получил: </p>
        <p class="doc__footer-itm-ultext">' . $pay . '</p>
    </div>
    <br>
    <div class="signatures">
        <div class="signature_itm">
            <div class="doc__footer-itm" style="width: 100%;">
                <p class="doc__footer-itm-title" style="font-weight:700;">Сдатчик</p>
                <p class="doc__footer-itm-ultext">' . $client_name . '</p>       
            </div>
            <div class="doc__footer-helpitm" style="width: 100%">
                <p class="doc__footer-itm-helptitle"></p>
                <p class="doc__footer-itm-helptext">(расшифровка подписи)</p>      
                <p class="doc__footer-itm-helptext">(подпись)</p>  
            </div>
        </div>
        <div class="signature_itm">
            <div class="doc__footer-itm" style="width: 100%;">
                <p class="doc__footer-itm-title" style="font-weight:700;">Кассир</p>
                <p class="doc__footer-itm-ultext"></p>       
            </div>
            <div class="doc__footer-helpitm" style="width: 100%">
                <p class="doc__footer-itm-helptitle">МП</p>
                <p class="doc__footer-itm-helptext">(расшифровка подписи)</p>      
                <p class="doc__footer-itm-helptext">(подпись)</p>  
            </div>
        </div>
    </div>
</div>
<br>
<p>--------------------------------------------------------------------------------------------------------------------------------------------</p>
<br>
<div class="doc__header">
    <div class="doc__header-text">
        <p>ООО "АУРУМ 24", ИНН 9709082673, КПП 770901001</p>
        <p>' . $point . '</p>
        <p style="font-size:20px;font-weight:700;">Скупочная квитанция №' . $operation_id . '</p>
    </div>
    <div class="doc__header-code">
        <div class="doc__header-code-itm">
            <p class="doc__header-code-itm-txt">Вид услуги</p>
            <p class="doc__header-code-itm-type">' . $type . '</p>
        </div>
        <div class="doc__header-code-itm">
            <p class="doc__header-code-itm-txt">Дата приема</p>
            <p class="doc__header-code-itm-date">' . $date . '</p>
        </div>
        <div class="doc__header-code-itm">
            <p class="doc__header-code-itm-txt">ОКПО</p>
            <p class="doc__header-code-itm-okpo">56653079</p>
        </div>
    </div>
</div>

<div class="doc__body">
    <div class="doc__body-itm">
        <p class="doc__body-itm-title">Сдатчик:</p>
        <p class="doc__body-itm-text">' . $client_name . '</p>
    </div>
    <div class="doc__body-itm">
        <p class="doc__body-itm-title"></p>
        <p class="doc__body-itm-text">' . $client_passport . '</p>
    </div>
    <div class="doc__body-itm">
        <p class="doc__body-itm-title">Адрес</p>
        <p class="doc__body-itm-text">' . $client_address . '</p>
    </div>
    <div class="doc__body-itm">
        <p class="doc__body-itm-title">Сумма</p>
        <p class="doc__body-itm-text">' . $pay . '</p>
    </div>
</div>

<div class="doc__table">
    <table class="uk-table uk-table-striped">
        <thead>
            <tr>
                <th width="700">Наименование и описание вещей (драгоценностей)</th>
                <th>Проба</th>
                <th>Вес,  гр.</th>
                <th>Сумма, руб.</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>' . $type . ' - ' . $undertype . '</td>
                <td>' . $proba . '</td>
                <td>' . $weight . '</td>
                <td>' . $pay . '</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="doc__footer">
    <div class="doc__footer-itm">
        <p class="doc__footer-itm-text">С описанием вещей и суммой их оценки согласен.</p>
    </div>
    <div class="doc__footer-itm">
        <p class="doc__footer-itm-text">С использованием персональных данных согласен.</p>
    </div>
    <div class="doc__footer-itm">
        <p class="doc__footer-itm-text" style="font-weight:700;">Изделия,  принятые в скупку возврату не подлежат.</p>
    </div>
    <br>
    <div class="doc__footer-itm" style="width: 47%;">
        <p class="doc__footer-itm-title" style="font-weight:700;">Сдатчик</p>
        <p class="doc__footer-itm-ultext">' . $worker . '</p>       
    </div>
    <div class="doc__footer-helpitm" style="width: 47%;">
        <p class="doc__footer-itm-helptitle"></p>
        <p class="doc__footer-itm-helptext">(расшифровка подписи)</p>      
        <p class="doc__footer-itm-helptext">(подпись)</p>  
    </div>
    <div class="doc__footer-itm">
        <p class="doc__footer-itm-title">Сумму получил: </p>
        <p class="doc__footer-itm-ultext">' . $pay . '</p>
    </div>
    <br>
    <div class="signatures">
        <div class="signature_itm">
            <div class="doc__footer-itm" style="width: 100%;">
                <p class="doc__footer-itm-title" style="font-weight:700;">Сдатчик</p>
                <p class="doc__footer-itm-ultext">' . $worker . '</p>       
            </div>
            <div class="doc__footer-helpitm" style="width: 100%">
                <p class="doc__footer-itm-helptitle"></p>
                <p class="doc__footer-itm-helptext">(расшифровка подписи)</p>      
                <p class="doc__footer-itm-helptext">(подпись)</p>  
            </div>
        </div>
        <div class="signature_itm">
            <div class="doc__footer-itm" style="width: 100%;">
                <p class="doc__footer-itm-title" style="font-weight:700;">Кассир</p>
                <p class="doc__footer-itm-ultext"></p>       
            </div>
            <div class="doc__footer-helpitm" style="width: 100%">
                <p class="doc__footer-itm-helptitle">МП</p>
                <p class="doc__footer-itm-helptext">(расшифровка подписи)</p>      
                <p class="doc__footer-itm-helptext">(подпись)</p>  
            </div>
        </div>
    </div>
</div>
';

//echo $doc;

$receipt = '
<style type="text/css">
* {
  /*font-family: Helvetica, sans-serif;*/
  font-family: "DejaVu Sans", sans-serif;
  font-size: 13px;}
tr:nth-child(even) {
    background: #e1e1e1;}

.doc__header {
    display: flex;
    justify-content: space-between;}
.doc__header-text {
    width: 80%;}
.doc__header-text p {
    margin: 0;
    font-size: 17px;
    color: #000000;}
.doc__header-code {
    width: 20%;}
.doc__header-code-itm {
    display: flex;
    align-items: center;
    justify-content: end;}
.doc__header-code-itm p.doc__header-code-itm-txt {
    margin: 0 10px 0 0;
    font-size: 17px;
    color: #000000;}
.doc__header-code-itm p.doc__header-code-itm-type {
    width: 100px;
    margin: 0;
    padding: 3px 10px;
    font-size: 17px;
    color: #000000;
    border-top: 3px solid #000000;
    border-right: 3px solid #000000;
    border-left: 3px solid #000000;
    text-align: center;}
.doc__header-code-itm p.doc__header-code-itm-date {
    width: 100px;
    margin: 0;
    padding: 3px 10px;
    font-size: 17px;
    color: #000000;
    border-top: 1px solid #000000;
    border-right: 3px solid #000000;
    border-left: 3px solid #000000;
    text-align: center;}
.doc__header-code-itm p.doc__header-code-itm-okpo {
    width: 100px;
    margin: 0;
    padding: 3px 10px;
    font-size: 17px;
    color: #000000;
    border-top: 3px solid #000000;
    border-right: 3px solid #000000;
    border-left: 3px solid #000000;
    border-bottom: 3px solid #000000;
    text-align: center;}

.doc__body {
    display: flex;
    flex-direction: column;
    margin: 20px 0 0 0;}
.doc__body-itm {
    display: flex;}
.doc__body-itm p.doc__body-itm-title {
    margin: 0;
    font-size: 17px;
    color: #000000;
    min-width: 150px;}
.doc__body-itm p.doc__body-itm-text {
    margin: 0;
    font-size: 17px;
    color: #000000;}

.doc__table table {
    margin: 20px 0 0 0;
    border: 3px solid #000000;
    font-size: 17px;}

.doc__footer {
    display: flex;
    flex-direction: column;
    margin: 20px 0 0 0;}
.doc__footer-itm {
    display: flex;}
.doc__footer-itm p.doc__footer-itm-title {
    margin: 0;
    font-size: 17px;
    color: #000000;
    min-width: 150px;}
.doc__footer-itm p.doc__footer-itm-text {
    margin: 0;
    font-size: 17px;
    color: #000000;}
.doc__footer-itm p.doc__footer-itm-ultext {
    padding: 0 10px;
    width: 100%;
    border-bottom: 1px solid #000000;
    margin: 0;
    font-size: 17px;
    color: #000000;}
.doc__footer-helpitm {
    display: flex;
    justify-content: space-between;}
.doc__footer-helpitm p.doc__footer-itm-helptitle {
    margin: 0;
    font-size: 17px;
    color: #000000;
    min-width: 150px;}
.doc__footer-helpitm p.doc__footer-itm-helptext {
    padding: 0 10px;
    margin: 0 0 7px 0;
    font-size: 14px;
    color: #000000;}
.signatures {
    display: flex;
    justify-content: space-between;}
.signature_itm {
    width: 47%;}
</style>';
$receipt .= $doc;

//echo $receipt;

include_once __DIR__ . '/dompdf/autoload.inc.php';
$dompdf = new Dompdf\Dompdf();
$dompdf->set_option('isRemoteEnabled', TRUE);
//$dompdf->setPaper('A4', 'portrait');
$dompdf->setPaper('A4', 'landscape');
$dompdf->loadHtml($receipt, 'UTF-8');
$dompdf->render();
 
// Вывод файла в браузер:
//$dompdf->stream('Квитанция - ' . $date . ' - ' . $client_name . ''); 
?>

<div id="content">
    <h1 class="uk-margin-remove uk-heading-hero uk-text-center">Распечатка квитанции</h1>
    <div>

        <div>
            <div class="pagemenu uk-width-1-1 uk-flex">
                <a class="menu-link" href="/">На главную</a>
            </div>
        </div>

        <div>
            <h3 class="uk-card-title">Данные квитанции</h3>
            <p class="uk-margin-remove">Тип: <span style="font-weight: 700;"><?php echo $type; ?></span></p>
            <p class="uk-margin-remove">Вид: <span style="font-weight: 700;"><?php echo $undertype; ?></span></p>
            <p class="uk-margin-remove">Дата: <span style="font-weight: 700;"><?php echo $date; ?></span></p>
            <p class="uk-margin-remove">Точка: <span style="font-weight: 700;"><?php echo $point; ?></span></p>
            <p class="uk-margin-remove">Сотрудник: <span style="font-weight: 700;"><?php echo $worker; ?></span></p>
            <br>
            <p class="uk-margin-remove">Проба: <span style="font-weight: 700;"><?php echo $proba; ?></span></p>
            <p class="uk-margin-remove">Вес: <span style="font-weight: 700;"><?php echo $weight; ?></span></p>
            <p class="uk-margin-remove">Стоимость: <span style="font-weight: 700;"><?php echo $pay; ?></span></p>
            <p class="uk-margin-remove">ФИО клиента: <span style="font-weight: 700;"><?php echo $client_name; ?></span></p>
            <p class="uk-margin-remove">Паспорт клиента: <span style="font-weight: 700;"><?php echo $client_passport; ?></span></p>
            <p class="uk-margin-remove">Адрес клиента: <span style="font-weight: 700;"><?php echo $client_address; ?></span></p>
        </div>

        <br><br>
        <?php echo $doc; ?>
        
    </div>
</div>

<?php   
}
?>