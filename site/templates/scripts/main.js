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
			let price_gramm = (Math.round(get_price_gramm * 100) / 100).toFixed(2);
			$('#price_gramm').val(price_gramm);
		}
		if (selected_proba == 'Pt') {
			let main_price = $('#main_price_platinum').val();
			let price_gramm = (Math.round(get_price_gramm * 100) / 100).toFixed(2);
			$('#price_gramm').val(price_gramm);
		}
		if (selected_proba == 'Pd') {
			let main_price = $('#main_price_palladium').val();
			let price_gramm = (Math.round(get_price_gramm * 100) / 100).toFixed(2);
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