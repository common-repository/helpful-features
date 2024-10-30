(function($) {

	$(".hefe-simple-carousel-parent.hefe-simple-carousel-parent-default > .hefe-simple-carousel-child:gt(0)").hide();
	setInterval(function() { 
		$('.hefe-simple-carousel-parent.hefe-simple-carousel-parent-default > .hefe-simple-carousel-child:first')
		.hide()
		.next()
		.fadeIn(500)
		.end()
		.appendTo('.hefe-simple-carousel-parent.hefe-simple-carousel-parent-default');
	}, 4000);

})(jQuery);