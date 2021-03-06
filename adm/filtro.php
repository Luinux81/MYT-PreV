<?php


//Si incluimos el archivo clase.tool.php se envian cabeceras desde config.php y la llamada final a header da error
//include_once "../clase.tool.php";

//Detectamos si los par�metros del filtro llegan por POST o GET y definimos las variables para el filtro

if(isset($_POST["origen"])){
    $origen=$_POST["origen"];
    //$param=Tool::limpiaCadena($_POST["Parametro"]);
    $param=$_POST["Parametro"];
    $columna=$_POST["NombreParametro"];
}
else{
    if(isset($_GET["origen"])){
        $origen=$_GET["origen"];
        //$param=Tool::limpiaCadena($_POST["Parametro"]);
        $param=$_GET["Parametro"];
        $columna=$_GET["NombreParametro"];
    }
}

//Variable de salida
$res="";


//Definimos el formulario de origen desde el que llega la petici�n al filtro
switch($origen){

    //Filtro del listado de compradores
    case "compradores":

        $jump="./vista.compradores.php";
        switch ($columna){
            case "Email":
                $res="UPPER(Email) LIKE '%" . strtoupper($param) . "%'";
                break;
            case "Nombre":
                $res="UPPER(Nombre) LIKE '%" . strtoupper($param) . "%'";
                break;
            default:
                //Si el filtro no se hace sobre el nombre o email del comprador no se pone ning�n filtro
                $res="1";
                break;
        }
        break;

    case "ventas":
    	
    	$jump="./vista.ventas.php";
    	switch($columna){
    		case "Evento":
    			if(trim($param)==""){
    				$res="1";
    			}
    			else{
    				$res="IdEvento=" . $param . " ";
    			}    			
    			break;
    			
    		case "Comprador":
    			if(trim($param)==""){
    				$res="1";
    			}
    			else{
    				$res="IdComprador=" . $param . " ";
    			}
    			break;
    		default:    			
    			$res=1;
    			break;
    	}
    	break;
    	
    //Filtro del listado de compras
    case "compras":

        $jump="./compras.php";
        switch($columna){
            case "Id":
                $res="Id='" . $param . "'";
                break;
            case "Email":
                $res="IdComprador='" . $param . "'";
                break;
            case "Fecha":
                $res="fecha='" . $param . "'";
                break;
            case "Cantidad":
                $res="cantidad='" . $param . "'";
                break;
            case "Importe":
                $res="importe='" . $param . "'";
                break;
            default:
                //Si el nombre de la columna para filtrar no es v�lido no se pone ning�n filtro
                $res=1;
                break;
        }
        break;

    //Filtro del listado de tickets
    case "tickets":{

        $jump="./tickets.php";
        switch($columna){
            case "IdCompra":
                $res="IdCompra='" . $param . "'";
                break;
            case "Codigo":
                $res="Codigo='" . $param . "'";
                break;
            default:
                //Si el nombre de la columna para filtrar no es v�lido no se pone ning�n filtro
                $res=1;
                break;
        }
    }
        break;

    //Filtro del listado de eventos
    case "eventos":{
    	
    	$jump="./eventos.php";
    	switch($columna){
    		case "Id":
    			if(trim($param)==""){
    				$res="1";
    			}
    			else{
    				$res="IdEvento='" . $param . "'";
    			}    			
    			break;
    			
    		case "Nombre":
    			if(trim($param)==""){
    				$res="1";
    			}
    			else{
    				$res="UPPER(Nombre) LIKE '%" . strtoupper($param) . "%'";
    			}    			
    			break;
    			
    		default:
    			//Si el nombre de la columna para filtrar no es v�lido no se pone ning�n filtro
    			$res="1";
    			break;
    		}
    	}
    break;
    
    //Si los par�metros para el filtro son inv�lidos se vuelve a la interfaz de administraci�n general
    default:
        $res=1;
        $jump="./admin.php";
        break;
}

//Guardamos el filtro en una variable de sesi�n
session_start();
$_SESSION["Filtro"]=$res;

//Saltamos a la p�gina de origen de la petici�n de filtro
header("Location:" . $jump . "");
//print_r(error_get_last());

?>