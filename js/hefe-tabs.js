(function($) {

	$('.hefe-tabs-content').not('.hefe-tabs-active').each(function() {
		$(this).hide();
	});
	$('.hefe-tabs-link').on('click touchstart', function (e) {
		var $paired_id = $(this).data('paired');
		$('.hefe-tabs-link').removeClass('hefe-tabs-active');
		$('.hefe-tabs-content').removeClass('hefe-tabs-active');
		$(this).addClass('hefe-tabs-active');
		$('.hefe-tabs-content').not('.hefe-tabs-content[data-paired="' + $paired_id + '"]').hide();
		$('.hefe-tabs-content[data-paired="' + $paired_id + '"]').addClass('hefe-tabs-active').fadeIn();
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

	var $tab_01 = getUrlParameter('tab_01');
	if($tab_01 != ''){
		$('.hefe-tabs-link[data-paired="' + $tab_01 + '"]').click();
	}

	var $tab_02 = getUrlParameter('tab_02');
	if($tab_02 != ''){
		$('.hefe-tabs-link[data-paired="' + $tab_02 + '"]').click();
	}

	var $tab_03 = getUrlParameter('tab_03');
	if($tab_03 != ''){
		$('.hefe-tabs-link[data-paired="' + $tab_03 + '"]').click();
	}

	var $tab_04 = getUrlParameter('tab_04');
	if($tab_04 != ''){
		$('.hefe-tabs-link[data-paired="' + $tab_04 + '"]').click();
	}

	var $tab_05 = getUrlParameter('tab_05');
	if($tab_05 != ''){
		$('.hefe-tabs-link[data-paired="' + $tab_05 + '"]').click();
	}

})(jQuery);