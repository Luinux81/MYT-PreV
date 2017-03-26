<?php

include_once "../clase.venta.php";
include_once "../clase.tool.php";

$err=$_GET['err'];
$res=$_GET['res'];

session_start();

if(isset($_SESSION["username"])){
	echo "<div style='background-color: #fff5c6;'>";
	//cabecera y menu izquierdo
	echo "<div style='width: 100%;text-align: left;background-color: #983030;position: absolute;top: 0px;left: 0px; padding-left: 10px;'>

    <img src='cabecera.jpg' style='float: left;'>
    <h1 style='float: left;padding-left: 10px;'>MYTickets</h1>
    </div>
    <div style='width: 215px;text-align: left;background-color: #708e8b;position: absolute;top: 90px;left: 10px; padding-left: 10px;'>
	" . Tool::menuPrincipal() . "
    </div>
    ";

	echo "<div style='position: absolute;top: 70px;left: 250px;'>";


	echo "<h1>Ventas</h1>
          <form action='./filtro.php' name='filtro_ventas' method='post'>
            <select name='NombreParametro'>
                <option>Email</option>
                <option>Nombre</option>
            </select>
            <input type='text' name='Parametro'>
            <input type='submit' name='Filtrar'>
            <input type='hidden' name='origen' value='ventas'>
          </form>
    ";

	if($_SESSION["Filtro"]=="" || $_SESSION["Filtro"]=="1"){
		echo "<h3>Listado completo</h3>";
		$_SESSION["Filtro"]="1";
	}else{
		echo "<h3>Listado filtrado</h3>";
	}

	$lista=Venta::listarVentas($_SESSION["Filtro"]);
	$_SESSION["Filtro"]="1";

	if ($err!=""){
		echo "<div style='width: 100%;background-color: #AA0000;'> " . $err . "</div>";
	}
	if ($res!=""){
		echo "<div style='width: 100%;background-color: #00AA00;'> " . $res . "</div>";
	}

	echo "<table style='width:100%;'><tr>
    <td><a href='vista.ventasDetalle.php?accion=nuevo'>Nuevo</a> </td>
    <td></td>
    <td></td>
    </tr></table>";

	$aux="";
	$col1="#999999";
	$col2="#777777";
	$aux2=$col1;
	$i=0;

	
	echo "<table><tr><th>Fecha</th><th>IdVenta</th><th>IdEvento</th><th>IdComprador</th><th>Importe</th><th></th></tr>";
	foreach($lista as $l){
		$i=$i+1;
		$aux= $aux . "<tr style='background-color:" . $aux2 . ";'>
        		<td>" . $l['Fecha'] . "</td>
        		<td>" . $l['IdVenta']  . "</td>
        		<td>" . $l['IdEvento']  . "</td>
        		<td>" . $l['IdComprador']  . "</td>
        		<td>" . $l['Importe']  . "</td>
        		
        		<td>
        			<a href='./vista.ventasDetalle.php?action=editar&id=" . $l['IdVenta'] . "'>Editar</a>
        			<a href='./controlador.ventas.php?action=archivar&id=" . $l['IdVenta'] . "'>Archivar</a>
        			<a href='./controlador.ventas.php?action=borrar&id=" . $l['IdVenta'] . "'>Borrar</a>
        		</td></tr>";

		if($aux2==$col1){
			$aux2=$col2;
		}
		else{
			$aux2=$col1;
		}
	}
	echo $aux;

	echo "</table>
    </div>
    </div>";

}
else{
	header("Location:login.php");
}


?>
