
/*
 * Extension spinner for Nette ajax.
 */
(function ($) {
	$.nette.ext('spinner', {
		init: function () {
			this.spinner = this.createSpinner();
			this.spinner.appendTo('body');
		},
		start: function () {
			this.spinner.css({
				left: '42%',
				top: '30%'
			});
			this.spinner.show(this.speed);
		},
		complete: function () {
			this.spinner.hide(this.speed);
		}
	}, {
		createSpinner: function () {
			var spinner = $('<div>', {
				id: 'spinner',
				css: {
					display: 'none'
				}
			});
			var icon = $('<i>', {
				class: 'fa fa-spinner fa-pulse fa-3x fa-fw'
			});
			spinner.append(icon);
			return spinner;
		},
		spinner: null,
		speed: undefined
	});
}(jQuery));
