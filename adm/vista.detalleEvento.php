<?php

include_once "../clase.evento.php";
include_once "../clase.tool.php";

$err=$_GET['err'];
$res=$_GET['res'];

$accion=$_GET['accion'];
$id=$_GET['id'];

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

	echo "<div style='position: absolute;top: 70px;left: 250px;'>
			<h1>Nuevo Evento</h1>
			<form action='./accion.crearEvento.php' method='POST' >
			
			<label>Nombre el evento</label><input type='text' id='nombre_evento' name='nombre_evento' />
				<div style='clear:both;'></div>
			<label>Genero</label><select  id='genero_evento' name='genero_evento'>
					<option selected>Festival</option>
					<option>Club Nocturno</option>
					<option>Otro</option>
					</select>
				<div style='clear:both;'></div>
			<label>Lugar</label><input type='text' name='lugar_evento'/>
				<div style='clear:both;'></div>
			<label>Direccion</label><input type='text' name='direccion_evento' />
				<div style='clear:both;'></div>
			<label>Ciudad</label><input type='text' name='ciudad_evento'/>
				<div style='clear:both;'></div>
			<label>Pais</label><input type='text' name='pais_evento'/>
				<div style='clear:both;'></div>
			<label>Entradas a la venta</label><input type='number' name='aforo_evento' />
				<div style='clear:both;'></div>
			<label>Inicio</label><input type='datetime-local' name='inicio_evento' />
				<div style='clear:both;'></div>
			<label>Fin</label><input type='datetime-local' name='fin_evento' />
				<div style='clear:both;'></div>
			<input type='submit' value='Crear Evento' />
			</form>
		  </div>
		</div>
		</div>
		";



}
else{
	header("Location:login.php");
}



?>

