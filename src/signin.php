<?php
include ('../config/database.php');//conecto a la base de datos
$email =$_POST['e_mail'];// valido usuario y contraseña su existencia
$passw=$_POST['p_swwd'];
$sql = "
SELECT
        id,
        email,
        password
FROM
        users
WHERE
        email='$email' and
        password ='$pssw' and 
        status = true;
        "
        $res = pg_query($conn, $sql);
        if($res){
            $row = pg_fetch_assoc($res);
            if ($row['total']>0){
                echo "Login OK";
            }else{
                echo "Login failed"
            }

        }
?>