<?php

//ob_start();

include_once __DIR__ . "/../config.php";

include_once SITE_ROOT . "/clase.usuario.php";
include_once SITE_ROOT . "/clase.tool.php";


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