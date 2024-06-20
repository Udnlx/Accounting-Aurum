$(".readonly").keydown(function(e){
    e.preventDefault();
});



//Получение цены при выборе пробы
$('#selected_proba').change( function() {
	let selected_proba = $('#selected_proba option:selected').text();

	if (selected_proba != 'Ag' && selected_proba != 'Pt' && selected_proba != 'Pd') {
		let main_price = $('#main_price_gold').val();
	    let get_price_gramm = (main_price/585)*selected_proba;
	    let price_gramm = Math.round(get_price_gramm * 100) / 100;
	    $('#price_gramm').val(price_gramm);
	} else {
		if (selected_proba == 'Ag') {
			let main_price = $('#main_price_silver').val();
			let price_gramm = Math.round(main_price * 100) / 100;
			$('#price_gramm').val(price_gramm);
		}
		if (selected_proba == 'Pt') {
			let main_price = $('#main_price_platinum').val();
			let price_gramm = Math.round(main_price * 100) / 100;
			$('#price_gramm').val(price_gramm);
		}
		if (selected_proba == 'Pd') {
			let main_price = $('#main_price_palladium').val();
			let price_gramm = Math.round(main_price * 100) / 100;
			$('#price_gramm').val(price_gramm);
		}
	}
});