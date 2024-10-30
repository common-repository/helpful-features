(function($) {

	$('.hefe-animate-css-item').appear(function(){
		var element = $(this);
		var effect = element.data('effect');
		var effectDelay = element.data('delay');
		if (effectDelay) {
			element.addClass('hefe-animate-hidden');
			setTimeout(function(){
				element.addClass(effect+' hefe-animate-visible');
				element.removeClass('hefe-animate-hidden');
			}, effectDelay);
		} else {
			element.addClass(effect+' hefe-animate-visible');
			element.removeClass('hefe-animate-hidden');
		}   
	});

})(jQuery);