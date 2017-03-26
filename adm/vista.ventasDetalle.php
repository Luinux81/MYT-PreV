<?php

header('Content-Type: text/html; charset=UTF-8');

include_once "../clase.comprador.php";
include_once "../clase.venta.php";
include_once "../clase.evento.php";
include_once "../clase.tool.php";

$err=$_GET['err'];
$res=$_GET['res'];

$accion=$_GET['action'];
$id=$_GET['id'];

session_start();

$eventos=Evento::listarEventos();
$compradores=Comprador::listarCompradores();

switch ($accion){
	case "nuevo":
		$titulo="Nueva Venta";
		$url="./controlador.ventas.php?action=nuevo";
		$boton="Crear venta";
		
		$optionEvento="";
		$aux=" selected ";
		
		foreach ($eventos as $e){
			$optionEvento=$optionEvento . "<option " . $aux . " value='" . $e['IdEvento'] . "' >" . $e['IdEvento'] . " -> " . $e['Nombre'] . "</option>" ;
			$aux="";
		}
		
		$optionComprador="";
		$aux=" selected ";
		
		foreach ($compradores as $c){
			$optionComprador=$optionComprador . "<option " . $aux . " value='" . $c['IdComprador'] . "'>" . $c['Email'] . " " . $c['Nombre'] . " " . $c['Apellidos'] . "</option>";
			$aux="";
		}
		
		break;

	case "editar":
		$titulo="Editar Venta";
		
		$v=new Venta();
		$v=Venta::getVenta($id);
		
		$idVenta=$v->IdVenta;
		$idEvento=$v->IdEvento;		
		$idComprador=$v->IdComprador;
		$importe=$v->importe;
		
		$optionEvento="";
		foreach($eventos as $e){
			$optionEvento=$optionEvento . "<option ";
			
			if($e['IdEvento']==$idEvento){
				$optionEvento=$optionEvento . "selected ";
			}
			
			$optionEvento=$optionEvento . " value='" . $e['IdEvento'] . "'>". $e['IdEvento'] . " -> " . $e['Nombre'] . "</option>";			
		}
		
		$optionComprador="";
		foreach ($compradores as $c){
			$optionComprador=$optionComprador . "<option ";
			
			if($c['IdComprador']==$idComprador){
				$optionComprador=$optionComprador . "selected ";
			}
			
			$optionComprador=$optionComprador . " value='" . $c['IdComprador'] . "'>" . $c['Email'] . " " . $c['Nombre'] . " " . $c['Apellidos'] . "</option>";
		}
		
		$fecha=Tool::adaptaFechaBDaForm($v->fecha);

		$url="./controlador.ventas.php?action=editar";
		$boton="Guardar cambios";
		break;

	default:
		$titulo="Nueva Venta";
		$url="./controlador.ventas.php?action=nuevo";
		$boton="Crear venta";
		
		$optionEvento="";
		$aux=" selected ";
		
		foreach ($eventos as $e){
			$optionEvento=$optionEvento . "<option " . $aux . " value='" . $e['IdEvento'] . "' >" . $e['IdEvento'] . " -> " . $e['Nombre'] . "</option>" ;
			$aux="";
		}
		
		$optionComprador="";
		$aux=" selected ";
		
		foreach ($compradores as $c){
			$optionComprador=$optionComprador . "<option " . $aux . " value='" . $c['IdComprador'] . "'>" . $c['Email'] . " " . $c['Nombre'] . " " . $c['Apellidos'] . "</option>";
			$aux="";
		}
		break;
}

if(isset($_SESSION["username"])){
	echo "<html>
				<header>
				<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
				</header>
				<body><div style='background-color: #fff5c6;'>";
	//cabecera y menu izquierdo
	echo "<div style='width: 100%;text-align: left;background-color: #983030;position: absolute;top: 0px;left: 0px; padding-left: 10px;'>

    <img src='cabecera.jpg' style='float: left;'>
    <h1 style='float: left;padding-left: 10px;'>MYTickets</h1>
    </div>
    <div style='width: 215px;text-align: left;background-color: #708e8b;position: absolute;top: 90px;left: 10px; padding-left: 10px;'>
	" . Tool::menuPrincipal() . "
    </div>
    ";

	echo "<div style='position: absolute;top: 70px;left: 250px;'>
			<h1>" . $titulo . "</h1>
			<form action='" . $url . "' method='POST' >
			
			<label>ID Venta</label><input type='text' name='idventa_venta' value='" . utf8_decode($idVenta) . "'  />
				<div style='clear:both;'></div>
					
			<label>Evento</label><select name='idevento_venta'>
					" . $optionEvento . " 
					</select>		
				<div style='clear:both;'></div>
							
			<label>Comprador</label><select name='idcomprador_venta'>
					" . $optionComprador . " 
					</select>		
				<div style='clear:both;'></div>
							
			<label>Importe</label><input type='text' name='importe_venta' value='" . utf8_decode($importe) . "'  />
				<div style='clear:both;'></div>
		
			<label>Fecha</label><input type='datetime-local' name='fecha_venta' value='" . $fecha . "'  />
				<div style='clear:both;'></div>

			<input type='hidden' name='id_venta' value='" . $v->IdVenta . "' />
			<input type='submit' value='" . $boton . "' />
			</form>
		</div>
		</div>
		</div></body></html>
		";




}
else{
	header("Location:login.php");
}



?>

