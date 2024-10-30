(function($) {

	$(".hefe-search-modal-toggle-in").click(function() {
		$(".hefe-search-modal-footer").fadeIn();
	});
	$(".hefe-search-modal-toggle-out").click(function() {
		$(".hefe-search-modal-footer").fadeOut();
	});
	$(document).keyup(function(e) {
		if (e.keyCode == 27) {
			$(".hefe-search-modal-footer").fadeOut();
		}
	});
	
})(jQuery);