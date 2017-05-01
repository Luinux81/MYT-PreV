<?php

include_once __DIR__ . "/../config.php";
include_once SITE_ROOT . '/clase.venta.php';

$ventas=Venta::listarVentas();

$res[0]=["fecha","importe","cantidad"];
$i=1;

foreach ($ventas as $v){
	$entradas=Venta::getEntradas($v['IdVenta']);	
	
	
	$aux=$entradas->fetch_all();
	
	
	$res[$i]=array($v['Fecha'],$v['Importe'],count($aux));
	$i++;
}
echo json_encode($res);
?>