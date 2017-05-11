
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
        /* For later
        html = "<div class='card' style='position: absolute; background-color: #47494b; margin-top: 2px;'>" +
            "<div class='card-img-top' style='margin-top: 2px;'>" +
            "   <div class=\"hVideo\">"+
            "   <video width=\"320px\" height=\"240px\" muted>" +
            "       <source src=\"" +src+ "\" type=\"video/mp4\">" +
            "       <source src=\"movie.ogg\" type=\"video/ogg\">" +
            "       Your browser does not support the video tag." +
            "       </video>" +
            "   </div>" +
            "</div>" +
            "<div class='card-block'>" +
            "   <h2 class='card-title'>Video</h2>" +
            "   <div class='card-text'>" +
            "       <div class='btn-group pull-right'>" +
            "           <button type='button' class='btn btn-danger btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
            "               Delete Video?" +
            "           </button>" +
            "           <div class='dropdown-menu'>" +
            "            <a class='dropdown-item dlt' href='#' id='"+ vID[x]+ "del'>Yes</a>" +
            "           </div>" +
            "       </div>" +
            "   </div>" +
            "   </div>" +
            "</div>";
            */

        html = "<div class='hVideoColumn'>" +
            " <div class=\"hVideo\" id='" +vID[x]+ "vid'>"+
            "   <video width=\"320px\" height=\"240px\" muted >" +
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

    $('#greetings').append('<br>Try hovering your mouse over the videos!<br>Click on any video you want to watch!');
    $(".hVideo").hover( hoverVideo, hideVideo );
    $('.dlt').click(function () {
        deleteVideo(this.id);
    });
    $('.hVideo').click(function(){
        vID = this.id.replace(/[^\d.]/g,'');
        src = "vids/" + vID + "/main.mp4";
        html = "<video class='video' controls width='100%' height='auto'>" +
            "<source src='" + src + "' type='video/mp4'>" +
            "</video>"
        $('#videoPlayerBody').html(html);
        $('#videoPlayer').modal('show');
        $('video', $('#videoPlayer')).get(0).play()
    });
    return true;
}

var hoverVideo = function (e) {
    $('video', this).get(0).play();
}

var hideVideo = function (e) {
    $('video', this).get(0).pause();
}




$("#logForm").on("hidden.bs.modal", function() {
    $(this).find("input").val('');
    $(this).find("input[type=checkbox]").prop("checked", "");
});
$("#signForm").on("hidden.bs.modal", function() {
    $(this).find("input").val('');
    grecaptcha.reset();
});
$('.modal').on('show.bs.modal', function() {
    $('.modal .modal-body').css('overflow-y', 'auto');
    $('.modal .modal-body').css('max-height', $(window).height() * 0.7);
});
$('#videoPlayer').on("hidden.bs.modal", function(e) {
    $('video', this).get(0).pause();
});
$('#videoPlayerBody').click(function(e) {
    $('video', this).get(0).paused ? $('video', this).get(0).play() : $('video', this).get(0).pause();
});

$(function() {
    function reposition() {
        var modal = $(this),
            dialog = modal.find('.modal-dialog');
        modal.css('display', 'block');
        dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 3));
    }
    $('.modal').on('show.bs.modal', reposition);
    $(window).on('resize', function() {
        $('.modal:visible').each(reposition);
    });
});

