/**
 * Created by Wai on 5/5/2017.
 */

/*JS variables*/

var _submit = document.getElementById('submitNow'),
    _file = document.getElementById('userFile'),
    _progress = document.getElementById('uploadProgressBar'),
    _name = document.getElementById('uploadName'),
    _percent = document.getElementById('uploadPercent'),
    _extensions = ["mp4", "mpg", "mov", "mpeg", "avi", "wmv"],
    _size = 10000000;


/*JS functions*/

function startRender (vid){
    vid = vid.trim();
    var data = new FormData();
    var connect = new XMLHttpRequest();
    data.append('submit', true);
    data.append('vID', vid);
    connect.open('POST', 'php-functions/call_v.php', true);
    connect.send(data);
}

var upload = function () {
    $('#uploadProgressBar').show();
    var data = new FormData();
    data.append('userUpload', _file.files[0]);
    data.append('submit', true);

    var connect = new XMLHttpRequest();
    connect.onreadystatechange = function(){
        if(connect.readyState == 4 && connect.status == 200){
            $('#outputWrapper').show();
            if(connect.responseText == "1"){
                $('#realOutput').html("Something went wrong saving to DB, upload failed.");
                return false;
            }else if(connect.responseText == "2"){
                $('#realOutput').html("Changing the file extension in the filename won't work here. <br>An infraction has been added to your account. <br>One more and you'll be banned.");
                return false;
            }else if(connect.responseText == "3"){
                $('#realOutput').html("Upload failed, apache related");
                return false;
            }
            $('#realOutput').append("<div class='text-center'>");
            $('#realOutput').html("Upload successfully completed!<br>Face recognition has started.<br>");
            $('#realOutput').append('<br> Click \'My Videos\' to view your video right now!');
            $('#realOutput').append("</div>");
            $('#goToVideos').show();
            startRender(connect.responseText);
        }
    };

    connect.upload.addEventListener('progress', function(e){
        var progg = Math.round((e.loaded / e.total) * 100);
        $('#uploadProgressBar').css('width', progg+'%').attr('aria-valuenow', progg).html('..extremely smooth fps..');
        if(progg > 40 && progg <= 60){
            $('#uploadProgressBar').html(' just kidding ');
        }else if(progg > 60 && progg <= 80){
            $('#uploadProgressBar').html(' i\'ve seen better ');
        }else if(progg > 80 && progg < 100){
            $('#uploadProgressBar').html('we\'re almost done here..');
        }
        if(progg >= 100){
            $('#uploadProgressBar').css('width', '100%').attr('aria-valuenow', '100').html('Such an interesting video : ) pumping out results..').addClass('bg_success');
        }
    }, false);

    connect.upload.addEventListener('load', function(e) {
        $('#outputWrapper').show();
        $('#realOutput').html('..just a few more seconds..');
        $("#newUpload").show();
    }, false);

    connect.open('POST', 'php-functions/upload.php', true);
    $('#userFileDiv').hide();
    connect.send(data);
};

var numOfUpload = function (){
    var data = new FormData();
    data.append('submit', true);

    var connect = new XMLHttpRequest();
    connect.onreadystatechange = function(){
        if(connect.readyState == 4 && connect.status == 200){
            if (connect.responseText >= 3){
                $('#uploadResult').html('At the moment, we are only accepting 3 videos. <br> Please delete existing videos to upload more.');
                return false;
            }
            upload();
        }
    };

    connect.open('POST', 'php-functions/numOfUploads.php', true);
    connect.send(data);
};

var login = function () {
    var v1 = $.trim($('#userr').val());
    var v2 = $.trim($('#passs').val());

    if (!v1 || !v2){
        $('#loginOutput').html("Missing fields");
        if (!v1) {
            $('#userr').css("border-color", "#DF3D82");
        }
        if (!v2){
            $('#passs').css("border-color", "#DF3D82");
        }
        return;
    }

   // $('#loginOutput').html("Trying");

    var data = new FormData();
    data.append('submit', true);
    data.append('userr', v1);
    data.append('passs', v2);

    var connect = new XMLHttpRequest();
    connect.onreadystatechange = function(){
        if(connect.readyState == 4 && connect.status == 200){
            if (connect.responseText.trim() == "0"){
                $('#loginOutput').html('Login Successful!');
                setTimeout(function(){
                    window.location.href = "/index.php";
                }, 500);
                $('#userr').css("border-color", "#43df0c");
                $('#passs').css("border-color", "#43df0c");
            }else{
                $('#loginOutput').html(connect.responseText);
                $('#passs').css("border-color", "#DF3D82");
                $('#userr').css("border-color", "#DF3D82");
            }
        }
    };
    connect.open('POST', 'php-functions/login.php', true);
    connect.send(data);
};

var logoff = function(){
    var connect = new XMLHttpRequest();
    var data = new FormData();
    data.append('submit', true);
    connect.onreadystatechange = function(){
        if(connect.readyState == 4 && connect.status == 200){
            window.location.href = "/index.php"
        }
    };
    connect.open('POST', 'php-functions/logoff.php', true);
    connect.send(data);
};

var signUp = function(){
    $('#signUpOutput').html('');
    if(/\s/.test($('#fname').val()) || /\s/.test($('#lname').val()) || /\s/.test($('#uname').val())){
        $('#signUpOutput').html('No white spaces allowed, check ');
        if(/\s/.test($('#fname').val())){
            $('#signUpOutput').append('First Name ');
        }
        if(/\s/.test($('#lname').val())){
            $('#signUpOutput').append('Last Name ');
        }
        if(/\s/.test($('#uname').val())){
            $('#signUpOutput').append('Username ');
        }
        return false;
    }
    if($('#fname').val().length == 0 || $('#lname').val().length == 0 || $('#uname').val().length == 0){
        $('#signUpOutput').html('Missing fields');
        return false;
    }

    var data = new FormData();
    data.append('insert', true);
    data.append('uname', $('#uname').val());
    data.append('pas', $('#pas').val());
    data.append('fname', $('#fname').val());
    data.append('lname', $('#lname').val());

    //reCaptcha check
    var response = grecaptcha.getResponse();

    if(response.length == 0){
    //reCaptcha not verified

        $('#signUpOutput').html('reCaptcha!');

    }else{
    //reCaptcha work

    var connect = new XMLHttpRequest();
    connect.onreadystatechange = function(){
        if(connect.readyState == 4 && connect.status == 200){
            if(connect.responseText == 23505){
                $('#signUpOutput').html('Username already exists.');
                return false;
            }
                //$('#signUpOutput').html("Registration Successful! Proceed to <a href=\"#logForm\" data-dismiss=\"modal\" data-toggle=\"modal\">login<\/a>");
            setTimeout(function(){
                $('#signForm').modal('hide');
                $('#logForm').modal('show');
            }, 700);
            $('#signUpBody').html('Registration Successful!');
        }
    };


    connect.open('POST', 'php-functions/user.php', true);
    connect.send(data);
}
};

var signOnPass = function(){
    if ($('#pas').val() != $('#repas').val() || $('#pas').val().length <= 0 || $('#repas').val().length <= 0){
        $('#signUpSubmit').prop('disabled', true);
        $('#pas').css('border-color', '#df3d82');
        $('#repas').css('border-color', '#df3d82');
        $('#signUpOutput').html('Passwords do not match');
    }else {
        $('#signUpSubmit').prop('disabled', false);
        $('#pas').css('border-color', '#43df0c');
        $('#repas').css('border-color', '#43df0c');
        $('#signUpOutput').html('');
    }
};



/*jQuery*/

/*Log In & Log Out Related*/
$('#logOff').click(logoff);
$('#loginForm').on('click', function(e){
    e.preventDefault();
});
$('#loginSubmit').click(login);

/*Upload form Related*/
$("#userFile").change(function () {
    var size = _file.files[0].size;
    var ext = _file.files[0].name.split('.').pop();

    if (size > _size || jQuery.inArray(ext, _extensions) == -1){
        $('#submitNow').prop('disabled', true);
        $('#uploadName').css('color', '#df0719');
        $('#outputWrapper').show();
        if(size > _size){
            $('#realOutput').html('File size is too big!');
        }else{
            $('#realOutput').html('Unsupported file format!');
        }
    }else{
        $('#uploadName').css('color', '#14df2a');
        $('#submitNow').prop('disabled', false);
        $('#outputWrapper').hide();
    }

    var strr = _file.files[0].name;
    strr += '[' + parseFloat(_file.files[0].size/1000000).toFixed(2)+ ' MB]';
    //_name.innerHTML = _file.files[0].name + '[' + parseFloat(_file.files[0].size/1000000).toFixed(2)+ ' MB]';
    $('#uploadName').html(strr);
});
$('#newUpload').click(function () {
    document.getElementById('userFile').value = "";
    document.getElementById('uploadName').innerHTML = "";
    document.getElementById('uploadPercent').innerHTML = "";
    document.getElementById('uploadResult').innerHTML = "";


    $('#uploadProgressBar').css('width', '0%').hide();
    $('#realOutput').html("");
    $('#uploadName').css('color', '#000000');
    $(this).hide();

    $('#submitNow').prop('disabled', false);
    $('#outputWrapper').hide();
    $('#userFileDiv').show();
});
$("#submitNow").click(function () {
    if(_file.files.length === 0){
        _name.innerHTML = "Please choose a file first";
        return;
    }
    $('#submitNow').prop('disabled', true);
    numOfUpload();
});
$('#goToVideos').click(function () {
    window.location.href='/index.php';
});

/*Sign Up Form Related*/
$('#signUpForm').on('click', function(e){
    e.preventDefault();
});
$('#signUpSubmit').click(signUp);
$('#pas').keyup(signOnPass);
$('#repas').keyup(signOnPass);
