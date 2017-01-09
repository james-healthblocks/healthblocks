function updateScrollbar(tresholdHeight){
	$('.sidebar-wrapper').perfectScrollbar('update');
}

$(document).ready(function(){

	//Place Logo
	var logoPath = $('.portal-logo').attr('img');
	if(logoPath){
		$('.portal-logo').css({
			'background-image' : 'url("'+ logoPath +'")',
			'background-size' : 'cover',
			'background-color' : '#fbfbfb'
		});
	}

	var tresholdHeight = $(window).outerHeight(true) - $('.portal-info').outerHeight(true) - $('.main-header').outerHeight(true);
	$('.sidebar-wrapper').css('height', tresholdHeight+'px');
	$('.sidebar-wrapper').perfectScrollbar(); 

	var padding = $('.main-header').outerHeight(true) + $('.portal-info').outerHeight(true);
	$('.ps-scrollbar-y-rail').css({
		'margin-top' : padding + 'px'
	});

	updateScrollbar(tresholdHeight);

	$('.sidebar').on("click", function(){
		$(this).on("transitionend oTransitionEnd MSTransitionEnd", function(e){
			updateScrollbar(tresholdHeight);
			$(this).off(e);
		});
	});

});

