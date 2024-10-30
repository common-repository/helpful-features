(function($) {

	$('.hefe-menu-style-02 ul li.menu-item-has-children > a').after('<div class="hefe-menu-down-arrow inactive"></div>');
	$('.hefe-menu-style-02 ul li.current-menu-item.menu-item-has-children > .hefe-menu-down-arrow').addClass('active').removeClass('inactive');
	$('.hefe-menu-style-02 ul li.current-menu-ancestor.menu-item-has-children > .hefe-menu-down-arrow').addClass('active').removeClass('inactive');
	$('.hefe-menu-style-02 ul li.current-menu-parent.menu-item-has-children > .hefe-menu-down-arrow').addClass('active').removeClass('inactive');
	$('.hefe-menu-down-arrow').on('click touchstart', function (e) {
		$(this).closest('li').toggleClass('active').children('ul.sub-menu').slideToggle();
		$(this).toggleClass('active');
		$(this).toggleClass('inactive');
		e.stopPropagation();
		e.preventDefault();
	});

})(jQuery);