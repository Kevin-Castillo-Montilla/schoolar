<?php
//config connection
$host = "aws-0-us-east-1.pooler.supabase.com";
$port = "6543";
$dbname = "postgres";
$user = "postgres.fyaevelnvphpaymvcpiv";
$password = "1080040202";

/*$host = "localhost";
$port = "5432";
$dbname = "schoolar";
$user = "postgres";
$password = "unicesmag";
*/
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
pg_close();//CIERRA LA BASE DE DATOS Y SOLO $CONN ES LA LLAVE MAESTRA

?>