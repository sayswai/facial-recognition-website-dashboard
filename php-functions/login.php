<?php
session_start();
include 'db_connect.php';
include '../configs/Config.php';
//Database Connection to Postgresql.
$conn1 = connect_db(\dbUsername, \dbPassword, \dbDBname);


if(!$conn1)
{
    error_log('DB connection problem :: login.php');
}


class auth
{
    function nounce(){
        $randomchars = 'aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ0123456789!@#$%^&*()_+{}|:?>[]\?<"';
        $charstrlen = strlen($randomchars);
        $output = '';
        //GENERATE THE RANDOM CHARS TO OUTPUT
        for ($a=0; $a<10; $a++) {
            $output .= $randomchars[rand(0,$charstrlen-1)];
        }
        return $output;
    }

    function access()
    { $usernounce = $this->nounce();
        if (isset($_POST['submit'])) {
            $usernamez = filter_input(INPUT_POST, 'userr', FILTER_SANITIZE_STRING);
            $passwordz = filter_input(INPUT_POST, 'passs', FILTER_SANITIZE_STRING);
            $trimmedp = trim($passwordz);
            $PASS0 = hash('sha512',$trimmedp);
            $PASS = trim($PASS0);
            $query = "SELECT * FROM users";
            $result = pg_query($query) or die ("Cannot execute query :$query\n");



            //LEST SEE IF ANY MATCHES IN DATABASE

            $i=2;
            while($col = pg_fetch_row($result))
            {
                //checks both user & pass
                if (strcasecmp(trim($usernamez),trim($col[1])) == 0) //check if username matches user in the database
                {
                    $i = 100;
                    if (trim($col[2]) == $PASS) //check password that matches user
                    {
                        $i = 1;
                        $_SESSION['username'] = $usernamez;
                    }
                }
            }
            //statements
            switch ($i)
            {
                #case 1:header("Location: upload.php");break; //GOOD!
                case 1: echo "0";break;
                case 2: echo "Username not found, please register if you haven't!";break;
                default:echo "Invalid Authentication!";break;
            }
        }
    }
}
$grantyes = new auth();
$grantyes->nounce();
$grantyes->access();
?>
