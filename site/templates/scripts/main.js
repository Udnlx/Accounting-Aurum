$(".readonly").keydown(function(e){
    e.preventDefault();
});



$(window).on('load', function() {

	//Получение свободного металла при загрузке страницы по продаже лома
	let url = window.location.pathname;
	if (url == '/prodazha-lom/') {
		let free = $('#free_375').text();
		$('#label_free_for_sale').text('Доступно для продажи: '+ free +' грамм');
	}

});



//Получение цены и свободного металла для продажи при выборе пробы
$('#selected_proba').change( function() {
	let selected_proba = $('#selected_proba option:selected').text();

	if (selected_proba != 'Ag' && selected_proba != 'Pt' && selected_proba != 'Pd') {
		let main_price = $('#main_price_gold').val();
	    let get_price_gramm = (main_price/585)*selected_proba;
	    let price_gramm = (Math.round(get_price_gramm * 100) / 100).toFixed(2);
	    $('#price_gramm').val(price_gramm);
	} else {
		if (selected_proba == 'Ag') {
			let main_price = $('#main_price_silver').val();
			let price_gramm = (Math.round(main_price * 100) / 100).toFixed(2);
			$('#price_gramm').val(price_gramm);
		}
		if (selected_proba == 'Pt') {
			let main_price = $('#main_price_platinum').val();
			let price_gramm = (Math.round(main_price * 100) / 100).toFixed(2);
			$('#price_gramm').val(price_gramm);
		}
		if (selected_proba == 'Pd') {
			let main_price = $('#main_price_palladium').val();
			let price_gramm = (Math.round(main_price * 100) / 100).toFixed(2);
			$('#price_gramm').val(price_gramm);
		}
	}
	$('#selected_weight').val('');
	$('#selected_price').val('');

	let free = $('#free_'+selected_proba).text();
	$('#label_free_for_sale').text('Доступно для продажи: '+ free +' грамм');
});



//Получение цены при вводе веса
$('#selected_weight').bind('input', function(){
	this.value = this.value.replace(/[^0-9\.]/g, '');
	let count = this.value.split(".").length-1;
	if (count > 1) {
		this.value = this.value.substr(0, this.value.lastIndexOf("."));
	}
	if (this.value.indexOf(".") != '-1') {
		this.value = this.value.substring(0, this.value.indexOf(".") + 3);
	} 
	let selected_weight = $('#selected_weight').val();
	let price_gramm = $('#price_gramm').val();
	let selected_price = selected_weight * price_gramm;
	let price = (Math.round(selected_price * 100) / 100).toFixed(2);
	if (!price) {
		price = '';
	}
	$('#selected_price').val(price);
});



//Ввод сколько отдали
$('#selected_pay').bind('input', function(){
	this.value = this.value.replace(/[^0-9\.]/g, '');
	let count = this.value.split(".").length-1;
	if (count > 1) {
		this.value = this.value.substr(0, this.value.lastIndexOf("."));
	}
	if (this.value.indexOf(".") != '-1') {
		this.value = this.value.substring(0, this.value.indexOf(".") + 3);
	}
});



//При выборе вида платежа
$('#cash_card').change( function() {
	let cash_card = $('#cash_card option:selected').text();
	if (cash_card== 'Безналичный расчет') {
		$('#selected_paytype option:contains("Да")').prop('selected', true);
		$('#data_client').removeClass('uk-hidden');
		$('#client_name').attr("required", true);
		$('#client_passport').attr("required", true);
		$('#client_address').attr("required", true);
	}
});



//Квитанция ДА-НЕТ
$('#selected_paytype').change( function() {
	let selected_paytype = $('#selected_paytype option:selected').text();
	if (selected_paytype == 'Да') {
		$('#data_client').removeClass('uk-hidden');
		$('#client_name').attr("required", true);
		$('#client_passport').attr("required", true);
		$('#client_address').attr("required", true);
	} else {
		$('#data_client').addClass('uk-hidden');
		$('#client_name').attr("required", false);
		$('#client_passport').attr("required", false);
		$('#client_address').attr("required", false);
	}
});



//Правка инпута Weight
$('.selected_weight_affinaj').bind('input', function(){
	this.value = this.value.replace(/[^0-9\.]/g, '');
	let count = this.value.split(".").length-1;
	if (count > 1) {
		this.value = this.value.substr(0, this.value.lastIndexOf("."));
	}
	if (this.value.indexOf(".") != '-1') {
		this.value = this.value.substring(0, this.value.indexOf(".") + 3);
	}
});



//Добавление нового аффинажа
$('#reg_new_affinaj').click(function() {
    var p375 = $('#weight_affinaj_375').val();
    var p333 = $('#weight_affinaj_333').val();
    var p417 = $('#weight_affinaj_417').val();
    var p500 = $('#weight_affinaj_500').val();
    var p585 = $('#weight_affinaj_585').val();
    var p620 = $('#weight_affinaj_620').val();
    var p750 = $('#weight_affinaj_750').val();
    var p800 = $('#weight_affinaj_800').val();
    var p850 = $('#weight_affinaj_850').val();
    var p875 = $('#weight_affinaj_875').val();
    var p900 = $('#weight_affinaj_900').val();
    var p916 = $('#weight_affinaj_916').val();
    var p958 = $('#weight_affinaj_958').val();
    var p990 = $('#weight_affinaj_990').val();
    //console.log(p375,p333,p417,p500,p585,p620,p750,p800,p850,p875,p900,p916,p958,p990);
$.ajax({
    type: "POST",
    url: '/add_new_affinaj.php',
    data: {
        'p375':p375, 
        'p333':p333,
        'p417':p417,
        'p500':p500,
        'p585':p585,
        'p620':p620,
        'p750':p750,
        'p800':p800,
        'p850':p850,
        'p875':p875,
        'p900':p900,
        'p916':p916,
        'p958':p958,
        'p990':p990,
    },
    beforeSend: function () {
        $('#result_new_affinaj').html('<p class="messages" style="color: green;">Отправка и обработка данных...</p>');
    },
    success: function (data) {
        $('#result_new_affinaj').html(data);
        let result_add = $('#result_add').text();
        if (result_add == 'Аффинаж зарегестрирован') {
        	$('#reg_new_affinaj').addClass('uk-hidden');
        	$('#weight_affinaj_375').attr("disabled", true);
        	$('#weight_affinaj_333').attr("disabled", true);
        	$('#weight_affinaj_417').attr("disabled", true);
        	$('#weight_affinaj_500').attr("disabled", true);
        	$('#weight_affinaj_585').attr("disabled", true);
        	$('#weight_affinaj_620').attr("disabled", true);
        	$('#weight_affinaj_750').attr("disabled", true);
        	$('#weight_affinaj_800').attr("disabled", true);
        	$('#weight_affinaj_850').attr("disabled", true);
        	$('#weight_affinaj_875').attr("disabled", true);
        	$('#weight_affinaj_900').attr("disabled", true);
        	$('#weight_affinaj_916').attr("disabled", true);
        	$('#weight_affinaj_958').attr("disabled", true);
        	$('#weight_affinaj_990').attr("disabled", true);
        	// var a = 0;
        	// a = a + ($('#weight_affinaj_375').val()/585*375);
        	// a = a + ($('#weight_affinaj_333').val()/585*333);
        	// a = a + ($('#weight_affinaj_417').val()/585*417);
        	// a = a + ($('#weight_affinaj_500').val()/585*500);
        	// a = a + ($('#weight_affinaj_585').val()/585*585);
        	// a = a + ($('#weight_affinaj_620').val()/585*620);
        	// a = a + ($('#weight_affinaj_750').val()/585*750);
        	// a = a + ($('#weight_affinaj_800').val()/585*800);
        	// a = a + ($('#weight_affinaj_850').val()/585*850);
        	// a = a + ($('#weight_affinaj_875').val()/585*875);
        	// a = a + ($('#weight_affinaj_900').val()/585*900);
        	// a = a + ($('#weight_affinaj_916').val()/585*916);
        	// a = a + ($('#weight_affinaj_958').val()/585*958);
        	// a = a + ($('#weight_affinaj_990').val()/585*990);
        	// console.log (a);
        }
        
    },
    error: function (jqXHR, text, error) {
        $('#result_new_affinaj').html(error);
    }
});
return false;    
});
//Добавление нового аффинажа



//Выбор открытого резерва
$('p.reserv_id').click(function() {
    let edited_reserv = $(this).attr('reserv_id');
    let proba_reserv = $(this).attr('proba');
    let weight_reserv = $(this).attr('weight');
    $('#reserv_id').val(edited_reserv);
    $('#selected_proba').val(proba_reserv);
    $('#selected_weight').val(weight_reserv);
});