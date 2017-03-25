<?php
include_once '../clase.evento.php';
include_once '../clase.comprador.php';
include_once '../clase.tool.php';


$accion=$_GET['action'];
$id=$_GET['id'];


$nombre=$_POST['nombre_comprador'];
$apellidos=$_POST['apellidos_comprador'];
$email=$_POST['email_comprador'];

switch($_POST['genero_comprador']){
	case "Sr.":
		$genero="H";
		break;
	case "Sra.":
		$genero="M";
		break;
	default:
		$genero="H";
		break;
}

$ciudad=$_POST['ciudad_comprador'];
$pais=$_POST['pais_comprador'];
$fechaN=$_POST['fecha_comprador'];


switch($accion){
	case "nuevo":
		$c=new Comprador();

		$c->Nombre=$nombre;
		$c->Apellidos=$apellidos;
		$c->Email=$email;
		$c->Genero=$genero;		
		$c->Ciudad=$ciudad;
		$c->Pais=$pais;
		$c->FechadeNacimiento=$fechaN;
		$c->Edad=calculaEdad($c->FechadeNacimiento);

		Comprador::crearComprador($c);

		break;

	case "editar":
		$c=new Comprador();
		
		$c->IdComprador=$id;
		$c->Nombre=$nombre;
		$c->Apellidos=$apellidos;
		$c->Email=$email;
		$c->Genero=$genero;
		$c->Ciudad=$ciudad;
		$c->Pais=$pais;
		$c->FechadeNacimiento=$fechaN;
		$c->Edad=calculaEdad($c->FechadeNacimiento);
		
		Comprador::actualizarComprador($id, $c);
		
		break;

	case "borrar":
		Comprador::borrarComprador($id);
		break;
/*
	case "duplicar":
		Evento::duplicarEvento($idEvento);
		break;
*/
	default:
		break;
}

header("Location:'./vista.compradores.php'");


function calculaEdad($fechaNacimiento){

	//print_r("Fecha1:");
	//print_r($fechaNacimiento);
	
	$aux=date_parse_from_format("Y-m-d", $fechaNacimiento);
	
	//print_r("Fecha2:");
	$aux1=date("Y-m-d",time());
	
	$aux1=date_parse_from_format("Y-m-d",$aux1);
	
	
	$edad=$aux1['year']-$aux['year'];
	
	if($aux['month']==$aux1['month']){
		if($aux['day']>$aux1['day']){
			$edad--;
		}
	}
	elseif($aux['month']>$aux1['month']){
		$edad--;
	}
	
	return $edad;
	
}
?>
