<?php
//config connection
$host = "localhost";
$port = "5432";
$dbname = "schoolar";
$user = "postgres";
$password = "unicesmag";
//create connection psql(parametros)
$conn = pg_connect("
host = $host
port = $port
dbname = $dbname
user = $user
password = $password 
");
if (!$conn){//conecion error
die("connection error: ". pg_last_error());
}
else {//connection true
    echo "success connection";
}
pg_clouse();//CIERRA LA BASE DE DATOS Y SOLO $CONN ES LA LLAVE MAESTRA

?>