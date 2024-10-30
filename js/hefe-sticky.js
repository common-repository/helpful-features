(function($) {

	var $window = $(window), $hefe_sticky_item = $('.hefe-sticky-item');
	var stickyOffset = $hefe_sticky_item.offset().top-0;
	var widgetsHeight = $hefe_sticky_item.outerHeight(true);
	$window.scroll(function(){
		var scroll = $window.scrollTop();
		if (scroll >= stickyOffset) {
			$hefe_sticky_item.prev('.hefe-sticky-placement').css('padding-top',widgetsHeight); 
			$hefe_sticky_item.css({position:'fixed',top:'0px',left:'0px'});
		} else {
			$hefe_sticky_item.prev('.hefe-sticky-placement').css('padding-top','0px'); 
			$hefe_sticky_item.css({position:'relative',top:'',left:''});
		}
	});

})(jQuery);