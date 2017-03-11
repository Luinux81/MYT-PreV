<?php

header('Content-Type: text/html; charset=UTF-8');


include_once "../clase.evento.php";
include_once "../clase.tool.php";

$err=$_GET['err'];
$res=$_GET['res'];

$accion=$_GET['accion'];
$id=$_GET['id'];

session_start();

switch ($accion){
	case "nuevo":
		$titulo="Nuevo Evento";
		$url="./accion.crearEvento.php";
		$option="<option selected>Festival</option><option>Club Nocturno</option><option>Otro</option>";
		$boton="Crear evento";
		break;
		
	case "editar":
		$titulo="Editar Evento";
		$e=Evento::getEvento($id);
		$nombre=$e->Nombre;
		$genero=$e->TipoEvento;
		
		switch($genero){
			case "Festival":
				$option="<option selected>Festival</option><option>Club Nocturno</option><option>Otro</option>";
				break;
			case "Club Nocturno":
				$option="<option>Festival</option><option selected>Club Nocturno</option><option>Otro</option>";
				break;
			case "Otro":
				$option="<option selected>Festival</option><option>Club Nocturno</option><option selected>Otro</option>";
				break;
			default:
				$option="<option selected>Festival</option><option>Club Nocturno</option><option>Otro</option>";
				break;
		}
		$lugar=$e->Lugar;
		$direccion=$e->Direccion;
		$ciudad=$e->Ciudad;
		$pais=$e->Pais;
		$aforo=$e->AforoEvento;		

		$inicio=Tool::adaptaFechaBDaForm($e->FechaInicio);
		$fin=Tool::adaptaFechaBDaForm($e->FechaFin);
		
		$url="./accion.editarEvento.php";
		$boton="Guardar cambios";
		break;
		
	default:
		$titulo="Nuevo Evento";
		$url="./accion.crearEvento.php";
		$option="<option selected>Festival</option><option>Club Nocturno</option><option>Otro</option>";
		$boton="Crear evento";
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
			
			<label>Nombre el evento</label><input type='text' id='nombre_evento' name='nombre_evento' value='" . utf8_decode($nombre) . "' />
				<div style='clear:both;'></div>
			
			<label>Genero</label><select  id='genero_evento' name='genero_evento'>
					" . $option . " 
					</select>
				<div style='clear:both;'></div>
			
			<label>Lugar</label><input type='text' name='lugar_evento' value='" . utf8_decode($lugar) . "' />
				<div style='clear:both;'></div>
			
			<label>Direccion</label><input type='text' name='direccion_evento' value='" . utf8_decode($direccion) . "'  />
				<div style='clear:both;'></div>
			
			<label>Ciudad</label><input type='text' name='ciudad_evento'  value='" . utf8_decode($ciudad) . "' />
				<div style='clear:both;'></div>
			
			<label>Pais</label><input type='text' name='pais_evento'  value='" . utf8_decode($pais) . "' />
				<div style='clear:both;'></div>
			
			<label>Entradas a la venta</label><input type='number' name='aforo_evento' value='" . $aforo . "'  />
				<div style='clear:both;'></div>
			
			<label>Inicio</label><input type='datetime-local' name='inicio_evento' value='" . $inicio . "'  />
				<div style='clear:both;'></div>
			
			<label>Fin</label><input type='datetime-local' name='fin_evento' value='" . $fin . "'  />
				<div style='clear:both;'></div>
			<input type='hidden' name='id_evento' value='" . $e->IdEvento . "' />
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

