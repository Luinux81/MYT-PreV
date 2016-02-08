<?php
include_once "../clase.usuario.php";

/**
 * Created by PhpStorm.
 * User: Equipo
 * Date: 1/12/14
 * Time: 11:58
 */

session_start();

$username=$_POST['username'];
$pass=$_POST['password'];

if(Usuario::loginValido($username,$pass)){
    $_SESSION["username"]=$username;
    $aux="Location:admin.php";
}
else{
    $aux="Location:login.php";
}

header($aux);
?>