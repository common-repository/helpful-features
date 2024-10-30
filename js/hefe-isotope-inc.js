(function($) {

	/* Filtered Isotope 
	------------------------------*/

	function getHashFilter() {
		var hash = location.hash;
		// get filter=filterName
		var matches = location.hash.match( /filter=([^&]+)/i );
		var hashFilter = matches && matches[1];
		return hashFilter && decodeURIComponent( hashFilter );
	}

	var $filtered_grid = $('.hefe-isotope-parent.hefe-isotope-parent-filtered');

	// bind filter button click
	var $filters = $('.hefe-isotope-filter').on( 'click', function() {
		var filterValue = $(this).attr('data-filter');
		$filtered_grid.imagesLoaded( function(){
			$filtered_grid.isotope({
				layoutMode: 'masonry',
				masonry: {
					columnWidth: '.hefe-isotope-child-column-width',
					gutter: '.hefe-isotope-gutter-width',
					isFitWidth: true,
				},
				itemSelector: '.hefe-isotope-child',
				stamp: '.hefe-isotope-stamp',
				filter: filterValue,
			});
		});
		$('.hefe-isotope-filter').removeClass('hefe-isotope-active');
		$('.hefe-isotope-filter[data-filter="'+filterValue+'"]').addClass('hefe-isotope-active');
		// set filter in hash
		location.hash = 'filter=' + encodeURIComponent( filterValue );
	});

	var isIsotopeInit = false;

	function onHashchange() {
		var hashFilter = getHashFilter();
		if ( !hashFilter && isIsotopeInit ) {
			return;
		}
		isIsotopeInit = true;
		// filter isotope
		$filtered_grid.imagesLoaded( function(){
			$filtered_grid.isotope({
				layoutMode: 'masonry',
				masonry: {
					columnWidth: '.hefe-isotope-child-column-width',
					gutter: '.hefe-isotope-gutter-width',
					isFitWidth: true,
				},
				itemSelector: '.hefe-isotope-child',
				stamp: '.hefe-isotope-stamp',
				filter: hashFilter,
			});
		});
		// set selected class on button
		if ( hashFilter ) {
			$filters.find('.is-checked').removeClass('is-checked');
			$filters.find('[data-filter="' + hashFilter + '"]').addClass('is-checked');
			$('.hefe-isotope-filter').removeClass('hefe-isotope-active');
			$('.hefe-isotope-filter[data-filter="'+hashFilter+'"]').addClass('hefe-isotope-active');
		}
	}

	$(window).on( 'hashchange', onHashchange );
	// trigger event handler to init Isotope
	onHashchange();

	$(window).resize(function() {
		$('.hefe-isotope-parent.hefe-isotope-parent-filtered').each(function() {
			$(this).isotope();
		});
	});

	/* Default Isotope 
	------------------------------*/
	var $default_grid = $('.hefe-isotope-parent.hefe-isotope-parent-default');
	$default_grid.imagesLoaded( function(){
		$default_grid.isotope({
			layoutMode: 'masonry',
			masonry: {
				columnWidth: '.hefe-isotope-child-column-width',
				gutter: '.hefe-isotope-gutter-width',
				isFitWidth: true,
			},
			itemSelector: '.hefe-isotope-child',
			stamp: '.hefe-isotope-stamp',
		});
	});

	$(window).resize(function() {
		$('.hefe-isotope-parent.hefe-isotope-parent-default').each(function() {
			$(this).isotope();
		});
	});

})(jQuery);