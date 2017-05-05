
/*$username_check = "SELECT username from user where name='$username'";
$username_c_query = pg_query($username_check);
if ($username_c_query) {
    if (pg_fetch_all_columns($username_c_query) > 0) {
        echo '<script language="javascript">';
        echo 'alert("Username already exists!")';
        echo '</script>';
        exit();
    }
} */

function validateUsername(){
    if(//test username input for length...) {
        $.ajax({
            type: 'POST',
            url: 'validate.php',
            data: { username: username },
            success: function(response) {
                if(response==0){
                    //username is valid
                }
                elseif(response==1){
                    //username is already taken
                }
                elseif(response==2){
                    //connection failed
                }

            }
        });
}
else{
    //display "username is too short" error
}

}