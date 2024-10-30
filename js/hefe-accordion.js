(function($) {

	$('.hefe-accordion-content').not('.hefe-accordion-active').each(function() {
		$(this).hide();
	});
	$('.hefe-accordion-link').on('click touchstart', function (e) {
		var $paired_id = $(this).data('paired');
		$(this).toggleClass('hefe-accordion-active');
		$('.hefe-accordion-content[data-paired="'+$paired_id+'"]').toggleClass('hefe-accordion-active').slideToggle();
		e.preventDefault();
	});

	var getUrlParameter = function getUrlParameter(sParam) {
		var sPageURL = decodeURIComponent(window.location.search.substring(1)),
			sURLVariables = sPageURL.split('&'),
			sParameterName,
			i;

		for (i = 0; i < sURLVariables.length; i++) {
			sParameterName = sURLVariables[i].split('=');

			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : sParameterName[1];
			}
		}
	};

	var $accordion_01 = getUrlParameter('accordion_01');
	if($accordion_01 != ''){
		$('.hefe-accordion-link[data-paired="' + $accordion_01 + '"]').click();
	}

	var $accordion_02 = getUrlParameter('accordion_02');
	if($accordion_02 != ''){
		$('.hefe-accordion-link[data-paired="' + $accordion_02 + '"]').click();
	}

	var $accordion_03 = getUrlParameter('accordion_03');
	if($accordion_03 != ''){
		$('.hefe-accordion-link[data-paired="' + $accordion_03 + '"]').click();
	}

	var $accordion_04 = getUrlParameter('accordion_04');
	if($accordion_04 != ''){
		$('.hefe-accordion-link[data-paired="' + $accordion_04 + '"]').click();
	}

	var $accordion_05 = getUrlParameter('accordion_05');
	if($accordion_05 != ''){
		$('.hefe-accordion-link[data-paired="' + $accordion_05 + '"]').click();
	}

})(jQuery);