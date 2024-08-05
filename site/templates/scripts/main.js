$(".readonly").keydown(function(e){
    e.preventDefault();
});



//Получение цены при выборе пробы
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



//Набор и удаление аффинажа
$('button.add-affilaj').click(function() {
	if ($('#selected_weight_affinaj').val() == '') {
		alert("Укажите вес");
	} else {
		let proba = $('#selected_proba_affinaj').val();
		let weight = $('#selected_weight_affinaj').val();
		$('#all_affinaj').append('<div class="position_affinaj"><p class="remove_affinaj_itm">удалить</p><p class="affinaj_itm" proba="' + proba + '" weight="' + weight + '">' + proba + ' - ' + weight + 'г.</p></div>');
	}
	if( $('#all_affinaj').is(':empty')) {
		$('#affilaj-reg-btn').addClass('uk-hidden');
	} else {
		$('#affilaj-reg-btn').removeClass('uk-hidden');
	}
});

$(document).on("click", "p.remove_affinaj_itm", function(){
	let removeItm = $(this).parent();
	removeItm.remove();
	if( $('#all_affinaj').is(':empty')) {
		$('#affilaj-reg-btn').addClass('uk-hidden');
	}
});



//Регистрация аффинажа
$('#affilaj-reg-btn').click(function() {
	var date = $('#selected_date').val();
	var point = $('#selected_point').val();
	var idpoint = $('#selected_idpoint').val();
	var worker = $('#selected_worker').val();
	var arr = [];
	$(document).find('p.affinaj_itm').each(function (){
        let proba = $(this).attr('proba');
        let weight = $(this).attr('weight');
        arr.push(proba + ',' + weight);
    })
    //console.log(date, point, idpoint, worker, arr);
$.ajax({
    type: "POST",
    url: '/add_affilaj.php',
    data: {
        'date':date, 
        'point':point, 
        'idpoint':idpoint,
        'worker':worker,
        'arr':arr
    },
    beforeSend: function () {
        $('#edit_messages').html('<p class="messages" style="color: green;">Отправка и обработка данных...</p>');
    },
    success: function (data) {
        $('#edit_messages').html(data);
    },
    error: function (jqXHR, text, error) {
        $('#edit_messages').html(error);
    }
});
$('.add-affilaj').prop('disabled', true);
$('#affilaj-reg-btn').prop('disabled', true);
$('.remove_affinaj_itm').prop('disabled', true);
$('.remove_affinaj_itm').addClass('disabled_remove');
return false;  
});
//Регистрация аффинажа