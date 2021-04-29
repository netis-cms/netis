/**
 * Nette Ajax extension adapted for bootstrap 4
 *
 * Requires
 * @link https://github.com/vojtech-dobes/nette.ajax.js
 */
(function($) {
  $.nette.ext('nette.spinner.btn', {
    start: function() {
      $('.btn-spinner')
          .attr('disabled', 'disabled')
          .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
    },
    complete: function() {
      $('.btn-spinner').removeAttr('disabled');
    }
  });
}(jQuery));
