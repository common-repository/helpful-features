(function($) {

	$('a[href*=".png"], a[href*=".gif"], a[href*=".jpg"], a[href*=".jpeg"], a[href*="youtube.com"], a[href*="youtu.be/"], a[href*="vimeo.com"], a[href*="google.com/maps"]').not('.hefe-fancybox-no-modal').each(function() {
		$(this).attr('data-fancybox','');
	});
	
})(jQuery);