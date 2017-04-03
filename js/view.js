(function($) {
  "use strict"; //Ensures no undeclared variables are used (ie width=7; is invalid)

  $('#nav').load("html/navbar.html");
  $('#logFormTrigger').click(function() {
    $('.logForm').toggle();
  })
})(jQuery);
