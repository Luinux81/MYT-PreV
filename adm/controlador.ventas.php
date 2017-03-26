<?php
include_once '../clase.venta.php';
include_once '../clase.tool.php';


$accion=$_GET['action'];
$idVenta=$_GET['id'];


switch($accion){
	case "nuevo":

		$v=new Venta();
		
		$v->IdVenta=$_POST['idventa_venta'];
		$v->IdEvento=$_POST['idevento_venta'];
		$v->IdComprador=$_POST['idcomprador_venta'];
		$v->fecha=$_POST['fecha_venta'];
		$v->importe=$_POST['importe_venta'];		

		Venta::crearVenta($v);

		break;

	case "editar":

		$v=new Venta();
		
		$v->IdVenta=$_POST['id_venta'];
		$v->IdEvento=$_POST['idevento_venta'];
		$v->IdComprador=$_POST['idcomprador_venta'];
		$v->fecha=$_POST['fecha_venta'];
		$v->importe=$_POST['importe_venta'];
		
		Venta::actualizarVenta($v->IdVenta, $v);
		
		break;

	case "borrar":
		Venta::borrarVenta($idVenta);
		break;

	case "archivar":
		Venta::archivarVenta($idVenta);
		break;

	default:
		break;
}

header("Location:'./vista.ventas.php'");

?>
