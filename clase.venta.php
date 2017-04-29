<?php

include_once SITE_ROOT . "/clase.tool.php";


class Venta{
	public $IdVenta;
	public $IdEvento;
	public $IdComprador;
	public $fecha;
	public $importe;
	
	/**
	 * Inserta un nuevo registro de venta en la base de datos con la información del objeto Venta pasado como parámetro.
	 * @param Venta $v Objeto de clase Venta con la información de la nueva venta.
	 */
	public static function crearVenta($v){
		$db=Tool::conectaBD();
		
		$sql="INSERT INTO Ventas 
		(IdVenta,IdEvento,IdComprador,Fecha,Importe) 
		VALUES ('" . $v->IdVenta . "',
				'" . $v->IdEvento . "',
				'" . $v->IdComprador . "',
				'" . $v->fecha . "',
				'" . $v->importe . "')";
		
		$res=Tool::ejecutaConsulta($sql, $db);		
		
		Tool::desconectaBD($db);
	}

	/**
	 * Actualiza el registro de la venta con un Id concreto en la base de datos con la información contenida en el objeto de clase Venta pasado como parámetro.
	 * @param integer $idVenta Id de la venta a actualizar.
	 * @param Venta $v Objeto que contiene la información con la que se actualizará el registro en la base de datos.
	 */
	public static function actualizarVenta($idVenta,$v){
		$db=Tool::conectaBD();
		
		$idVenta=Tool::limpiaCadena($idVenta);
		$aux=Venta::getVenta($idVenta);
		
		if($aux->IdVenta<>""){
			$sql="UPDATE Ventas SET 
				IdVenta='" . $v->IdVenta . "',
				IdEvento='" . $v->IdEvento . "',
				IdComprador='" . $v->IdComprador . "',
				Fecha='" . $v->fecha . "',
				Importe='" . $v->importe . "' 
				WHERE IdVenta='" . $idVenta . "'";
			
			Tool::ejecutaConsulta($sql, $db);
		}
		
		Tool::desconectaBD($db);
	}
	
	/**
	 * Elimina un registro de venta de la base de datos
	 * @param integer $idVenta Id de la venta a eliminar
	 */
	public static function borrarVenta($idVenta){
		$db=Tool::conectaBD();
		
		$sql="DELETE FROM Ventas WHERE IdVenta='" . Tool::limpiaCadena($idVenta) . "'";
		$res=Tool::ejecutaConsulta($sql, $db);
		
		Tool::desconectaBD($db);
	}
	
	/**
	 * Guarda la información de una venta en la tabla de ArchivoVentas y elimina el registro de la tabla Ventas.
	 * @param string $idVenta Id de la venta a archivar.
	 */
	public static function archivarVenta($idVenta){
		$db=Tool::conectaBD();
		
		$idVenta=Tool::limpiaCadena($idVenta);
		
		$v=new Venta();
		$v=Venta::getVenta($idVenta);
		
		if($v->IdEvento<>""){
			$sql="INSERT INTO ArchivoVentas 
			(IdVenta,IdEvento,IdComprador,Fecha,Importe) VALUES 
			('" . $v->IdVenta . "',
			'" . $v->IdEvento . "',
			'" . $v->IdComprador . "',
			'" . $v->fecha . "',
			'" . $v->importe . "')";
			
			if(Tool::ejecutaConsulta($sql, $db)){
				Venta::borrarVenta($idVenta);
			}
		}
		
		Tool::desconectaBD($db);
	}
	
	/**
	 * Devuelve un array con todos las ventas que cumplen el filtro.
	 * @param string $filtro Clausula WHERE de la consulta SQL que obtiene las ventas. Su valor por defecto es 1.
	 * @return array Array con las ventas que cumplen el filtro obtenidos de la base de datos.
	 */
	public static function listarVentas($filtro="1"){
		$db=Tool::conectaBD();
		
		$sql="SELECT * FROM Ventas WHERE " . Tool::limpiaCadena($filtro);
		
		$res=Tool::ejecutaConsulta($sql, $db);
		
		Tool::desconectaBD($db);
		
		return $res;
	}

	/**
	 * Devuelve un objeto de la clase Venta con la información de la base de datos de la venta con Id pasado como parámetro.
	 * @param string $idVenta Id de la venta a obtener
	 * @return Venta Objeto con la información de la base de datos.
	 */
	public static function getVenta($idVenta){
		$db=Tool::conectaBD();
		
		$sql="SELECT * FROM Ventas WHERE IdVenta='" . Tool::limpiaCadena($idVenta) . "'";
		
		$aux=Tool::ejecutaConsulta($sql, $db);
		
		$res=$aux->fetch_assoc();
		
		$v=new Venta();
		$v->IdVenta=$res['IdVenta'];
		$v->IdEvento=$res['IdEvento'];
		$v->IdComprador=$res['IdComprador'];
		$v->fecha=$res['Fecha'];
		$v->importe=$res['Importe'];
		
		
		Tool::desconectaBD($db);
		
		return $v;		
	}
	
}