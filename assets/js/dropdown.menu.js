
/*
 * Extension dropdown menu for Nette ajax.
 */
(function($) {
	$.nette.ext('dropdown.menu', {
		load: function() {

			var dropdown = '.expand-dropdown';
			var employ   = '.employ-toggle';

			$(employ).off('click').on('click', function() {
				$(dropdown).fadeToggle('fast');
				$(dropdown).on('click', function(e) {
					if (!$(e.target).is(employ) && !$(e.target).parents().is(employ)) {
						$(dropdown).hide();
					}
				});
			});
		}
	});
}(jQuery));
