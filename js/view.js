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
  var videos = ["fakeVideo", "fakeVideo1", "fakeVideo2"];
  for(i=1; i<31; i++) {
    var html = "<video width=\"320\" height=\"240\" controls><source src=\"vids/" + videos[i-1] + "/finished.mp4\" type=\"video/mp4\">This browser does not support the HTML5 video tag.</video>"
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
