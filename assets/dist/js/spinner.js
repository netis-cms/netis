/**
 * Nette Ajax extension adapted for bootstrap 4
 *
 * Requires
 * @link https://github.com/vojtech-dobes/nette.ajax.js
 */
(function($, undefined) {
  $.nette.ext('nette.spinner', {
    init: function () {
      this.spinner = this.createSpinner();
      this.spinner.appendTo('body');
    },
    start: function () {
      this.spinner.show(this.speed);
    },
    complete: function () {
      this.spinner.hide(this.speed);
    }
  }, {
    createSpinner: function () {
      let spinner;
      return spinner = $('<div>', {
        id: 'ajax-spinner',
        class: 'spinner-border spinner-border-sm text-secondary',
        css: {
          'top': '30%',
          'left': '42%',
          'position': 'absolute',
          'width': '15rem',
          'height': '15rem',
          'z-index': '9999',
          'display': 'none'
        }
      });
    },
    spinner: null,
    speed: undefined
  });
})(jQuery);
