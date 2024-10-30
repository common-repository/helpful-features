(function($) {

	$.fn.randomize = function(selector){
		var $elems = selector ? $(this).find(selector) : $(this).children(),
		$parents = $elems.parent();
		$parents.each(function(){
			$(this).children(selector).sort(function(){
				return Math.round(Math.random()) - 0.5;
			}).detach().appendTo(this);
		});
		return this;
	};
	$('.hefe-random-order-parent').randomize('.hefe-random-order-child');

})(jQuery);