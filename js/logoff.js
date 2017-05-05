/**
 * Created by Wai on 5/5/2017.
 */
function logoff(){
    var connect = new XMLHttpRequest();
    var data = new FormData();
    data.append('submit', true);
    connect.onreadystatechange = function(){
        if(connect.readyState == 4){
            try {
                var resp = JSON.parse(connect.response);
            } catch (e){
                var resp = {
                    status: 'error caught',
                    data: 'Unknown error occurred: ' + connect.responseText +''
                };
            }
            console.log(resp.status + ': ' + resp.data);
        }
    };
    connect.open('POST', 'php-functions/logoff.php');
    connect.send(data);
}

$("#logoff").click(logoff());