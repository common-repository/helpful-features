(function($) {

	$(window).bind("scroll", function() {
		if ($(this).width() > 520) {
		    if ($(this).scrollTop() > 520) {
		        $(".hefe-scroll-up-box").fadeIn();
		    } else {
		        $(".hefe-scroll-up-box").stop().fadeOut();
		    }
		}
	});
	$(".hefe-scroll-up-box").click(function () {
		$('html, body').animate({scrollTop: $("html").offset().top}, 500);
	});

})(jQuery);