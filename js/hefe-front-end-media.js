(function($) {

	$(document).ready( function() {
		var hefe_front_end_media_modal;
		
		$('.hefe-front-end-media-link').on('click', function(event){
			event.preventDefault();

			var attachment_id = $(this).data('attachment');

			hefe_front_end_media_modal = wp.media.frames.hefe_front_end_media_modal = wp.media({
				title: 'Front End Media',
				button: {
					text: 'Close',
				},
				multiple: false
			});

			if (typeof attachment_id != 'undefined') {

				hefe_front_end_media_modal.on( 'open', function() {

					var selection = hefe_front_end_media_modal.state().get('selection');
					attachment = wp.media.attachment(attachment_id);
					attachment.fetch();
					selection.add( attachment ? [ attachment ] : [] );

				});

			}

			hefe_front_end_media_modal.open();

		});

	});

})(jQuery);