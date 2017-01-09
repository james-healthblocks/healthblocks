$(".ui.dropdown").dropdown();
$(".ui.checkbox").checkbox();
$('.datepicker').datepicker({
    autoclose: true
});

$(document).on('change', '.date-field', function(){
	$(".date-field").removeClass('error');
});

$(document).on('click', '.close.icon', function(){
	$("form").find(".error").removeClass('error');
});

$("form").submit(function(){
	var monthYear = $(this).hasClass('monthYear');
	var fullDate = $(this).hasClass('fullDate');
	var error = false;
	var messages = [];

	if(document.getElementsByClassName('reports-filter').length > 0){
		var x = 0;
		$('.reports-filter').find('.filter-field').each(function(){
			if($(this).checkbox('is checked')){
				x++;
			}
		});

		if(x == 0){
			error = true;
			messages.push('No STI Checked')
		}
	}

	if(monthYear){
		var startMonth = $("#startMonth").dropdown('get value')[0];
		var startYear = $("#startYear").dropdown('get value')[0];

		var endMonth = $("#endMonth").dropdown('get value')[0];
		var endYear = $("#endYear").dropdown('get value')[0];

		var startDate = new Date(startYear + "-" + startMonth);
		var endDate = new Date(endYear + "-" + endMonth);

	}else if(fullDate){
		var startDate = new Date($("input[name='activity-date-start']").val());
		var endDate = new Date($("input[name='activity-date-end']").val());
	}

	startDateValid = moment(startDate, moment.ISO_8601, true).isValid(); 
	endDateValid = moment(endDate, moment.ISO_8601, true).isValid(); 

	if((startDate > endDate) || !startDateValid || !endDateValid){
		$(".date-field").addClass('error');

		messages.push("Invalid Date Range");
		error = true;
	}

	if(error){
		showMessage("error", "Invalid Input", messages);
		return false;
	}

	return true;
});
