<?php
include_once '../clase.evento.php';
include_once '../clase.tool.php';

$id=$_GET["id"];

Evento::borrarEvento($id);

header("Location:'./eventos.php'");


?>