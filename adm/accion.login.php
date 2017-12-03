<?php

//ob_start();

include_once __DIR__ . "/../config.php";

include_once SITE_ROOT . "/clase.usuario.php";
include_once SITE_ROOT . "/clase.tool.php";


session_start();

$username=$_POST['username'];
$pass=$_POST['password'];

try {
    $aux=Usuario::loginValido($username, $pass);
} 
catch (Exception $e) {
    $aux=false;
    $_SESSION["LastError"]=$e->getMessage();
}

if($aux){
    $_SESSION["username"]=$username;
    $aux="Location:admin.php";
}
else{
    $aux="Location:login.php?err";
}

header($aux);
exit();
?>