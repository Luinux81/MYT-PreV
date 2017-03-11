<?php
include_once '../clase.evento.php';
include_once '../clase.tool.php';

$id=$_GET["id"];

Evento::duplicarEvento($id);

header("Location:'./eventos.php'");


?>