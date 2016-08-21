$(function(){

	//general form validator
	$('form').validate({
		errorElement: 'span',
		errorClass: 'help-inline',
		onfocusout: false,
    	onkeyup: function(element) { },
		highlight: function(element, errorClass, validClass) {
			$(element).closest('div.form-group').addClass('has-error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).closest('div.form-group').removeClass('has-error');
		}
	});

	//delete buttons
	$('a.delete').click(function(e){
		e.preventDefault();
		if ($(this).hasClass('btn-danger')) {
			$('form#delete').submit();
		} else {
			$(this).addClass('btn-danger');
		}
	});
	
	//fill date 
	$('.input-group.date .input-group-addon').click(function(e){
		e.preventDefault();
		var today = new Date();
		var yyyy = today.getFullYear().toString();
		var mm = (today.getMonth()+1).toString();
		var dd  = today.getDate().toString();
		today = yyyy + '-' + (mm[1] ? mm : '0' + mm[0]) + '-' + (dd[1] ? dd : '0' + dd[0]);
		$(this).closest('.input-group').find('input').val(today);
	});

	$('.input-group.money input[type=checkbox]').change(function(){
		$(this).closest('.input-group').find('input[type=number]').prop('disabled', !$(this).prop('checked'));
	});
	
	$('input[name=hours]').keyup(updateAmount).change(updateAmount);
	
	function updateAmount() {
		var $amount = $('input[name=amount]');
		if ($amount.prop('disabled')) $amount.val(formatNumber($('input[name=hours]').val() * $('input[name=rate]').val()));
	}
	
	function formatNumber(num) {
		return parseFloat(Math.round(num * 100) / 100).toFixed(2);
	}

	/*get timezone, update user record if different
	var tz = jstz.determine(), 
		$meta = $('meta[name=timezone]'), 
		token = $('meta[name=token]').attr('content'),
		$input = $('input[name=timezone]');
	if ($meta.size()) {
		if ($meta.attr('content') != tz.name()) {
			$.post('/timezone', { _token: token, timezone: tz.name() });
		}
	} else if ($input.size()) {
		$input.val(tz.name());
	}*/

});