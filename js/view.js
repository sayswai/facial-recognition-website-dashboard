
function createVid() {
    var connect = new XMLHttpRequest();
    var data = new FormData();
    data.append('submit', true);
    connect.onreadystatechange = function(){
        if(connect.readyState == 4 && connect.status == 200){
            pushVideos(JSON.parse(connect.responseText));
            }
    };

    connect.open('POST', 'php-functions/get_videos.php', true);
    connect.send(data);
}

function pushVideos(vID) {
    if (vID.length <= 0){
        $('#videos-center').html('<font color="#ff1493">No videos found, start uploading!</font>');
        return false;
    }
    for (var x = 0; x < vID.length; x++){
        src = "vids/" + vID[x] + "/main.mp4";

        html = " <div class=\"hVideo\">"+
            "   <video width=\"320px\" height=\"240px\">" +
            "       <source src=\"" +src+ "\" type=\"video/mp4\">" +
            "       <source src=\"movie.ogg\" type=\"video/ogg\">" +
            "       Your browser does not support the video tag." +
            "   </video>" +
            "</div>";
        if(x == 0){
            $('#videos-left').append(html);
        }else if(x == 1){
            $('#videos-center').append(html);
        }else{
            $('#videos-right').append(html);
        }
    }

    $(".hVideo").hover( hoverVideo, hideVideo );
    return true;
}

var hoverVideo = function (e) {
    $('video', this).get(0).play();
}

var hideVideo = function (e) {
    $('video', this).get(0).pause();
}

$(".hVideo").hover( hoverVideo, hideVideo );


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



