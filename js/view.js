function createVids() {
  $("#log").addClass("log");
  var vid = $("<video width=\"320\" height=\"240\" controls></video>");
  for(i=1; i<61; i++) {
    if(i%3==0) {
      $("#videos-right").html(vid);
    }
    else if(i%2==0) {
      $("#videos-center").html(vid);
    }
    else {
      $("#videos-left").html(vid);
    }
  }
  //Get all video sources from db
//  $.ajax({
//    url: 'php-functions/get_videos.php',
//    data: "",
//
//    dataType: 'json',
//    success: function(data)
//    {
//      var videos = data;
//    }
//  });
var videos = ["fakeVideo"];
  $('video').each(function (index) {
    $(this).append("<source src=\"vids/" + videos[index] + "/finished.mp4\" type=\"video/mp4\">This browser does not support the HTML5 video tag.");
  });
}
