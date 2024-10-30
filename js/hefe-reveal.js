(function($) {

	$('.hefe-reveal-parent').each(function() {
		var overHeight = $(this).find('.hefe-reveal-child-over').height();
		$(this).find('.hefe-reveal-child-under').css('min-height', overHeight);
		$(this).hover (
			function () {
				$(this).find('.hefe-reveal-child-under').stop().fadeIn();
				$(this).find('.hefe-reveal-child-over').stop().hide();
			},
			function () {
				$(this).find('.hefe-reveal-child-under').stop().hide();
				$(this).find('.hefe-reveal-child-over').stop().fadeIn();
			}
		);
	});

})(jQuery);