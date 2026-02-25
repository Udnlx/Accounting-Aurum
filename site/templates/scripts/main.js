$(".readonly").keydown(function(e){
    e.preventDefault();
});



$(window).on('load', function() {

	let url = window.location.pathname;

	//Получение свободного металла при загрузке страницы по продаже лома
	if (url == '/prodazha-lom/') {
		let free = $('#free_585').text();
		$('#label_free_for_sale').text('Доступно для продажи: '+ free +' грамм');
	}

	//Установка нужной пробы и веса, цены за грам и общей цены при закрытии резерва с операцией продажи
	if (url == '/rezerv-operatciia/') {
		let proba = $('#proba').text();
		// $('#selected_proba option:contains("'+proba+'")').prop('selected', true);
		$('#selected_proba option').filter(function() {
		    return $.trim($(this).text()) === proba;
		}).prop('selected', true).parent().change();

		let selected_proba = $('#selected_proba option:selected').text();
		if (selected_proba != 'Ag' && selected_proba != 'Ag-800' && selected_proba != 'Ag-875' && selected_proba != 'Ag-925' && selected_proba != 'Ag-999' && selected_proba != 'Pt' && selected_proba != 'Pd' && selected_proba != '999') {
			let main_price = $('#main_price_gold').val();
		    let get_price_gramm = (main_price/585)*selected_proba;
		    let price_gramm = (Math.round(get_price_gramm * 100) / 100).toFixed(2);
		    let percent = price_gramm*3/100;
		    let min_price = (Math.round(price_gramm - percent)).toFixed(2);
		    $('#price_gramm').val(price_gramm);
		    $('#base_price').text(price_gramm);
		    $('#min_price').text(min_price);
		} else {
			if (selected_proba == '999') {
				let main_price = $('#main_price_gold_999').val();
				let price_gramm = (Math.round(main_price * 100) / 100).toFixed(2);
				let percent = price_gramm*3/100;
		    	let min_price = (Math.round(price_gramm - percent)).toFixed(2);
				$('#price_gramm').val(price_gramm);
				$('#base_price').text(price_gramm);
		    	$('#min_price').text(min_price);
			}
			if (selected_proba == 'Ag') {
				let main_price = $('#main_price_silver').val();
				let price_gramm = (Math.round(main_price * 100) / 100).toFixed(2);
				let percent = price_gramm*3/100;
		    	let min_price = (Math.round(price_gramm - percent)).toFixed(2);
				$('#price_gramm').val(price_gramm);
				$('#base_price').text(price_gramm);
		    	$('#min_price').text(min_price);
			}
			if (selected_proba == 'Ag-800') {
				let main_price = $('#main_price_silver').val();
				let get_price_gramm = (main_price/925)*800;
				let price_gramm = (Math.round(get_price_gramm * 100) / 100).toFixed(2);
				let percent = price_gramm*3/100;
		    	let min_price = (Math.round(price_gramm - percent)).toFixed(2);
				$('#price_gramm').val(price_gramm);
				$('#base_price').text(price_gramm);
		    	$('#min_price').text(min_price);
			}
			if (selected_proba == 'Ag-875') {
				let main_price = $('#main_price_silver').val();
				let get_price_gramm = (main_price/925)*875;
				let price_gramm = (Math.round(get_price_gramm * 100) / 100).toFixed(2);
				let percent = price_gramm*3/100;
		    	let min_price = (Math.round(price_gramm - percent)).toFixed(2);
				$('#price_gramm').val(price_gramm);
				$('#base_price').text(price_gramm);
		    	$('#min_price').text(min_price);
			}
			if (selected_proba == 'Ag-925') {
				let main_price = $('#main_price_silver').val();
				let get_price_gramm = (main_price/925)*925;
				let price_gramm = (Math.round(get_price_gramm * 100) / 100).toFixed(2);
				let percent = price_gramm*3/100;
		    	let min_price = (Math.round(price_gramm - percent)).toFixed(2);
				$('#price_gramm').val(price_gramm);
				$('#base_price').text(price_gramm);
		    	$('#min_price').text(min_price);
			}
			if (selected_proba == 'Ag-999') {
				let main_price = $('#main_price_silver').val();
				let get_price_gramm = (main_price/925)*999;
				let price_gramm = (Math.round(get_price_gramm * 100) / 100).toFixed(2);
				let percent = price_gramm*3/100;
		    	let min_price = (Math.round(price_gramm - percent)).toFixed(2);
				$('#price_gramm').val(price_gramm);
				$('#base_price').text(price_gramm);
		    	$('#min_price').text(min_price);
			}
			if (selected_proba == 'Pt') {
				let main_price = $('#main_price_platinum').val();
				let price_gramm = (Math.round(main_price * 100) / 100).toFixed(2);
				let percent = price_gramm*3/100;
		    	let min_price = (Math.round(price_gramm - percent)).toFixed(2);
				$('#price_gramm').val(price_gramm);
				$('#base_price').text(price_gramm);
		    	$('#min_price').text(min_price);
			}
			if (selected_proba == 'Pd') {
				let main_price = $('#main_price_palladium').val();
				let price_gramm = (Math.round(main_price * 100) / 100).toFixed(2);
				let percent = price_gramm*3/100;
		    	let min_price = (Math.round(price_gramm - percent)).toFixed(2);
				$('#price_gramm').val(price_gramm);
				$('#base_price').text(price_gramm);
		    	$('#min_price').text(min_price);
			}
		}

		let weight = $('#weight').text();
		let price_gramm = $('#price_gramm').val();
		let selected_price = weight * price_gramm;
		let price = (Math.round(selected_price * 100) / 100).toFixed(2);
		if (!price) {
			price = '';
		}
		$('#selected_weight').val(weight);
		$('#selected_price').val(price);
	}

	//Установка нужной пробы при внесение правок в операцию
	if (url == '/pravka-operatcii-forma/') {
		let proba = $('#proba').text();
		// $('#new_selected_proba option:contains("'+proba+'")').prop('selected', true);
		$('#new_selected_proba option').filter(function() {
		    return $.trim($(this).text()) === proba;
		}).prop('selected', true).parent().change();
	}

	//Установка параметров в поиске по операциям
	if (url == '/adminpanel-vse-operatcii-rezul-tat-poiska/') {
		let f_point = $('#post_point').text();
		let f_worker = $('#post_worker').text();
		let f_proba = $('#post_proba').text();
		$('#f_point option:contains("'+f_point+'")').prop('selected',true);
		$('#f_worker option:contains("'+f_worker+'")').prop('selected', true);
		$('#f_proba option:contains("'+f_proba+'")').prop('selected', true);
	}

});



//Получение цены и свободного металла для продажи при выборе пробы
$('#selected_proba').change( function() {
	let selected_proba = $('#selected_proba option:selected').text();

	if (selected_proba != 'Ag' && selected_proba != 'Ag-800' && selected_proba != 'Ag-875' && selected_proba != 'Ag-925' && selected_proba != 'Ag-999' && selected_proba != 'Pt' && selected_proba != 'Pd' && selected_proba != '999') {
		let main_price = $('#main_price_gold').val();
	    let get_price_gramm = (main_price/585)*selected_proba;
	    let price_gramm = (Math.round(get_price_gramm * 100) / 100).toFixed(2);
	    let percent = price_gramm*3/100;
	    let min_price = (Math.round(price_gramm - percent)).toFixed(2);
	    $('#price_gramm').val(price_gramm);
	    $('#base_price').text(price_gramm);
	    $('#min_price').text(min_price);
	} else {
		if (selected_proba == '999') {
			let main_price = $('#main_price_gold_999').val();
			let price_gramm = (Math.round(main_price * 100) / 100).toFixed(2);
			let percent = price_gramm*3/100;
	    	let min_price = (Math.round(price_gramm - percent)).toFixed(2);
			$('#price_gramm').val(price_gramm);
			$('#base_price').text(price_gramm);
	    	$('#min_price').text(min_price);
		}
		if (selected_proba == 'Ag') {
			let main_price = $('#main_price_silver').val();
			let price_gramm = (Math.round(main_price * 100) / 100).toFixed(2);
			let percent = price_gramm*3/100;
	    	let min_price = (Math.round(price_gramm - percent)).toFixed(2);
			$('#price_gramm').val(price_gramm);
			$('#base_price').text(price_gramm);
	    	$('#min_price').text(min_price);
		}
		if (selected_proba == 'Ag-800') {
			let main_price = $('#main_price_silver').val();
			let get_price_gramm = (main_price/925)*800;
			let price_gramm = (Math.round(get_price_gramm * 100) / 100).toFixed(2);
			let percent = price_gramm*3/100;
	    	let min_price = (Math.round(price_gramm - percent)).toFixed(2);
			$('#price_gramm').val(price_gramm);
			$('#base_price').text(price_gramm);
	    	$('#min_price').text(min_price);
		}
		if (selected_proba == 'Ag-875') {
			let main_price = $('#main_price_silver').val();
			let get_price_gramm = (main_price/925)*875;
			let price_gramm = (Math.round(get_price_gramm * 100) / 100).toFixed(2);
			let percent = price_gramm*3/100;
	    	let min_price = (Math.round(price_gramm - percent)).toFixed(2);
			$('#price_gramm').val(price_gramm);
			$('#base_price').text(price_gramm);
	    	$('#min_price').text(min_price);
		}
		if (selected_proba == 'Ag-925') {
			let main_price = $('#main_price_silver').val();
			let get_price_gramm = (main_price/925)*925;
			let price_gramm = (Math.round(get_price_gramm * 100) / 100).toFixed(2);
			let percent = price_gramm*3/100;
	    	let min_price = (Math.round(price_gramm - percent)).toFixed(2);
			$('#price_gramm').val(price_gramm);
			$('#base_price').text(price_gramm);
	    	$('#min_price').text(min_price);
		}
		if (selected_proba == 'Ag-999') {
			let main_price = $('#main_price_silver').val();
			let get_price_gramm = (main_price/925)*999;
			let price_gramm = (Math.round(get_price_gramm * 100) / 100).toFixed(2);
			let percent = price_gramm*3/100;
	    	let min_price = (Math.round(price_gramm - percent)).toFixed(2);
			$('#price_gramm').val(price_gramm);
			$('#base_price').text(price_gramm);
	    	$('#min_price').text(min_price);
		}
		if (selected_proba == 'Pt') {
			let main_price = $('#main_price_platinum').val();
			let price_gramm = (Math.round(main_price * 100) / 100).toFixed(2);
			let percent = price_gramm*3/100;
	    	let min_price = (Math.round(price_gramm - percent)).toFixed(2);
			$('#price_gramm').val(price_gramm);
			$('#base_price').text(price_gramm);
	    	$('#min_price').text(min_price);
		}
		if (selected_proba == 'Pd') {
			let main_price = $('#main_price_palladium').val();
			let price_gramm = (Math.round(main_price * 100) / 100).toFixed(2);
			let percent = price_gramm*3/100;
	    	let min_price = (Math.round(price_gramm - percent)).toFixed(2);
			$('#price_gramm').val(price_gramm);
			$('#base_price').text(price_gramm);
	    	$('#min_price').text(min_price);
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

//Ввод цена за грамм
$('#price_gramm').bind('input', function(){
	this.value = this.value.replace(/[^0-9\.]/g, '');
	let count = this.value.split(".").length-1;
	if (count > 1) {
		this.value = this.value.substr(0, this.value.lastIndexOf("."));
	}
	if (this.value.indexOf(".") != '-1') {
		this.value = this.value.substring(0, this.value.indexOf(".") + 3);
	}
});

//Ввод стоимость
$('#selected_price').bind('input', function(){
	this.value = this.value.replace(/[^0-9\.]/g, '');
	let count = this.value.split(".").length-1;
	if (count > 1) {
		this.value = this.value.substr(0, this.value.lastIndexOf("."));
	}
	if (this.value.indexOf(".") != '-1') {
		this.value = this.value.substring(0, this.value.indexOf(".") + 3);
	}
});

//Ввод стоимость
$('#multisum_nal').bind('input', function(){
	this.value = this.value.replace(/[^0-9\.]/g, '');
	let count = this.value.split(".").length-1;
	if (count > 1) {
		this.value = this.value.substr(0, this.value.lastIndexOf("."));
	}
	if (this.value.indexOf(".") != '-1') {
		this.value = this.value.substring(0, this.value.indexOf(".") + 3);
	}
});

//Ввод стоимость
$('#multisum_beznal').bind('input', function(){
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
	if (cash_card == 'Безналичный расчет' || cash_card == 'Смешанный расчет') {
		$('#selected_paytype option:contains("Да")').prop('selected', true);
		$('#data_client').removeClass('uk-hidden');
		$('#client_name').attr("required", true);
		$('#client_passport').attr("required", true);
		$('#client_address').attr("required", true);
		if (cash_card == 'Смешанный расчет') {
			$('#multisum').removeClass('uk-hidden');
			$('#multisum_nal').attr("required", true);
			$('#multisum_beznal').attr("required", true);
		} else {
			$('#multisum').addClass('uk-hidden');
			$('#multisum_nal').attr("required", false);
			$('#multisum_beznal').attr("required", false);
		}
	} else {
		$('#selected_paytype option:contains("Нет")').prop('selected', true);
		$('#data_client').addClass('uk-hidden');
		$('#client_name').attr("required", false);
		$('#client_passport').attr("required", false);
		$('#client_address').attr("required", false);
		if (cash_card == 'Смешанный расчет') {
			$('#multisum').removeClass('uk-hidden');
			$('#multisum_nal').attr("required", true);
			$('#multisum_beznal').attr("required", true);
		} else {
			$('#multisum').addClass('uk-hidden');
			$('#multisum_nal').attr("required", false);
			$('#multisum_beznal').attr("required", false);
		}
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



//Добавление нового аффинажа по золоту
$('#reg_new_affinaj').click(function() {
	var affinaj_date = $('#affinaj_date').val();
	var affinaj_point = $('#affinaj_point').val();
	var affinaj_idpoint = $('#affinaj_idpoint').val();
	var affinaj_worker = $('#affinaj_worker').val();
	//console.log(affinaj_date,affinaj_point,affinaj_idpoint,affinaj_worker);

	var f375 = $('#free_for_affinaj_375').text();
	var f333 = $('#free_for_affinaj_333').text();
	var f417 = $('#free_for_affinaj_417').text();
	var f500 = $('#free_for_affinaj_500').text();
	var f585 = $('#free_for_affinaj_585').text();
	var f620 = $('#free_for_affinaj_620').text();
	var f750 = $('#free_for_affinaj_750').text();
	var f800 = $('#free_for_affinaj_800').text();
	var f850 = $('#free_for_affinaj_850').text();
	var f875 = $('#free_for_affinaj_875').text();
	var f900 = $('#free_for_affinaj_900').text();
	var f916 = $('#free_for_affinaj_916').text();
	var f958 = $('#free_for_affinaj_958').text();
	var f990 = $('#free_for_affinaj_990').text();
	//console.log(f375,f333,f417,f500,f585,f620,f750,f800,f850,f875,f900,f916,f958,f990);

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
    	'affinaj_date':affinaj_date, 
    	'affinaj_point':affinaj_point, 
    	'affinaj_idpoint':affinaj_idpoint, 
    	'affinaj_worker':affinaj_worker, 

    	'f375':f375, 
        'f333':f333,
        'f417':f417,
        'f500':f500,
        'f585':f585,
        'f620':f620,
        'f750':f750,
        'f800':f800,
        'f850':f850,
        'f875':f875,
        'f900':f900,
        'f916':f916,
        'f958':f958,
        'f990':f990,

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
        	let operation_id = $('#operation_id').text();
        	console.log (operation_id);
        	window.location.replace("/affinazh-raskhod-uspeshnaia-registratciia/?id=" + operation_id);
        }
    },
    error: function (jqXHR, text, error) {
        $('#result_new_affinaj').html(error);
    }
});
return false;    
});
//Добавление нового аффинажа по золоту



//Добавление нового аффинажа по серебру
$('#reg_new_affinaj_ag').click(function() {
	var affinaj_ag_date = $('#affinaj_ag_date').val();
	var affinaj_ag_point = $('#affinaj_ag_point').val();
	var affinaj_ag_idpoint = $('#affinaj_ag_idpoint').val();
	var affinaj_ag_worker = $('#affinaj_ag_worker').val();
	//console.log(affinaj_ag_date,affinaj_ag_point,affinaj_ag_idpoint,affinaj_ag_worker);

	var fag = $('#free_for_affinaj_Ag').text();
	var fag800 = $('#free_for_affinaj_Ag-800').text();
	var fag875 = $('#free_for_affinaj_Ag-875').text();
	var fag925 = $('#free_for_affinaj_Ag-925').text();
	//console.log(fag,fag875,fag925);

    var pag = $('#weight_affinaj_Ag').val();
    var pag800 = $('#weight_affinaj_Ag-800').val();
    var pag875 = $('#weight_affinaj_Ag-875').val();
    var pag925 = $('#weight_affinaj_Ag-925').val();
    //console.log(pag,pag875,pag925);
$.ajax({
    type: "POST",
    url: '/add_new_affinaj_ag.php',
    data: { 
    	'affinaj_ag_date':affinaj_ag_date, 
    	'affinaj_ag_point':affinaj_ag_point, 
    	'affinaj_ag_idpoint':affinaj_ag_idpoint, 
    	'affinaj_ag_worker':affinaj_ag_worker, 

    	'fag':fag, 
    	'fag800':fag800,
        'fag875':fag875,
        'fag925':fag925,

        'pag':pag, 
        'pag800':pag800,
        'pag875':pag875,
        'pag925':pag925,
    },
    beforeSend: function () {
        $('#result_new_affinaj_ag').html('<p class="messages" style="color: green;">Отправка и обработка данных...</p>');
    },
    success: function (data) {
        $('#result_new_affinaj_ag').html(data);
        let result_add = $('#result_add').text();
        if (result_add == 'Аффинаж зарегестрирован') {
        	$('#reg_new_affinaj_ag').addClass('uk-hidden');
        	$('#weight_affinaj_Ag').attr("disabled", true);
        	$('#weight_affinaj_Ag-800').attr("disabled", true);
        	$('#weight_affinaj_Ag-875').attr("disabled", true);
        	$('#weight_affinaj_Ag-925').attr("disabled", true);
        	let operation_id = $('#operation_id').text();
        	console.log (operation_id);
        	window.location.replace("/affinazh-ag-raskhod-uspeshnaia-registratciia/?id=" + operation_id);
        }
    },
    error: function (jqXHR, text, error) {
        $('#result_new_affinaj_ag').html(error);
    }
});
return false;    
});
//Добавление нового аффинажа по серебру



//Правка инпута Weight при редактировании аффинажа
$('.edit_weight_affinaj').bind('input', function(){
	this.value = this.value.replace(/[^0-9\.]/g, '');
	let count = this.value.split(".").length-1;
	if (count > 1) {
		this.value = this.value.substr(0, this.value.lastIndexOf("."));
	}
	if (this.value.indexOf(".") != '-1') {
		this.value = this.value.substring(0, this.value.indexOf(".") + 3);
	}
});



//Редактирование аффинажа по золоту
$('#edit_affinaj').click(function() {
	var affinaj_date = $('#affinaj_date').val();
	var affinaj_point = $('#affinaj_point').val();
	var affinaj_idpoint = $('#affinaj_idpoint').val();
	var affinaj_worker = $('#affinaj_worker').val();
	var affinaj_id = $('#affinaj_id').val();
	console.log(affinaj_date,affinaj_point,affinaj_idpoint,affinaj_worker,affinaj_id);

	var o375 = $('#old_for_affinaj_375').text();
	var o333 = $('#old_for_affinaj_333').text();
	var o417 = $('#old_for_affinaj_417').text();
	var o500 = $('#old_for_affinaj_500').text();
	var o585 = $('#old_for_affinaj_585').text();
	var o620 = $('#old_for_affinaj_620').text();
	var o750 = $('#old_for_affinaj_750').text();
	var o800 = $('#old_for_affinaj_800').text();
	var o850 = $('#old_for_affinaj_850').text();
	var o875 = $('#old_for_affinaj_875').text();
	var o900 = $('#old_for_affinaj_900').text();
	var o916 = $('#old_for_affinaj_916').text();
	var o958 = $('#old_for_affinaj_958').text();
	var o990 = $('#old_for_affinaj_990').text();
	console.log(o375,o333,o417,o500,o585,o620,o750,o800,o850,o875,o900,o916,o958,o990);

    var n375 = $('#new_weight_affinaj_375').val();
    var n333 = $('#new_weight_affinaj_333').val();
    var n417 = $('#new_weight_affinaj_417').val();
    var n500 = $('#new_weight_affinaj_500').val();
    var n585 = $('#new_weight_affinaj_585').val();
    var n620 = $('#new_weight_affinaj_620').val();
    var n750 = $('#new_weight_affinaj_750').val();
    var n800 = $('#new_weight_affinaj_800').val();
    var n850 = $('#new_weight_affinaj_850').val();
    var n875 = $('#new_weight_affinaj_875').val();
    var n900 = $('#new_weight_affinaj_900').val();
    var n916 = $('#new_weight_affinaj_916').val();
    var n958 = $('#new_weight_affinaj_958').val();
    var n990 = $('#new_weight_affinaj_990').val();
    console.log(n375,n333,n417,n500,n585,n620,n750,n800,n850,n875,n900,n916,n958,n990);
$.ajax({
    type: "POST",
    url: '/edit_affinaj.php',
    data: { 
    	'affinaj_date':affinaj_date, 
    	'affinaj_point':affinaj_point, 
    	'affinaj_idpoint':affinaj_idpoint, 
    	'affinaj_worker':affinaj_worker, 
    	'affinaj_id':affinaj_id,

    	'o375':o375, 
        'o333':o333,
        'o417':o417,
        'o500':o500,
        'o585':o585,
        'o620':o620,
        'o750':o750,
        'o800':o800,
        'o850':o850,
        'o875':o875,
        'o900':o900,
        'o916':o916,
        'o958':o958,
        'o990':o990,

        'n375':n375, 
        'n333':n333,
        'n417':n417,
        'n500':n500,
        'n585':n585,
        'n620':n620,
        'n750':n750,
        'n800':n800,
        'n850':n850,
        'n875':n875,
        'n900':n900,
        'n916':n916,
        'n958':n958,
        'n990':n990,
    },
    beforeSend: function () {
        $('#result_edit_affinaj').html('<p class="messages" style="color: green;">Отправка и обработка данных...</p>');
    },
    success: function (data) {
        $('#result_edit_affinaj').html(data);
        let result_add = $('#result_add').text();
        if (result_add == 'Аффинаж изменен') {
        	$('#edit_affinaj').addClass('uk-hidden');
        	$('#new_weight_affinaj_375').attr("disabled", true);
        	$('#new_weight_affinaj_333').attr("disabled", true);
        	$('#new_weight_affinaj_417').attr("disabled", true);
        	$('#new_weight_affinaj_500').attr("disabled", true);
        	$('#new_weight_affinaj_585').attr("disabled", true);
        	$('#new_weight_affinaj_620').attr("disabled", true);
        	$('#new_weight_affinaj_750').attr("disabled", true);
        	$('#new_weight_affinaj_800').attr("disabled", true);
        	$('#new_weight_affinaj_850').attr("disabled", true);
        	$('#new_weight_affinaj_875').attr("disabled", true);
        	$('#new_weight_affinaj_900').attr("disabled", true);
        	$('#new_weight_affinaj_916').attr("disabled", true);
        	$('#new_weight_affinaj_958').attr("disabled", true);
        	$('#new_weight_affinaj_990').attr("disabled", true);
        	let affinaj_id = $('#affinaj_id').val();
        	console.log (affinaj_id);
        	window.location.replace("/affinazh-raskhod-vnesti-izmeneniia-uspeshno/?id=" + affinaj_id);
        }
    },
    error: function (jqXHR, text, error) {
        $('#result_edit_affinaj').html(error);
    }
});
return false;    
});
//Редактирование аффинажа по золоту



//Редактирование аффинажа по серебпу
$('#edit_affinaj_ag').click(function() {
	var affinaj_ag_date = $('#affinaj_ag_date').val();
	var affinaj_ag_point = $('#affinaj_ag_point').val();
	var affinaj_ag_idpoint = $('#affinaj_ag_idpoint').val();
	var affinaj_ag_worker = $('#affinaj_ag_worker').val();
	var affinaj_ag_id = $('#affinaj_ag_id').val();
	console.log(affinaj_ag_date,affinaj_ag_point,affinaj_ag_idpoint,affinaj_ag_worker,affinaj_ag_id);

	var oag = $('#old_for_affinaj_Ag').text();
	var oag800 = $('#old_for_affinaj_Ag-800').text();
	var oag875 = $('#old_for_affinaj_Ag-875').text();
	var oag925 = $('#old_for_affinaj_Ag-925').text();
	console.log(oag,oag875,oag925);

    var nag = $('#new_weight_affinaj_Ag').val();
    var nag800 = $('#new_weight_affinaj_Ag-800').val();
    var nag875 = $('#new_weight_affinaj_Ag-875').val();
    var nag925 = $('#new_weight_affinaj_Ag-925').val();
    console.log(nag,nag875,nag925);
$.ajax({
    type: "POST",
    url: '/edit_affinaj_ag.php',
    data: { 
    	'affinaj_ag_date':affinaj_ag_date, 
    	'affinaj_ag_point':affinaj_ag_point, 
    	'affinaj_ag_idpoint':affinaj_ag_idpoint, 
    	'affinaj_ag_worker':affinaj_ag_worker, 
    	'affinaj_ag_id':affinaj_ag_id,

    	'oag':oag, 
    	'oag800':oag800,
        'oag875':oag875,
        'oag925':oag925,

        'nag':nag, 
        'nag800':nag800,
        'nag875':nag875,
        'nag925':nag925,
    },
    beforeSend: function () {
        $('#result_edit_affinaj_ag').html('<p class="messages" style="color: green;">Отправка и обработка данных...</p>');
    },
    success: function (data) {
        $('#result_edit_affinaj_ag').html(data);
        let result_add = $('#result_add').text();
        if (result_add == 'Аффинаж изменен') {
        	$('#edit_affinaj_ag').addClass('uk-hidden');
        	$('#new_weight_affinaj_Ag').attr("disabled", true);
        	$('#new_weight_affinaj_Ag-800').attr("disabled", true);
        	$('#new_weight_affinaj_Ag-875').attr("disabled", true);
        	$('#new_weight_affinaj_Ag-925').attr("disabled", true);
        	let affinaj_ag_id = $('#affinaj_ag_id').val();
        	console.log (affinaj_ag_id);
        	window.location.replace("/affinazh-ag-raskhod-vnesti-izmeneniia-uspeshno/?id=" + affinaj_ag_id);
        }
    },
    error: function (jqXHR, text, error) {
        $('#result_edit_affinaj_ag').html(error);
    }
});
return false;    
});
//Редактирование аффинажа по серебру



//Правка инпута Weight при закрытии аффинажа
$('.close_weight_affinaj').bind('input', function(){
	this.value = this.value.replace(/[^0-9\.]/g, '');
	let count = this.value.split(".").length-1;
	if (count > 1) {
		this.value = this.value.substr(0, this.value.lastIndexOf("."));
	}
	if (this.value.indexOf(".") != '-1') {
		this.value = this.value.substring(0, this.value.indexOf(".") + 3);
	}
});



//Закрытие аффинажа по золоту
$('#close_affinaj').click(function() {
	var affinaj_date = $('#affinaj_date').val();
	var affinaj_point = $('#affinaj_point').val();
	var affinaj_idpoint = $('#affinaj_idpoint').val();
	var affinaj_worker = $('#affinaj_worker').val();
	var affinaj_id = $('#affinaj_id').val();
	console.log(affinaj_date,affinaj_point,affinaj_idpoint,affinaj_worker,affinaj_id);

	var proba999 = $('#close_weight_affinaj').val();
	console.log(proba999);
	$.ajax({
	    type: "POST",
	    url: '/close_affinaj.php',
	    data: { 
	    	'affinaj_date':affinaj_date, 
	    	'affinaj_point':affinaj_point, 
	    	'affinaj_idpoint':affinaj_idpoint, 
	    	'affinaj_worker':affinaj_worker, 
	    	'affinaj_id':affinaj_id,

	    	'proba999':proba999, 
	    },
	    beforeSend: function () {
	        $('#result_close_affinaj').html('<p class="messages" style="color: green;">Отправка и обработка данных...</p>');
	    },
	    success: function (data) {
	        $('#result_close_affinaj').html(data);
	        let result_add = $('#result_add').text();
	        if (result_add == 'Аффинаж закрыт') {
	        	$('#close_affinaj').addClass('uk-hidden');
	        	$('#close_weight_affinaj').attr("disabled", true);
	        	let affinaj_id = $('#affinaj_id').val();
	        	console.log (affinaj_id);
	        	window.location.replace("/affinazh-prikhod-zakrytie-uspeshno/?id=" + affinaj_id);
	        }
	    },
	    error: function (jqXHR, text, error) {
	        $('#result_close_affinaj').html(error);
	    }
	});
	return false;  
});
//Закрытие аффинажа по золоту



//Закрытие аффинажа по серебру
$('#close_affinaj_ag').click(function() {
	var affinaj_ag_date = $('#affinaj_ag_date').val();
	var affinaj_ag_point = $('#affinaj_ag_point').val();
	var affinaj_ag_idpoint = $('#affinaj_ag_idpoint').val();
	var affinaj_ag_worker = $('#affinaj_ag_worker').val();
	var affinaj_ag_id = $('#affinaj_ag_id').val();
	console.log(affinaj_ag_date,affinaj_ag_point,affinaj_ag_idpoint,affinaj_ag_worker,affinaj_ag_id);

	var proba999 = $('#close_weight_affinaj_ag').val();
	console.log(proba999);
	$.ajax({
	    type: "POST",
	    url: '/close_affinaj_ag.php',
	    data: { 
	    	'affinaj_ag_date':affinaj_ag_date, 
	    	'affinaj_ag_point':affinaj_ag_point, 
	    	'affinaj_ag_idpoint':affinaj_ag_idpoint, 
	    	'affinaj_ag_worker':affinaj_ag_worker, 
	    	'affinaj_ag_id':affinaj_ag_id,

	    	'proba999':proba999, 
	    },
	    beforeSend: function () {
	        $('#result_close_affinaj_ag').html('<p class="messages" style="color: green;">Отправка и обработка данных...</p>');
	    },
	    success: function (data) {
	        $('#result_close_affinaj_ag').html(data);
	        let result_add = $('#result_add').text();
	        if (result_add == 'Аффинаж закрыт') {
	        	$('#close_affinaj_ag').addClass('uk-hidden');
	        	$('#close_weight_affinaj_ag').attr("disabled", true);
	        	let affinaj_ag_id = $('#affinaj_ag_id').val();
	        	console.log (affinaj_ag_id);
	        	window.location.replace("/affinazh-ag-prikhod-zakrytie-uspeshno/?id=" + affinaj_ag_id);
	        }
	    },
	    error: function (jqXHR, text, error) {
	        $('#result_close_affinaj_ag').html(error);
	    }
	});
	return false;  
});
//Закрытие аффинажа по серебру



//Выбор открытого резерва
$('p.reserv_id').click(function() {
    let edited_reserv = $(this).attr('reserv_id');
    let proba_reserv = $(this).attr('proba');
    let weight_reserv = $(this).attr('weight');
    $('#reserv_id').val(edited_reserv);
    $('#selected_proba').val(proba_reserv);
    $('#selected_weight').val(weight_reserv);

    $('#operation_reserv_id').val(edited_reserv);
    $('#operation_selected_proba').val(proba_reserv);
    $('#operation_selected_weight').val(weight_reserv);
});



//Ввод суммы
$('#selected_sum').bind('input', function(){
	this.value = this.value.replace(/[^0-9\.]/g, '');
	let count = this.value.split(".").length-1;
	if (count > 1) {
		this.value = this.value.substr(0, this.value.lastIndexOf("."));
	}
	if (this.value.indexOf(".") != '-1') {
		this.value = this.value.substring(0, this.value.indexOf(".") + 3);
	}
});



//Правка инпута Weight при редактировании заявки закрытия смены
$('.edit_weight_close').bind('input', function(){
	this.value = this.value.replace(/[^0-9\.]/g, '');
	let count = this.value.split(".").length-1;
	if (count > 1) {
		this.value = this.value.substr(0, this.value.lastIndexOf("."));
	}
	if (this.value.indexOf(".") != '-1') {
		this.value = this.value.substring(0, this.value.indexOf(".") + 3);
	}
});



//Ввод суммы при редактировании заявки закрытия смены
$('#close_cash').bind('input', function(){
	this.value = this.value.replace(/[^0-9\.]/g, '');
	let count = this.value.split(".").length-1;
	if (count > 1) {
		this.value = this.value.substr(0, this.value.lastIndexOf("."));
	}
	if (this.value.indexOf(".") != '-1') {
		this.value = this.value.substring(0, this.value.indexOf(".") + 3);
	}
});

$('#close_bn_cash').bind('input', function(){
	this.value = this.value.replace(/[^0-9\.]/g, '');
	let count = this.value.split(".").length-1;
	if (count > 1) {
		this.value = this.value.substr(0, this.value.lastIndexOf("."));
	}
	if (this.value.indexOf(".") != '-1') {
		this.value = this.value.substring(0, this.value.indexOf(".") + 3);
	}
});



//Ввод суммы при регистрации долга
$('#arrear_sum').bind('input', function(){
	this.value = this.value.replace(/[^0-9\.]/g, '');
	let count = this.value.split(".").length-1;
	if (count > 1) {
		this.value = this.value.substr(0, this.value.lastIndexOf("."));
	}
	if (this.value.indexOf(".") != '-1') {
		this.value = this.value.substring(0, this.value.indexOf(".") + 3);
	}
});



//Регистрация нового долга
$('#reg_new_arrear').click(function() {
	var arrear_date = $('#arrear_date').val();
	var arrear_point = $('#arrear_point').val();
	var arrear_idpoint = $('#arrear_idpoint').val();
	var arrear_worker = $('#arrear_worker').val();
	var arrear_person = $('#arrear_person').val();
	var arrear_sum = $('#arrear_sum').val();
	var arrear_descript = $('#arrear_descript').val();
	//console.log(arrear_date,arrear_point,arrear_idpoint,arrear_worker,arrear_person,arrear_sum,arrear_descript);

	if (arrear_person == '' || arrear_sum == '' || arrear_descript == '') {
		alert('Заполните пожалуйста все поля при регистрации долга');
	} else {
		$.ajax({
		    type: "POST",
		    url: '/add_new_arrear.php',
		    data: { 
		    	'arrear_date':arrear_date, 
		    	'arrear_point':arrear_point, 
		    	'arrear_idpoint':arrear_idpoint, 
		    	'arrear_worker':arrear_worker, 
		    	'arrear_person':arrear_person,
		    	'arrear_sum':arrear_sum,
		    	'arrear_descript':arrear_descript, 
		    },
		    beforeSend: function () {
		        $('#result_new_arrear').html('<p class="messages" style="color: green;">Отправка и обработка данных...</p>');
		    },
		    success: function (data) {
		        $('#result_new_arrear').html(data);
		        $('#arrear_person').val('');
		        $('#arrear_sum').val('');
		        $('#arrear_descript').val('');
		        let currentUrl = $(location).attr('href');
		        if (currentUrl == 'http://accounting-aurum/dolgi/') {
		        	window.location.replace("http://accounting-aurum/dolgi/");
		        }
		    },
		    error: function (jqXHR, text, error) {
		        $('#result_new_arrear').html(error);
		    }
		});
	return false;  
	}
});
//Закрытие аффинажа



//Ввод суммы при добавлении работ к изделию
$('#addw_pay').bind('input', function(){
	this.value = this.value.replace(/[^0-9\.]/g, '');
	let count = this.value.split(".").length-1;
	if (count > 1) {
		this.value = this.value.substr(0, this.value.lastIndexOf("."));
	}
	if (this.value.indexOf(".") != '-1') {
		this.value = this.value.substring(0, this.value.indexOf(".") + 3);
	}
});



//Ввод сумм при изменении базовых цен в настройках
$('#main_price_gold').bind('input', function(){
	this.value = this.value.replace(/[^0-9\.]/g, '');
	let count = this.value.split(".").length-1;
	if (count > 1) {
		this.value = this.value.substr(0, this.value.lastIndexOf("."));
	}
	if (this.value.indexOf(".") != '-1') {
		this.value = this.value.substring(0, this.value.indexOf(".") + 3);
	}
});

$('#main_price_gold_999').bind('input', function(){
	this.value = this.value.replace(/[^0-9\.]/g, '');
	let count = this.value.split(".").length-1;
	if (count > 1) {
		this.value = this.value.substr(0, this.value.lastIndexOf("."));
	}
	if (this.value.indexOf(".") != '-1') {
		this.value = this.value.substring(0, this.value.indexOf(".") + 3);
	}
});

$('#main_price_silver').bind('input', function(){
	this.value = this.value.replace(/[^0-9\.]/g, '');
	let count = this.value.split(".").length-1;
	if (count > 1) {
		this.value = this.value.substr(0, this.value.lastIndexOf("."));
	}
	if (this.value.indexOf(".") != '-1') {
		this.value = this.value.substring(0, this.value.indexOf(".") + 3);
	}
});

$('#main_price_platinum').bind('input', function(){
	this.value = this.value.replace(/[^0-9\.]/g, '');
	let count = this.value.split(".").length-1;
	if (count > 1) {
		this.value = this.value.substr(0, this.value.lastIndexOf("."));
	}
	if (this.value.indexOf(".") != '-1') {
		this.value = this.value.substring(0, this.value.indexOf(".") + 3);
	}
});

$('#main_price_palladium').bind('input', function(){
	this.value = this.value.replace(/[^0-9\.]/g, '');
	let count = this.value.split(".").length-1;
	if (count > 1) {
		this.value = this.value.substr(0, this.value.lastIndexOf("."));
	}
	if (this.value.indexOf(".") != '-1') {
		this.value = this.value.substring(0, this.value.indexOf(".") + 3);
	}
});



//Логаут при бездействии
var inactivityTime = function () {
    var time;
    window.onload = resetTimer;
    // DOM Events
    document.onmousemove = resetTimer;
    document.onkeydown = resetTimer;

    function logout() {
        //alert("You are now logged out.")
        location.href = '/login/?logout'
    }

    function resetTimer() {
        clearTimeout(time);
        time = setTimeout(logout, 1200000)
        // 1000 milliseconds = 1 second
    }
};

window.onload = function() {
  	inactivityTime();
}



//Скрипты при изменении данных в операции для изменения остатков по лому и кассам
$('#btn_edit_return').click(function() {
	//console.log ('Скрипт возврата лома');
	var id_edit_operation = $('#id_edit_operation').val();
	var proba_return = $('#proba_return').val();
	var weight_return = $('#edit_return').val();
	if (proba_return == '' || weight_return == '') {
		alert('Заполните пожалуйста все поля возврата лома');
	} else {
		$.ajax({
		    type: "POST",
		    url: '/edtoper_lom_return.php',
		    data: {
		    	'id_edit_operation':id_edit_operation,  
		    	'proba_return':proba_return, 
		    	'weight_return':weight_return, 
		    },
		    beforeSend: function () {
		        $('#result_lom_return').html('<p class="messages" style="color: green;">Отправка и обработка данных...</p>');
		    },
		    success: function (data) {
		        $('#result_lom_return').html(data);
		        $('#edit_return').addClass('uk-hidden');
		        $('#btn_edit_return').addClass('uk-hidden');
		    },
		    error: function (jqXHR, text, error) {
		        $('#result_lom_return').html(error);
		    }
		});
	return false;  
	}
});

$('#btn_edit_pick').click(function() {
	//console.log ('Скрипт забора лома');
	var id_edit_operation = $('#id_edit_operation').val();
	var proba_pick = $('#proba_pick').val();
	var weight_pick = $('#edit_pick').val();
	if (proba_pick == '' || weight_pick == '') {
		alert('Заполните пожалуйста все поля возврата лома');
	} else {
		$.ajax({
		    type: "POST",
		    url: '/edtoper_lom_pick.php',
		    data: {
		    	'id_edit_operation':id_edit_operation,  
		    	'proba_pick':proba_pick, 
		    	'weight_pick':weight_pick, 
		    },
		    beforeSend: function () {
		        $('#result_lom_pick').html('<p class="messages" style="color: green;">Отправка и обработка данных...</p>');
		    },
		    success: function (data) {
		        $('#result_lom_pick').html(data);
		        $('#edit_pick').addClass('uk-hidden');
		        $('#btn_edit_pick').addClass('uk-hidden');
		    },
		    error: function (jqXHR, text, error) {
		        $('#result_lom_pick').html(error);
		    }
		});
	return false;  
	}
});

$('#btn_edit_lom').click(function() {
	//console.log ('Скрипт изменения лома');
	var id_edit_operation = $('#id_edit_operation').val();
	var proba_edit = $('#proba_lom').val();
	var weight_edit = $('#edit_lom').val();
	if (proba_edit == '' || weight_edit == '') {
		alert('Заполните пожалуйста все поля возврата лома');
	} else {
		$.ajax({
		    type: "POST",
		    url: '/edtoper_lom_edit.php',
		    data: {
		    	'id_edit_operation':id_edit_operation,  
		    	'proba_edit':proba_edit, 
		    	'weight_edit':weight_edit, 
		    },
		    beforeSend: function () {
		        $('#result_lom_edit').html('<p class="messages" style="color: green;">Отправка и обработка данных...</p>');
		    },
		    success: function (data) {
		        $('#result_lom_edit').html(data);
		        $('#edit_lom').addClass('uk-hidden');
		        $('#btn_edit_lom').addClass('uk-hidden');
		    },
		    error: function (jqXHR, text, error) {
		        $('#result_lom_edit').html(error);
		    }
		});
	return false;  
	}
});

$('#btn_edit_cash').click(function() {
	//console.log ('Скрипт изменения кассы');
	var id_edit_operation = $('#id_edit_operation').val();
	var id_edit_cash = $('#id_cash').val();
	var sum_edit = $('#edit_cash').val();
	if (sum_edit == '') {
		alert('Заполните пожалуйста все поля изменения в кассе');
	} else {
		$.ajax({
		    type: "POST",
		    url: '/edtoper_cash.php',
		    data: {
		    	'id_edit_operation':id_edit_operation, 
		    	'id_edit_cash':id_edit_cash,  
		    	'sum_edit':sum_edit,
		    },
		    beforeSend: function () {
		        $('#result_edit_cash').html('<p class="messages" style="color: green;">Отправка и обработка данных...</p>');
		    },
		    success: function (data) {
		        $('#result_edit_cash').html(data);
		        $('#edit_cash').addClass('uk-hidden');
		        $('#btn_edit_cash').addClass('uk-hidden');
		    },
		    error: function (jqXHR, text, error) {
		        $('#result_edit_cash').html(error);
		    }
		});
	return false;  
	}
});
//Скрипты при изменении данных в операции для изменения остатков по лому и кассам



//Набор корзины в мульти скупке
$('#btn_add_lom').click(function() {
	//console.log ('Набор корзины в мульти скупке');
	if ($('#selected_weight').val() == '' || $('#selected_price').val() == '' || $('#selected_pay').val() == '') {
		alert('Недостаточно данных для добавления позиции, проверьте заполненность нужных полей');
	} else {
		let elem_content = $('#selected_proba').val() + ' — ' + $('#price_gramm').val() + ' — ' + $('#selected_weight').val() + ' — ' + $('#selected_price').val() + ' — ' + $('#selected_pay').val() + ' — ' + $('#cash_card').val() + ' — ' + $('#description_operation').val();
		$('#cart_element').append('<p class="cart-item uk-margin-remove uk-flex uk-flex-between">' + elem_content + '<span class="del_elem" style="cursor:pointer;">❌</span></p>');
	}
});
//Набор корзины в мульти скупке

//Удаление из корзины в мульти скупке
$(document).on("click", "span.del_elem", function(){
	//console.log ('Удаление из корзины в мульти скупке');
	$(this).parent().remove();
});
//Удаление из корзины в мульти скупке

//Отрпавка мультискупки на регистрацию
$('#btn_reg').hover(function() {
	let cart_content = '';
	let cart_items = $('#cart_element');
    $(cart_items).find('p.cart-item').each(function (){
        cart_content = cart_content + '===' + $(this).text();
        $('#selected_cart').val(cart_content);
    })
});
//Отрпавка мультискупки на регистрацию









//Формирование отчета основного для скачивания
$('#download_main').click(function() {
	let download_date = $('#download_date').val();
	console.log (download_date);
	window.location.replace("/osnovnoi-otchet-skachat/?download_date=" + download_date);
});
//Формирование отчета основного для скачивания

//Формирование отчета за день для скачивания
$('#download_day').click(function() {
	let download_date = $('#download_date').val();
	console.log (download_date);
	window.location.replace("/otchet-za-den-skachat/?download_date=" + download_date);
});
//Формирование отчета за день для скачивания

//Формирование отчета за период для скачивания
$('#download_period').click(function() {
	let download_start_date = $('#download_start_date').val();
	let download_finish_date = $('#download_finish_date').val();
	console.log (download_start_date + download_finish_date);
	window.location.replace("/otchet-za-period-skachat/?download_start_date=" + download_start_date + "&download_finish_date=" + download_finish_date);
});
//Формирование отчета за период для скачивания

//Формирование отчета за период по точке для скачивания
$('#download_period_point').click(function() {
	let download_start_date = $('#download_start_date').val();
	let download_finish_date = $('#download_finish_date').val();
	let download_report_point = $('#download_report_point').val();
	console.log (download_start_date + download_finish_date + download_report_point);
	window.location.replace("/otchet-po-tochke-skachat/?download_start_date=" + download_start_date + "&download_finish_date=" + download_finish_date + "&download_report_point=" + download_report_point);
});
//Формирование отчета за период по точке для скачивания