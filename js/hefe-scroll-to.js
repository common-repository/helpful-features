(function($) {

	$('.hefe-scroll-to-link').on('click touchstart', function () {
		var $paired_id = $(this).data('paired');
		$('html, body').animate({scrollTop: $('.hefe-scroll-to-content[data-paired="' + $paired_id + '"]').offset().top}, 500);
	});

})(jQuery);