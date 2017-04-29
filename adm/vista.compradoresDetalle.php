<?php

header('Content-Type: text/html; charset=UTF-8');

include_once __DIR__ . "/../config.php";
include_once SITE_ROOT . "/clase.comprador.php";
include_once SITE_ROOT . "/clase.tool.php";

$err=$_GET['err'];
$res=$_GET['res'];

$accion=$_GET['action'];
$id=$_GET['id'];

session_start();

switch ($accion){
	case "nuevo":
		$titulo="Nuevo Comprador";
		$url="./controlador.compradores.php?action=nuevo";
		$optionGenero="<option selected>Sr.</option><option>Sra.</option>";
		$boton="Crear comprador";
		break;

	case "editar":
		$titulo="Editar Comprador";
		$c=Comprador::getComprador("IdComprador", $id);
		
		$nombre=$c->Nombre;		
		$apellidos=$c->Apellidos;
		$email=$c->Email;
		$genero=$c->Genero;

		switch($genero){
			case "H":
				$optionGenero="<option selected>Sr.</option><option>Sra.</option>";
				break;
			case "M":
				$optionGenero="<option>Sr.</option><option selected>Sra.</option>";
				break;
			default:
				$optionGenero="<option selected>Sr.</option><option>Sra.</option>";
				break;
		}

		$ciudad=$c->Ciudad;
		$pais=$c->Pais;
		$fecha=$c->FechadeNacimiento;
		$edad=$c->Edad;

		$url="./controlador.compradores.php?action=editar&id=" . $id;
		$boton="Guardar cambios";
		break;

	default:
		$titulo="Nuevo Comprador";
		$url="./controlador.compradores.php?action=nuevo";
		$optionGenero="<option selected>Sr.</option><option>Sra.</option>";
		$boton="Crear comprador";
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
		
			<label>Genero</label><select  id='genero_evento' name='genero_evento'>
					" . $optionGenero . "
					</select>
				<div style='clear:both;'></div>

			<label>Nombre</label><input type='text' name='nombre_comprador' value='" . utf8_decode($nombre) . "' />
				<div style='clear:both;'></div>
					
			<label>Apellidos</label><input type='text' name='apellidos_comprador' value='" . utf8_decode($apellidos) . "' />
				<div style='clear:both;'></div>

			<label>Email</label><input type='text' name='email_comprador' value='" . utf8_decode($email) . "' />
				<div style='clear:both;'></div>
		
			<label>Ciudad</label><input type='text' name='ciudad_comprador'  value='" . utf8_decode($ciudad) . "' />
				<div style='clear:both;'></div>
		
			<label>Pais</label><input type='text' name='pais_comprador'  value='" . utf8_decode($pais) . "' />
				<div style='clear:both;'></div>
		
			<label>Fecha de nacimiento</label><input type='date' name='fecha_comprador' value='" . $fecha . "'  />
				<div style='clear:both;'></div>
		
			<input type='hidden' name='id_comprador' value='" . $e->IdComprador . "' />
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


