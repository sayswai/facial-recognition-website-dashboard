(function($) {
  "use strict"; //Ensures no undeclared variables are used (ie width=7; is invalid)

  //$('#logForm').on('shown.bs.modal', function() {
  //  $('#logForm').focus();
  //});

  for(var i=1, i<60, i++) {
    if(i%3==0) {
      $(document.createElement('video')).appendTo("#videos-right");
    }
    else if(i%2==0) {
      $(document.createElement('video')).appendTo("#videos-center");
    }
    else {
      $(document.createElement('video')).appendTo("#videos-left");
    }
  }
  //Get all video sources from db
  $.ajax({
    url: 'get_videos.php',
    data: "",

    dataType: 'json',
    success: function(data)
    {
      var videos = data;
    }
  });
  $('video').each(function (index) {
    var id = data[index]
    var url = "vids/"+id+"/"+id;
    $(this).attr({ width: 320, height: 240, src: url });
  });
})(jQuery);
