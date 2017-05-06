<?php
/**
 * Created by PhpStorm.
 * User: Wai
 * Date: 5/5/2017
 * Time: 12:44 PM
 */
session_start();
if(isset($_POST['submit'])){
    error_log('log off called');
    /*Unset all session variables that were set*/
    #session_unset();
    session_destroy();
}
    echo "
        <script lang=\"text/javascript\">
            window.location.href = \"/index.php\";
        </script>
                ";

?>