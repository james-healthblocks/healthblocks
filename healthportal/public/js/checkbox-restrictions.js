$(document).on('change', '.disable-others', function(){
	attr = $(this).attr('field');
	$others = $('.ui.checkbox[field="' + attr +'"]:not(".disable-others")');

	if($(this).checkbox('is checked')){
		$others.addClass('disabled').checkbox('uncheck');
		$others.siblings().addClass('disabled').children('input').val('');
		$(this).removeClass('disabled');
	}else if($(this).checkbox('is unchecked')){
		$others.removeClass('disabled');
		$others.siblings().removeClass('disabled');
	}

	if(attr == 'riskgroups'){
		sex = $(".sex_dropdown").dropdown('get value');
		if(sex == '1'){
			$('.female-only').addClass('disabled');
		}else{
			$('.male-only').addClass('disabled');
		}
	}
	

});