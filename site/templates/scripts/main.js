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
$('#selected_weight_affinaj').bind('input', function(){
	this.value = this.value.replace(/[^0-9\.]/g, '');
	let count = this.value.split(".").length-1;
	if (count > 1) {
		this.value = this.value.substr(0, this.value.lastIndexOf("."));
	}
	if (this.value.indexOf(".") != '-1') {
		this.value = this.value.substring(0, this.value.indexOf(".") + 3);
	}
});



//Выбор открытого резерва
$('p.reserv_id').click(function() {
    let edited_reserv = $(this).attr('reserv_id');
    let proba_reserv = $(this).attr('proba');
    let weight_reserv = $(this).attr('weight');
    $('#reserv_id').val(edited_reserv);
    $('#selected_proba').val(proba_reserv);
    $('#selected_weight').val(weight_reserv);
});