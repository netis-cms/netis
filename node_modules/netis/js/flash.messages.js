
/*
 * Extension flash messages for Nette ajax.
 */
(function ($) {
	$.nette.ext('flash.messages', {
		load: function() {
			$('.close-message').off('click').on('click', function() {
				$(this).parent().fadeOut('slow');
			});
		}
	});
}(jQuery));
