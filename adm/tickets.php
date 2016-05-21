<?php
/**
 * Created by PhpStorm.
 * User: Equipo
 * Date: 30/11/14
 * Time: 17:15
 */

include_once "../fpdf.php";
include_once "../clase.tool.php";
include_once "../clase.ticket.php";


$err=$_GET['err'];
$res=$_GET['res'];
$auth=$_GET['pass'];

session_start();

if(isset($_SESSION["username"])){

echo "<div style='background-color: #fff5c6;'>";
//cabecera y menu izquierdo
echo "<div style='width: 100%;text-align: left;background-color: #983030;position: absolute;top: 0px;left: 0px; padding-left: 10px;'>

    <img src='cabecera.jpg' style='float: left;'>
    <h1 style='float: left;padding-left: 10px;'>Gesti&oacute;n de entradas anticipadas</h1>
    </div>
    <div style='width: 215px;text-align: left;background-color: #708e8b;position: absolute;top: 90px;left: 10px; padding-left: 10px;'>
        <a href='./compradores.php?pass=admin'>Compradores</a><br>
        <a href='./compras.php?pass=admin'>Compras</a><br>
        <a href='./tickets.php?pass=admin'>Tickets</a><br>
    </div>
    ";

echo "<div style='position: absolute;top: 70px;left: 250px;'>";
$lista=Ticket::listadoTickets();

if ($err!=""){
    echo "<div style='width: 100%;background-color: #AA0000;'> " . $err . "</div>";
}
if ($res!=""){
    echo "<div style='width: 100%;background-color: #00AA00;'> " . $res . "</div>";
}

echo "<h1>Tickets</h1>";
echo "<h3>Listado completo</h3>";

echo "<table style='width:100%;'><tr>
    <td><a href='listaTickets.php?pass=admin'>Listado Taquilla</a> </td>
    <td></td>
    <td></td>
    </tr></table>";

$aux="";
$col1="#999999";
$col2="#777777";
$aux2=$col1;
$i=0;

echo "<table><tr><th>#</th><th>IdCompra</th><th>Codigo</th></tr>";
foreach($lista as $l){
    $i=$i+1;
    $aux= $aux . "<tr style='background-color:" . $aux2 . ";'><td>" . $i . "</td><td>" . $l['IdCompra']  . "</td>" . "<td>" . $l['Codigo']  . "</td>";
    $aux =$aux . "</tr>";

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

