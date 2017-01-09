$(document).ready(function(){
	$(document).find('input').attr('readonly', true);
	$(document).find('input[type="file"]').prop('disabled', true);
	$(document).find('input.datepicker').unbind('focus');
    $(document).find('.ui.dropdown:not(.no-reset)').addClass('disabled');
});