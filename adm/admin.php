<?php
include_once __DIR__ . "/../config.php";

include_once SITE_ROOT . "/clase.tool.php";

session_start();

if(isset($_SESSION["username"])){
    //cabecera y menu izquierdo	
	echo Tool::inicioDocWeb(true);
	
    echo "
    		<div id='chart_div'></div>
    	";
    
    echo Tool::finDocWeb();
}
else{
    header("Location:login.php");
}


?>