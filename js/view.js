function createVids() {

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
  var videos = [["fakeVideo", "video1"], ["fakeVideo1", "video2"], ["fakeVideo2", "video3"]];
  for(i=1; i<31; i++) {
    var html = "<video width=\"320\" height=\"240\" controls><source src=\"vids/" + videos[i-1][0] + "/finished.mp4\" type=\"video/mp4\">This browser does not support the HTML5 video tag.</video><br><a href=\"https://sayswaiy.ddns.net/"+ videos[i-1][0] +"\"><p class=\"center-text\">"+ videos[i-1][1] +"</p></a>";
    if (i % 3 == 0) {
      $("#videos-right").append(html);
    } else if (i % 2 == 0) {
      $("#videos-center").append(html);
    } else {
      $("#log").addClass("log");
      $("#videos-left").append(html);
    }
  }
}

function createVid() {
  var vID = window.location.href.slice(26);
  //Get video source from db
//  $.ajax({
//    url: 'php-functions/get_video.php',
//    type: "POST",
//    data: { vID : vID },
//
//    dataType: 'json',
//    success: function(data)
//    {
//      var video = data;
//    }
//  });

  var html = "<video width=\"1280\" height=\"960\" controls><source src=\"vids/" + vID + "/finished.mp4\" type=\"video/mp4\">This browser does not support the HTML5 video tag.</video><br><h2 class=\"center-text\">"+ video[1] +"</h2>";
  $("#video").append(html);
}

$(function() {
  $("#logForm").on("hidden.bs.modal", function() {
    $(this).find("input").val('');
    $(this).find("input[type=checkbox]").prop("checked", "");
  });
  $("#signForm").on("hidden.bs.modal", function() {
    $(this).find("input").val('');
    grecaptcha.reset();
  });
});
