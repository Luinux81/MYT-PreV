<?php
include_once "./clase.tool.php";


class Entrada{
	public $idEntrada;
	public $idVenta;
	public $idTipoEntrada;
	
	/**
	 * Inserta un nuevo registro de entrada en la base de datos con la información del objeto Entrada pasado como parámetro.
	 * @param Entrada $e Objeto con la información de la nueva entrada.
	 */
	public static function crearEntrada($e){
		$db=Tool::conectaBD();
		
		$sql="INSERT INTO Entradas (IdEntrada,IdTipoEntrada,IdVenta) 
				VALUES ('" . $e->idEntrada . "',
						'" . $e->idTipoEntrada . "', 
						'" . $e->idVenta . "')";
		
		$res=Tool::ejecutaConsulta($sql, $db);
		
		Tool::desconectaBD($db);
	}

	/**
	 * Actualiza el registro de entrada con un Id concreto en la base de datos con la información contenida en el objeto de clase Entrada pasado como parámetro.
	 * @param integer $idEntrada Id de la entrada a actualizar.
	 * @param Entrada $e Objeto que contiene la información con la que se actualizará el registro en la base de datos.
	 */
	public static function actualizarEntrada($idEntrada, $e){
		$db=Tool::conectaBD();
		
		$aux=Entrada::getEntrada($idEntrada);
		
		if($aux->idEntrada<>""){
			$sql="UPDATE Entradas SET 
					IdEntrada='" . $e->idEntrada . "',
					IdTipoEntrada='" . $e->idTipoEntrada . "',
					IdVenta='" . $e->idVenta ."' 
				    WHERE IdEntrada='" . $idEntrada . "'";
			
			Tool::ejecutaConsulta($sql, $db);
		}		
		
		Tool::desconectaBD($db);
	}

	/**
	 * Elimina un registro de entrada de la base de datos
	 * @param integer $idEntrada Id de la entrada a eliminar
	 */
	public static function borrarEntrada($idEntrada){
		$db=Tool::conectaBD();
		
		$sql="DELETE FROM Entradas WHERE IdEntrada='" . Tool::limpiaCadena($idEntrada) . "'";
		
		$res=Tool::ejecutaConsulta($sql, $db);
		
		Tool::desconectaBD($db);
	}

	/**
	 * Devuelve un array con todos las entradas que cumplen el filtro.
	 * @param string $filtro Clausula WHERE de la consulta SQL que obtiene las entrada. Su valor por defecto es 1.
	 * @return array Array con las entrada que cumplen el filtro obtenidas de la base de datos.
	 */
	public static function listarEntradas($filtro="1"){
		$db=Tool::conectaBD();
		
		$sql="SELECT * FROM Entradas WHERE " . Tool::limpiaCadena($filtro);
		
		$res=Tool::ejecutaConsulta($sql, $db);
		
		Tool::desconectaBD($db);
		
		return $res;
	}

	/**
	 * Devuelve un objeto de la clase Entrada con la información de la base de datos de la entrada con Id pasado como parámetro.
	 * @param integer $idEntrada Id de la entrada a obtener
	 * @return Entrada Objeto con la información de la base de datos.
	 */
	public static function getEntrada($idEntrada){
		$db=Tool::conectaBD();
		
		$sql="SELECT * FROM Entradas WHERE IdEntrada='" . Tool::limpiaCadena($idEntrada) . "'";
		
		$aux=Tool::ejecutaConsulta($sql, $db);
		$res=$aux->fetch_assoc();
		
		$e=new Entrada();
		
		$e->idEntrada=$res['IdEntrada'];
		$e->idVenta=$res['IdVenta'];
		$e->idTipoEntrada=$res=['IdTipoEntrada'];
		
		Tool::desconectaBD($db);
		
		return $e;		
	}
}

?>