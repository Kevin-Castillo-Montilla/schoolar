<?php
include('../config/database.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List.Users</title>
<link rel="stylesheet" href="../Listuser.css">
</head>
<li><a href="home.php">Volver</a></li>
<body>
    <table border="1" align="center">
        <tr>
            <td>firstName</td>
            <td>lastName</td>
            <td>E-mail</td>
            <td>status</td>
            <td>photo</td>
            <td>...</td>
        </tr>
        <?php
        //code
$sql = "
    SELECT
        firstname,
        lastname,
        email,
        case when status= true then 'active' else 'No active' end as status
    from 
        users 
        ";
        $res = pg_query($conn,$sql);
        if (!$res){
            echo"Querry error";
            exit;
        }
        while($row=pg_fetch_assoc($res)){
          echo" <tr>";
          echo" <td>".$row ['firstname']."</td>";
          echo" <td>".$row ['lastname']."</td>";
          echo" <td>".$row ['email']."</td>";
          echo" <td>".$row ['status']."</td>";
          echo" <td alling='center'><img src = 'photo_users/masculino.png' width=40></td>";
          echo" <td>";
          echo" <a href=''><img src ='icons/edicion.png' width ='20'></a>";
          echo" <a href=''><img src ='icons/bote-de-basura-mas.png' width ='20'></a>";
          echo" <a href='http://localhost/schoolar/src/delte.php'><img src ='icons/vaso.png' width= '20' > </a>";
          echo" </td>";
          echo" </tr> ";
        }
        ?>
</table>
</body>
</html>
<?php
