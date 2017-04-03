(function($) {
  "use strict"; //Ensures no undeclared variables are used (ie width=7; is invalid)

  $('#nav').load("html/navbar.html"); //Loads common navbar html
  $('#logForm').on('shown.bs.modal', function() {
    $('#logForm').focus();
  });
  $('.close').click(function() {
    $('#logForm').toggle();
  });
})(jQuery);
