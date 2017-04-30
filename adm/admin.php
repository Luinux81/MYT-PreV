<?php
include_once __DIR__ . "/../config.php";

include_once SITE_ROOT . "/clase.tool.php";

session_start();

if(isset($_SESSION["username"])){
    //cabecera y menu izquierdo	
	echo Tool::inicioDocWeb();
	
    echo "
    		<div id='chart_div'></div>
  		</body>    		
    ";
}
else{
    header("Location:login.php");
}


?>