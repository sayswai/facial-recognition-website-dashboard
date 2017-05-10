
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

function deleteVideo(vID) {
    vID = vID.replace(/[^\d.]/g,'');
    var data = new FormData();
    data.append('submit', true);
    data.append('vID', vID);

    var connect = new XMLHttpRequest();

    connect.onreadystatechange = function(){
        if(connect.readyState == 4 && connect.status == 200){
            window.location.href = "/index.php";
        }
    };

    connect.open('POST', 'php-functions/delete_video.php', true);
    connect.send(data);
}

function pushVideos(vID) {
    if (vID.length <= 0){
        $('#videos-center').html('<font color="#ff1493">No videos found, start uploading!</font>');
        return false;
    }
    $('#vidTitle').show();
    for (var x = 0; x < vID.length; x++){
        src = "vids/" + vID[x] + "/main.mp4";

        html = "<div class='hVideoColumn'>" +
            " <div class=\"hVideo\">"+
            "   <video width=\"320px\" height=\"240px\" muted>" +
            "       <source src=\"" +src+ "\" type=\"video/mp4\">" +
            "       <source src=\"movie.ogg\" type=\"video/ogg\">" +
            "       Your browser does not support the video tag." +
            "   </video>" +
            "</div>" +
            "<div class='btn-group pull-right'>" +
            "   <button type='button' class='btn btn-danger btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
            "       Delete Video?" +
            "   </button>" +
            "   <div class='dropdown-menu'>" +
            "       <a class='dropdown-item dlt' href='#' id='"+ vID[x]+ "del'>Yes</a>" +
            "   </div>" +
            "</div>" +
            "</div>";
        if(x%3 == 0){
            $('#videos-left').append(html);
        }else if(x%2 == 1){
            $('#videos-center').append(html);
        }else{
            $('#videos-right').append(html);
        }
    }

    $('#greetings').append('<br>Try hovering the mouse over the videos!');
    $(".hVideo").hover( hoverVideo, hideVideo );
    $('.dlt').click(function () {
        deleteVideo(this.id);
    });
    return true;
}

var hoverVideo = function (e) {
    $('video', this).get(0).play();
}

var hideVideo = function (e) {
    $('video', this).get(0).pause();
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



