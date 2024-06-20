<?php namespace ProcessWire;

?>
    
    <div class="uk-padding-small uk-container uk-flex uk-flex-column uk-flex-middle">
        <h2 class="uk-margin-remove uk-heading-small uk-text-center">Система учета Aurum</h2>
        <p class="uk-margin-remove uk-text-small uk-text-center">Программа для ведения учета Aurum</p>
        <p class="uk-margin-remove uk-text-small uk-text-center">© 2024-<?php echo date("Y"); ?> NikiDa (www.nikida.ru)</p>
    </div>
    
    
    
    
    
    <script src="<?php echo $config->urls->templates; ?>scripts/jquery-3.5.1.min.js"></script>
    <script src="<?php echo $config->urls->templates; ?>scripts/main.js?v=<?php echo uniqid(); ?>"></script>





	<script>
	function digits_float(target){
	val = $(target).val().replace(/[^0-9.]/g, '');
	if (val.indexOf(".") != '-1') {
		val = val.substring(0, val.indexOf(".") + 3);
	} 
	val = val.replace(/\B(?=(\d{3})+(?!\d))/g, '');
	$(target).val(val);
	}

	$(function($){
	$('body').on('input', '#selected_weight', function(e){
		digits_float(this);
	});
	digits_float('#selected_weight');
	});

	$(function($){
	$('body').on('input', '#selected_pay', function(e){
		digits_float(this);
	});
	digits_float('#selected_weight');
	});
	</script>
    
    
    
    
    
    </body>
    </html>