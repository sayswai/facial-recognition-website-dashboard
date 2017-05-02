<?php

include 'db_connect.php';
//Database Connection to Postgresql.
$conn1 = connect_db("postgres", "1", "CS160");

if($conn1)
{
    echo "The connection is valid.\n";

}
else{
    echo "The connection is invalid.";
}

class auth
{
    function access()
    {
        if (!empty($_POST)) {
            $usernamez = filter_input(INPUT_POST, 'userr', FILTER_SANITIZE_STRING);
            $passwordz = filter_input(INPUT_POST, 'passs', FILTER_SANITIZE_STRING);
            $encrypt = 'BitchImBadANDBoujieWhipUpTheDopeWithAnUZI';
            $trimmedp = trim($passwordz);
            $PASS = crypt($trimmedp, $encrypt);


            session_start();

            $my_name = $_SESSION['User'] = $usernamez;

            $query = "SELECT * FROM users";
            $result = pg_query($query) or die ("Cannot execute query :$query\n");

            //LEST SEE IF ANY MATCHES IN DATABASE

            $i=2;
            while($col = pg_fetch_row($result))
            {
                //checks both user & pass
                if (strcasecmp(trim($usernamez),trim($col[1])) == 0)
                {
                    $i = 3;
                    if (trim($col[2]) == $PASS) //check password that matches user
                    {
                        $i = 1;
                    }
                }
            }
            //statements
            switch ($i)
            {
                case 1:header("Location: upload.php");
                    break;

                case 2:echo "The Username entered has not been registered, please register";
                    break;

                default:echo "Invalid Login, please try again";
                    break;
            }

        }
    }
}
$grantyes = new auth();
$grantyes->access();
?>
