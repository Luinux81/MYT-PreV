<?php

include_once __DIR__ . "/../config.php";
include_once SITE_ROOT . '/clase.venta.php';

$ventas=Venta::listarVentas();

$res[0]=["fecha","importe"];
$i=1;

foreach ($ventas as $v){
	$res[$i]=array($v['Fecha'],$v['Importe']);
	$i++;
}
echo json_encode($res);
?>