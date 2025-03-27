<?php
//lamar el archivo
include ('../config/database.php');
$fname  = $_POST['f_name'];
$lname  = $_POST['l_name'];
$email  = $_POST['e_mail'];
$passwd = $_POST['passw'];
//crear sql 
$sql = "INSERT INTO users(firstname,lastname,email,password)
    VALUES('$fname','$lname','$email','$passwd')
";
$res = pg_query($conn, $sql);
if ($res){
    echo"User has been created succesful";
}else{
    echo"Error";
}
?>