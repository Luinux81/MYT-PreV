<?php

include_once "../clase.tool.php";

session_start();

if(isset($_SESSION["username"])){
    //cabecera y menu izquierdo
    echo "<div style='width: 100%;text-align: left;background-color: #983030;position: absolute;top: 0px;left: 0px; padding-left: 10px;'>

    <img src='cabecera.jpg' style='float: left;'>
    <h1 style='float: left;'>MYT Tickets</h1>
    </div>
    <div style='width: 200px;text-align: left;background-color: #708e8b;position: absolute;top: 90px;left: 10px; padding-left: 10px;'>
        " . Tool::menuPrincipal() . "
    </div>
        		aaaaa
    ";
}
else{
    header("Location:login.php");
}



?>