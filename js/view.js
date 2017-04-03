(function($) {
  "use strict"; //Ensures no undeclared variables are used (ie width=7; is invalid)

  $('#nav').load("html/navbar.html"); //Loads common navbar html

  $('#logFormTrigger').click(function() {
    $('.logForm').toggle(); //Toggles logForm visibility
  })

  
})(jQuery);
