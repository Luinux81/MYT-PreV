<?php

//ob_start();


include_once "../clase.usuario.php";
include_once "../clase.tool.php";


session_start();

$username=$_POST['username'];
$pass=$_POST['password'];

if(Usuario::loginValido($username,$pass)){
    $_SESSION["username"]=$username;
    $aux="Location:admin.php";
    //print_r("OK");
}
else{
    $aux="Location:login.php?err";
    //print_r("NO");
}

header($aux);
exit();
?>