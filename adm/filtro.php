<?php
/**
 * Created by PhpStorm.
 * User: Equipo
 * Date: 21/05/16
 * Time: 17:18
 */



//Si incluimos el archivo clase.tool.php se envian cabeceras desde config.php y la llamada final a header da error
//include_once "../clase.tool.php";

//Definimos parámetros para el filtro
$origen=$_POST["origen"];
//$param=Tool::limpiaCadena($_POST["Parametro"]);
$param=$_POST["Parametro"];
$columna=$_POST["NombreParametro"];

//Variable de salida
$res="";


//Definimos el formulario de origen desde el que llega la petición al filtro
switch($origen){

    //Filtro del listado de compradores
    case "compradores":

        $jump="./compradores.php";
        switch ($columna){
            case "Email":
                $res="Email='" . $param . "'";
                break;
            case "Nombre":
                $res="Nombre='" . $param . "'";
                break;
            default:
                //Si el filtro no se hace sobre el nombre o email del comprador no se pone ningún filtro
                $res="1";
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
                //Si el nombre de la columna para filtrar no es válido no se pone ningún filtro
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
                //Si el nombre de la columna para filtrar no es válido no se pone ningún filtro
                $res=1;
                break;
        }
    }
        break;

    //Si los parámetros para el filtro son inválidos se vuelve a la interfaz de administración general
    default:
        $res=1;
        $jump="./admin.php";
        break;
}

//Guardamos el filtro en una variable de sesión
session_start();
$_SESSION["Filtro"]=$res;

//Saltamos a la página de origen de la petición de filtro
header("Location:" . $jump . "");
//print_r(error_get_last());

?>