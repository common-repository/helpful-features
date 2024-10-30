(function($) {

	$('.hefe-pop-out-sidebar-link').click(function(e){
		e.preventDefault();
	});

	if ($('.hefe-pop-out-sidebar-link').data('side') == 'left') {
		$('.hefe-pop-out-sidebar-toggle').addClass('hefe-pop-out-sidebar-toggle-left');
		$('.hefe-pop-out-sidebar-widgets').addClass('hefe-pop-out-sidebar-widgets-left');
	}
	$('.hefe-pop-out-sidebar-toggle-left').unbind('click').bind('click', function (e) {
		if( $('.hefe-pop-out-sidebar-widgets').is(':visible') ) {
			$('.hefe-pop-out-sidebar-widgets').animate({ 'left': '-260px' }, function(){
				$('.hefe-pop-out-sidebar-widgets').hide();
			});
			$('body').removeClass('hefe-pop-out-sidebar-body-fixed');
			$('.hefe-pop-out-sidebar-body-bg').fadeOut();
			$('body').off('touchmove');
			event.stopPropagation();
		}
		else {
			$('.hefe-pop-out-sidebar-widgets').show();
			$('.hefe-pop-out-sidebar-widgets').animate({ 'left': '0px' });
			$('body').addClass('hefe-pop-out-sidebar-body-fixed');
			$('.hefe-pop-out-sidebar-body-bg').fadeIn();
			$('body.hefe-pop-out-sidebar-body-fixed').on('touchmove',function(e){
				if(!$('.hefe-pop-out-sidebar-widgets').has($(e.target)).length) {
					e.preventDefault();
				}
			});
			event.stopPropagation();
		}
	});
	if ($('.hefe-pop-out-sidebar-link').data('side') == 'right') {
		$('.hefe-pop-out-sidebar-toggle').addClass('hefe-pop-out-sidebar-toggle-right');
		$('.hefe-pop-out-sidebar-widgets').addClass('hefe-pop-out-sidebar-widgets-right');
	}
	$('.hefe-pop-out-sidebar-toggle-right').unbind('click').bind('click', function (e) {
		if( $('.hefe-pop-out-sidebar-widgets').is(':visible') ) {
			$('.hefe-pop-out-sidebar-widgets').animate({ 'right': '-260px' }, function(){
				$('.hefe-pop-out-sidebar-widgets').hide();
			});
			$('body').removeClass('hefe-pop-out-sidebar-body-fixed');
			$('.hefe-pop-out-sidebar-body-bg').fadeOut();
			$('body').off('touchmove');
			event.stopPropagation();
		}
		else {
			$('.hefe-pop-out-sidebar-widgets').show();
			$('.hefe-pop-out-sidebar-widgets').animate({ 'right': '0px' });
			$('body').addClass('hefe-pop-out-sidebar-body-fixed');
			$('.hefe-pop-out-sidebar-body-bg').fadeIn();
			$('body.hefe-pop-out-sidebar-body-fixed').on('touchmove',function(e){
				if(!$('.hefe-pop-out-sidebar-widgets').has($(e.target)).length) {
					e.preventDefault();
				}
			});
			event.stopPropagation();
		}
	});
	$('.hefe-pop-out-sidebar-body-bg').unbind('click').bind('click', function (e) {
		if ($('.hefe-pop-out-sidebar-link').data('side') == 'left') {
			$('.hefe-pop-out-sidebar-widgets').animate({ 'left': '-260px' }, function(){
				$('.hefe-pop-out-sidebar-widgets').hide();
			});
			$('body').removeClass('hefe-pop-out-sidebar-body-fixed');
			$('.hefe-pop-out-sidebar-body-bg').fadeOut();
			$('body').off('touchmove');
			event.stopPropagation();
		}
		else if ($('.hefe-pop-out-sidebar-link').data('side') == 'right') {
			$('.hefe-pop-out-sidebar-widgets').animate({ 'right': '-260px' }, function(){
				$('.hefe-pop-out-sidebar-widgets').hide();
			});
			$('body').removeClass('hefe-pop-out-sidebar-body-fixed');
			$('.hefe-pop-out-sidebar-body-bg').fadeOut();
			$('body').off('touchmove');
			event.stopPropagation();
		}
	});

})(jQuery);