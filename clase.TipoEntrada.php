<?php

include_once "./clase.tool.php";


class TipoEntrada{

	public $idTipoEntrada;
	public $idEvento;
	public $nombre;
	public $precio;
	public $cantidad;
	public $disponible;
	public $fechaInicioVenta;
	public $fechaFinVenta;
	
	
	/**
	 * Inserta un nuevo registro de tipo de entrada en la base de datos con la información del objeto TipoEntrada pasado como parámetro.
	 * @param TipoEntrada $e Objeto con la información del nuevo tipo de entrada.
	 */
	public static function crearTipoEntrada($tp){
		$db=Tool::conectaBD();
		
		$sql="INSERT INTO TiposEntrada 
		(IdEvento,Nombre,Precio,Disponible,Cantidad,FechaInicioVenta,FechaFinVenta)
		VALUES (" . $tp->idEvento . ",'
				" . $tp->nombre . "',
				" . $tp->precio . ",
				" . $tp->disponible . ",
				" . $tp->cantidad . ",
				" . $tp->fechaInicioVenta . "
				" . $tp->fechaFinVenta .")";
		
		$res=Tool::ejecutaConsulta($sql, $db);
		
		Tool::desconectaBD($db);
	}

	/**
	 * Crea un tipo de entrada nuevo en la base de datos con la información contenida en otro registro.
	 * @param integer $idTipo Id del registro origen de la información.
	 */
	public static function duplicarTipoEntrada($idTipo){
		$tp=TipoEntrada::getTipoEntrada($idTipo);
		if($tp->idTipoEntrada<>""){
			TipoEntrada::crearTipoEntrada($tp);
		}
	}
	
	/**
	 * Actualiza el registro del tipo de entrada con un Id concreto en la base de datos con la información contenida en el objeto de clase TipoEntrada pasado como parámetro.
	 * @param integer $idTipo Id del tipo de entrada a actualizar.
	 * @param TipoEntrada $e Objeto que contiene la información con la que se actualizará el registro en la base de datos.
	 */
	public static function actualizarTipoEntrada($idTipo,$tp){
		$db=Tool::conectaBD();
		
		$aux=TipoEntrada::getTipoEntrada($idTipo);
		
		if($aux->idTipoEntrada<>""){
			$sql="UPDATE TiposEntrada SET
					IdEvento='" . $tp->idEvento . "',
					Nombre='" . $tp->nombre . "',
					Precio='" . $tp->precio . "',
					Cantidad='" . $tp->cantidad . "',
					Disponible='" . $tp->disponible . "',
					FechaInicioVenta='" . $tp->fechaInicioVenta . "',
					FechaFinVenta='" . $tp->fechaFinVenta . "' 

					WHERE IdTipoEntrada='" . Tool::limpiaCadena($idTipo) . "'";
				
			Tool::ejecutaConsulta($sql, $db);
		}
		
		Tool::desconectaBD($db);
	}
	
	/**
	 * Elimina un registro de tipo de entrada de la base de datos
	 * @param integer $idTipo Id del tipo de entrada a eliminar
	 */
	public static function borrarTipoEntrada($idTipo){
		$db=Tool::conectaBD();
		
		$sql="DELETE FROM TiposEntrada WHERE IdTipoEntrada='" . Tool::limpiaCadena($idTipo) ."'";
		
		Tool::ejecutaConsulta($sql, $db);
		
		Tool::desconectaBD($db);
	}

	/**
	 * Guarda la información de un tipo de entrada en la tabla de ArchivoTiposEntrada y elimina el registro de la tabla TiposEntrada.
	 * @param integer $idEvento Id del tipo de entrada a archivar.
	 */
	public static function archivarTipoEntrada($idTipo){
		$db=Tool::conectaBD();
		
		$tp=new TipoEntrada();
		$tp=TipoEntrada::getTipoEntrada($idTipo);
		
		$sql="INSERT INTO ArchivoTiposEntrada
		(IdTipoEntrada,IdEvento,Nombre,Precio,Disponible,Cantidad,FechaInicioVenta,FechaFinVenta)
		VALUES (" . $tp->idTipoEntrada . ",
				" . $tp->idEvento . ",'
				" . $tp->nombre . "',
				" . $tp->precio . ",
				" . $tp->disponible . ",
				" . $tp->cantidad . ",
				" . $tp->fechaInicioVenta . "
				" . $tp->fechaFinVenta .")";
		
		if(Tool::ejecutaConsulta($sql, $db)){
			TipoEntrada::borrarTipoEntrada($idTipo);
		}
		
		Tool::desconectaBD($db);		
	}

	/**
	 * Devuelve un array con todos los tipos de entrada que cumplen el filtro.
	 * @param string $filtro Clausula WHERE de la consulta SQL que obtiene los tipos de entrada. Su valor por defecto es 1.
	 * @return array Array con los tipo de entrada que cumplen el filtro obtenidos de la base de datos.
	 */
	public static function listarTiposEntrada($filtro="1"){
		$db=Tool::conectaBD();
		
		$sql="SELECT * FROM TiposEntrada WHERE " . Tool::limpiaCadena($filtro);
		
		$res=Tool::ejecutaConsulta($sql, $db);
		
		//print_r($sql);
		
		Tool::desconectaBD($db);
		
		return $res;
	}
	
	/**
	 * Devuelve un objeto de la clase TipoEntrada con la información de la base de datos del tipo de entrada con Id pasado como parámetro.
	 * @param integer $idTipo Id del tipo de entrada a obtener
	 * @return TipoEntrada Objeto con la información de la base de datos.
	 */
	public static function getTipoEntrada($idTipo){
		$db=Tool::conectaBD();
		
		$sql="SELECT * FROM TiposEntrada WHERE IdTipoEntrada='" . $idTipo . "'";
		
		$aux=Tool::ejecutaConsulta($sql, $db);
		$res=$aux->fetch_assoc();
		
		$tp=new TipoEntrada();
		$tp->idTipoEntrada=$res['IdTipoEntrada'];
		$tp->idEvento=$res['IdEvento'];
		$tp->nombre=$res['Nombre'];
		$tp->precio=$res['Precio'];
		$tp->cantidad=$res['Cantidad'];
		$tp->disponible=$res['Disponible'];
		$tp->fechaInicioVenta=$res['FechaInicioVenta'];
		$tp->fechaFinVenta=$res['FechaFinVenta'];
		
		Tool::desconectaBD($db);
		
		return $tp;
	}
	
}

?>