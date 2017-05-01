<?php

header('Content-Type: text/html; charset=UTF-8');

include_once __DIR__ . "/../config.php";
include_once SITE_ROOT . "/clase.evento.php";
include_once SITE_ROOT . "/clase.tool.php";

$err=$_GET['err'];
$res=$_GET['res'];

$accion=$_GET['accion'];
$id=$_GET['id'];

session_start();

switch ($accion){
	case "nuevo":
		$titulo="Nuevo Evento";
		$url="./controlador.eventos.php?action=nuevo";
		$optionEstado="<option selected>Activo</option><option>Inactivo</option><option>Agotadas</option><option>Cancelado</option>";
		$optionGenero="<option selected>Festival</option><option>Club Nocturno</option><option>Otro</option>";
		$boton="Crear evento";
		break;
		
	case "editar":
		$titulo="Editar Evento";
		$e=Evento::getEvento($id);
		$nombre=$e->Nombre;
		$genero=$e->TipoEvento;
		
		switch($genero){
			case "Festival":
				$optionGenero="<option selected>Festival</option><option>Club Nocturno</option><option>Otro</option>";
				break;
			case "Club Nocturno":
				$optionGenero="<option>Festival</option><option selected>Club Nocturno</option><option>Otro</option>";
				break;
			case "Otro":
				$optionGenero="<option selected>Festival</option><option>Club Nocturno</option><option selected>Otro</option>";
				break;
			default:
				$optionGenero="<option selected>Festival</option><option>Club Nocturno</option><option>Otro</option>";
				break;
		}
		
		$estado=$e->Estado;
		switch($estado){
			case "Activo":
				$optionEstado="<option selected>Activo</option><option>Inactivo</option><option>Agotadas</option><option>Cancelado</option>";
				break;
			case "Inactivo":
				$optionEstado="<option>Activo</option><option selected>Inactivo</option><option>Agotadas</option><option>Cancelado</option>";
				break;
			case "Agotadas":
				$optionEstado="<option>Activo</option><option>Inactivo</option><option selected>Agotadas</option><option>Cancelado</option>";
				break;
			case "Cancelado":
				$optionEstado="<option>Activo</option><option>Inactivo</option><option>Agotadas</option><option selected>Cancelado</option>";
				break;
			default:
				$optionEstado="<option selected>Activo</option><option>Inactivo</option><option>Agotadas</option><option>Cancelado</option>";
				break;
		}
		
		$lugar=$e->Lugar;
		$direccion=$e->Direccion;
		$ciudad=$e->Ciudad;
		$pais=$e->Pais;
		$aforo=$e->AforoEvento;		

		$inicio=Tool::adaptaFechaBDaForm($e->FechaInicio);
		$fin=Tool::adaptaFechaBDaForm($e->FechaFin);
		
		$url="./controlador.eventos.php?action=editar";
		$boton="Guardar cambios";
		break;
		
	default:
		$titulo="Nuevo Evento";
		$url="./controlador.eventos.php?action=nuevo";
		$optionEstado="<option selected>Activo</option><option>Inactivo</option><option>Agotadas</option><option>Cancelado</option>";
		$optionGenero="<option selected>Festival</option><option>Club Nocturno</option><option>Otro</option>";
		$boton="Crear evento";
		break;
}

if(isset($_SESSION["username"])){
	echo "<html>
				<header>
				<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
				</header>";
	
	echo Tool::inicioDocWeb();
	
	echo "<div>
			<h1>" . $titulo . "</h1>
			<form action='" . $url . "' method='POST' >
			
			<label>Nombre el evento</label><input type='text' id='nombre_evento' name='nombre_evento' value='" . utf8_decode($nombre) . "' />
				<div style='clear:both;'></div>
			
			<label>Genero</label><select  id='genero_evento' name='genero_evento'>
					" . $optionGenero . " 
					</select>
				<div style='clear:both;'></div>
			
			<label>Estado</label><select name='estado_evento'>
					" . $optionEstado . " 
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
		</body></html>
		";


	

}
else{
	header("Location:login.php");
}



?>

