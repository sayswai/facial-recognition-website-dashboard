<?php

include 'db_connect.php';
//Database Connection to Postgresql.
$conn1 = connect_db("postgres", "1", "CS160");

if($conn1)
{
    echo "The connection is valid.";
    echo ":)";
}
else{
    echo "The connection is invalid.";
}

class auth
{
    function access()
    {
        if (!empty($_POST)) {
            $usernamez = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $passwordz = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
            $encrypt = 'BitchImBadANDBoujieWhipUpTheDopeWithAnUZI';
            session_start();

            $my_name = $_SESSION['User'] = $usernamez;

            $query = "SELECT * FROM users";
            $result = pg_query($query);

            //LEST SEE IF ANY MATCHES IN DATABASE
            $i = 0;

            while ($col = pg_fetch_row($result))
            {
                //checks both user & pass
                if (strcasecmp($usernamez, $col[1]) == 0)
                {
                    $i++;
                if ($col[2] == crypt($passwordz, $encrypt)) //check password that matches user
                {
                    $i = 0;
                }
            }
            }

            //statements
            switch ($i) {
                case 0: //login good
                    header("Location: upload.php"); //redirect
                    break;

                case 1: //no never found
                    echo "The Username entered has not been registered, please register";
                    break;

                default:
                    echo "Invalid Login, please try again";
                    break;
            }
        }
    }
}
$grantyes = new auth();
$grantyes->access();
?>
