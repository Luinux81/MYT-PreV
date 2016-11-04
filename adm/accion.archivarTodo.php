<?php

include_once "../fpdf.php";
include_once "../clase.ticket.php";
include_once "../clase.compra.php";
include_once "../clase.comprador.php";
include_once "../clase.tool.php";

session_start();

if(isset($_SESSION["username"])){
	$c=new Comprador();
	$i=0;
	
	$lista=$c->listadoCompradores();
	
	foreach ($lista as $aux){
		if(Comprador::archivaComprador($aux['Email'])){
			$i++;
		}		
	}
	
	echo "Archivados " . $i . " compradores<br/>";
	
	$c=new Compra();
	$i=0;
	
	$lista=$c->listadoCompras();
	
	foreach($lista as $aux){
		if(Compra::archivaCompra($aux['Id'])){
			$i++;
		}
	}
	
	echo "Archivados " . $i . " compras<br/>";
	
	$c=new Ticket();
	$i=0;
	
	$lista=$c->listadoTickets();
	
	foreach($lista as $aux){
		if(Ticket::archivaTicket($aux['Codigo'])){
			$i++;
		}
	}
	
	echo "Archivados " . $i . " tickets<br/>";
}
else{
	header("Location:login.php");
}
?>