<?php
/**
 * Created by PhpStorm.
 * User: Equipo
 * Date: 26/11/14
 * Time: 12:19
 */
include_once "../clase.compra.php";
include_once "../clase.comprador.php";
include_once "../clase.tool.php";

$err=$_GET['err'];
$res=$_GET['res'];

session_start();

if(isset($_SESSION["username"])){

    echo "<div style='background-color: #fff5c6;'>";
    //cabecera y menu izquierdo
    echo "<div style='width: 100%;text-align: left;background-color: #983030;position: absolute;top: 0px;left: 0px; padding-left: 10px;'>

    <img src='cabecera.jpg' style='float: left;'>
    <h1 style='float: left;padding-left: 10px;'>Gesti&oacute;n de entradas anticipadas</h1>
    </div>
    <div style='width: 215px;text-align: left;background-color: #708e8b;position: absolute;top: 90px;left: 10px; padding-left: 10px;'>
	" . Tool::menuPrincipal() . "
    </div>
    ";

    echo "<div style='position: absolute;top: 70px;left: 250px;'>";
    $c=new Compra();
    $lista=$c->listadoCompras($_SESSION["Filtro"]);


    echo "<h1>Compras</h1>
            <form action='./filtro.php' name='filtro_compras' method='post'>
                <select name='NombreParametro'>
                    <option>Id</option>
                    <option>Email</option>
                    <option>Fecha</option>
                    <option>Cantidad</option>
                    <option>Importe</option>
                </select>
                <input type='text' name='Parametro'>
                <input type='submit' name='Filtrar'>
                <input type='hidden' name='origen' value='compras'>
            </form>";

    if($_SESSION["Filtro"]=="" || $_SESSION["Filtro"]=="1"){
        echo "<h3>Listado completo</h3>";
    }else{
        echo "<h3>Listado filtrado</h3>";
        $_SESSION["Filtro"]="1";
    }

    if ($err!=""){
        echo "<div style='width: 100%;background-color: #AA0000;'> " . $err . "</div>";
    }
    if ($res!=""){
        echo "<div style='width: 100%;background-color: #00AA00;'> " . $res . "</div>";
    }

    echo "<table style='width:100%;'><tr>
        <td><a href='addCompra.php?pass=admin'><img src='png/nuevo.png'>Nuevo</a> </td>
        <td></td>
        <td></td>
        </tr></table>";

    $aux="";
    $col1="#999999";
    $col2="#777777";
    $aux2=$col1;
    $i=0;

    echo "<table><tr><th>#</th><th>Id</th><th>Comprador</th><th>Fecha</th><th>Cantidad</th><th>Importe</th><th></th></tr>";
    foreach($lista as $l){

        $i=$i+1;

        $aux= $aux . "<tr style='background-color:" . $aux2 . ";'><td>" . $i . "</td><td><a href='./muestraTicket.php?code=" .$l['Id'] . "'>" . $l['Id']  . "</a></td>" . "<td>" . $l['IdComprador']  . "</td>" . "<td>" . $l['Fecha']  . "</td>" . "<td>" . $l['Cantidad']  . "</td>" . "<td>" . $l['Importe']  . "</td>";
        $aux =$aux . "<td><a href='./editCompras.php?id=" . $l['Id'] . "&pass=admin'><img src='png/editar.png'>Editar</a>
        <a href='./accion.delCompra.php?id=" . $l['Id'] . "&pass=admin'><img src='png/borrar.png'>Borrar</a>
        <a href='./addTicket.php?id=" . $l['Id'] . "&pass=admin'><img src='png/addTicket.png'>A&ntilde;adir tickets</a>
        <a href='./delTickets.php?id=" . $l['Id'] . "&pass=admin'><img src='png/delTicket.png'>Eliminar Tickets</a>
        <a href='./accion.enviaTickets.php?id=" . $l['Id'] . "&pass=admin'><img src='png/enviar.png'>Enviar</a></td></tr>
        ";

        if($aux2==$col1){
            $aux2=$col2;
        }
        else{
            $aux2=$col1;
        }

    }
    echo $aux;

    echo "</table>";


    echo "</table>
    </div>
    </div>";

}
else{
    header("Location:login.php");
}
?>