<?php

include_once '../clase.evento.php';
include_once '../clase.tool.php';

$e=new Evento();

$e->Nombre=$_POST['nombre_evento'];
$e->TipoEvento=$_POST['genero_evento'];
$e->Lugar=$_POST['lugar_evento'];
$e->Direccion=$_POST['direccion_evento'];
$e->Ciudad=$_POST['ciudad_evento'];
$e->Pais=$_POST['pais_evento'];
$e->AforoEvento=$_POST['aforo_evento'];
$e->FechaInicio=$_POST['inicio_evento'];
$e->FechaFin=$_POST['fin_evento'];

Evento::crearEvento($e);

header("Location:'./eventos.php'");
?>
