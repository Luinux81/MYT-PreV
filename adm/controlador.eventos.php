<?php
include_once __DIR__ . "/../config.php";

include_once SITE_ROOT . "/clase.evento.php";
include_once SITE_ROOT . "/clase.tool.php";


$accion=$_GET['action'];
$idEvento=$_GET['id'];


switch($accion){
	case "nuevo":
		
		$e=new Evento();
		
		$e->Nombre=$_POST['nombre_evento'];
		$e->TipoEvento=$_POST['genero_evento'];
		$e->Estado=$_POST['estado_evento'];
		$e->Lugar=$_POST['lugar_evento'];
		$e->Direccion=$_POST['direccion_evento'];
		$e->Ciudad=$_POST['ciudad_evento'];
		$e->Pais=$_POST['pais_evento'];
		$e->AforoEvento=$_POST['aforo_evento'];
		$e->FechaInicio=$_POST['inicio_evento'];
		$e->FechaFin=$_POST['fin_evento'];
		
		Evento::crearEvento($e);
		
		break;
		
	case "editar":
		
		$e=new Evento();
		
		$e->IdEvento=$_POST['id_evento'];
		$e->Nombre=$_POST['nombre_evento'];
		$e->TipoEvento=$_POST['genero_evento'];
		$e->Estado=$_POST['estado_evento'];
		$e->Lugar=$_POST['lugar_evento'];
		$e->Direccion=$_POST['direccion_evento'];
		$e->Ciudad=$_POST['ciudad_evento'];
		$e->Pais=$_POST['pais_evento'];
		$e->AforoEvento=$_POST['aforo_evento'];
		$e->FechaInicio=$_POST['inicio_evento'];
		$e->FechaFin=$_POST['fin_evento'];
		
		Evento::actualizarEvento($e->IdEvento, $e);
		
		break;
		
	case "borrar":
		Evento::borrarEvento($idEvento);
		break;
		
	case "duplicar":
		Evento::duplicarEvento($idEvento);
		break;
		
	default:
		break;
}

header("Location:'./vista.eventos.php'");

?>