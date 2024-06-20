$(".readonly").keydown(function(e){
    e.preventDefault();
});



//Получение цены при выборе пробы
$('#selected_proba').change( function() {
	let selected_proba = $('#selected_proba option:selected').text();
	let main_price = $('#main_price').val();
    let get_price_gramm = (main_price/585)*selected_proba;
    let price_gramm = Math.round(get_price_gramm * 100) / 100;
    $('#price_gramm').val(price_gramm);
});