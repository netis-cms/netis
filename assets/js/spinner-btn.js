/*
 * Extension for Nette ajax.
 */
(function($) {
  $.nette.ext('spinner-btn', {
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
