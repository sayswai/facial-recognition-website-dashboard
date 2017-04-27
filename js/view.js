(function($) {
  "use strict"; //Ensures no undeclared variables are used (ie width=7; is invalid)

  $('#logForm').on('shown.bs.modal', function() {
    $('#logForm').focus();
  });
})(jQuery);
