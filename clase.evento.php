<?php

include_once "./clase.tool.php";

class Evento{
	
	//Atributos de la clase
	
	public $IdEvento;
	public $Nombre;
	public $FechaInicio;
	public $FechaFin;
	public $AforoEvento;
	public $EntradasVendidas;
	public $Estado;
	public $TipoEvento;
	public $Lugar;
	public $Direccion;
	public $Ciudad;
	public $Pais;
	
	
	// Casos de uso
	
	
	/**
	 * Inserta un nuevo registro de evento en la base de datos con la informaci�n del objeto Evento pasado como par�metro.
	 * @param Evento $e Objeto de clase Evento con la informaci�n del nuevo evento. 
	 */
	public static function crearEvento($e){
		$db=Tool::conectaBD();
		
		$sql="INSERT INTO Eventos 
		(Nombre,FechaInicio,FechaFin,AforoEvento,EntradasVendidas,Estado,TipoEvento,Lugar,Direccion,Ciudad,Pais) 
		VALUES ('" . $e->Nombre . "',
				'" . $e->FechaInicio . "',
				'" . $e->FechaFin . "',
				" . $e->AforoEvento . ",
				0,
				'" . $e->Estado . "',
				'" . $e->TipoEvento . "',
				'" . $e->Lugar . "',
				'" . $e->Direccion . "',
				'" . $e->Ciudad . "',
				'" . $e->Pais . "')";
		
		$res=Tool::ejecutaConsulta($sql, $db);
		
		Tool::desconectaBD($db);
	}
	
	/**
	 * Crea un evento nuevo en la base de datos con la informaci�n contenida en otro registro.
	 * @param integer $idEvento Id del registro origen de la informaci�n.
	 */
	public static function duplicarEvento($idEvento){
		$evt=Evento::getEvento($idEvento);
		if ($evt->IdEvento<>""){
			Evento::crearEvento($evt);
		}		
	}
	
	/**
	 * Actualiza el registro del evento con un Id concreto en la base de datos con la informaci�n contenida en el objeto de clase Evento pasado como par�metro.
	 * @param integer $idEvento Id del evento a actualizar.
	 * @param Evento $e Objeto que contiene la informaci�n con la que se actualizar� el registro en la base de datos.
	 */
	public static function actualizarEvento($idEvento,$e){
		$db=Tool::conectaBD();
		
		$aux=Evento::getEvento($idEvento);
		
		if($aux->IdEvento<>""){
			$sql="UPDATE Eventos SET 
					Nombre='" . $e->Nombre . "',
					FechaInicio='" . $e->FechaInicio . "',
					FechaFin='" . $e->FechaFin . "',
					AforoEvento='" . $e->AforoEvento . "',
					EntradasVendidas='" . $e->EntradasVendidas . "',
					Estado='" . $e->Estado . "',
					TipoEvento='" . $e->TipoEvento . "',
					Lugar='" . $e->Lugar . "',
					Direccion='" . $e->Direccion . "',
					Ciudad='" . $e->Ciudad . "',
					Pais='" . $e->Pais . "'
					WHERE IdEvento='" . Tool::limpiaCadena($idEvento) . "'";	
			
			Tool::ejecutaConsulta($sql, $db);
		}
		
		Tool::desconectaBD($db);
	}
	
	/**
	 * Elimina un registro de evento de la base de datos
	 * @param integer $idEvento Id del evento a eliminar
	 */
	public static function borrarEvento($idEvento){
		$db=Tool::conectaBD();		
		
		$sql="DELETE FROM Eventos WHERE IdEvento='" . Tool::limpiaCadena($idEvento) ."'";
		
		Tool::ejecutaConsulta($sql, $db);
		
		Tool::desconectaBD($db);		
	}
	
	/**
	 * Guarda la informaci�n de un evento en la tabla de Historico.
	 * @param integer $idEvento Id del evento a archivar.
	 */
	public static function archivarEvento($idEvento){
		$db=Tool::conectaBD();
		
		$e=Evento::getEvento($idEvento);
		
		if($e->IdEvento<>""){
			$sql="INSERT INTO ArchivoEventos
			(IdEvento,Nombre,FechaInicio,FechaFin,AforoEvento,EntradasVendidas,Estado,TipoEvento,Lugar,Direccion,Ciudad,Pais)
			VALUES ('" . $e->IdEvento . "',
					'" . $e->Nombre . "',
					'" . $e->FechaInicio . "',
					'" . $e->FechaFin . "',
					" . $e->AforoEvento . ",
					0,'" . $e->Estado . "',
					'" . $e->TipoEvento . "',
					'" . $e->Lugar . "',
					'" . $e->Direccion . "',
					'" . $e->Ciudad . "',
					'" . $e->Pais . "')";
			
			if(Tool::ejecutaConsulta($sql, $db)){
				Evento::borrarEvento($idEvento);
			}
		}
		
		
		Tool::desconectaBD($db);
	}
	
	/**
	 * Devuelve un array con todos los eventos que cumplen el filtro.
	 * @param string $filtro Clausula WHERE de la consulta SQL que obtiene los eventos. Su valor por defecto es 1.
	 * @return array Array con los eventos que cumplen el filtro obtenidos de la base de datos.
	 */
	public static function listarEventos($filtro="1"){
		$db=Tool::conectaBD();
		
		$sql="SELECT * FROM Eventos WHERE " . Tool::limpiaCadena($filtro);
		
		$res=Tool::ejecutaConsulta($sql, $db);
		
		Tool::desconectaBD($db);
		
		return $res;		
	}
	
	/**
	 * Devuelve un objeto de la clase Evento con la informaci�n de la base de datos del evento con Id pasado como par�metro.
	 * @param unknown $idEvento Id del evento a obtener
	 * @return Evento Objeto con la informaci�n de la base de datos.
	 */
	public static function getEvento($idEvento){
		$db=Tool::conectaBD();
		
		$sql="SELECT * FROM Eventos WHERE IdEvento='" . $idEvento ."'";
		
		$res=Tool::ejecutaConsulta($sql, $db);
		
		$evt=new Evento();
		$evt->IdEvento=$res['IdEvento'];
		$evt->Nombre=$res['Nombre'];
		$evt->FechaInicio=$res['FechaInicio'];
		$evt->FechaFin=$res['FechaFin'];
		$evt->AforoEvento=$res['AforoEvento'];
		$evt->EntradasVendidas=$res['EntradasVendidas'];
		$evt->TipoEvento=$res['TipoEvento'];
		$evt->Estado=$res['Estado'];
		$evt->Lugar=$es['Lugar'];
		$evt->Direccion=$res['Direccion'];
		$evt->Ciudad=$res['Ciudad'];
		$evt->Pais=$res['Pais'];
		
		Tool::desconectaBD($db);
		
		return $evt;		
	}
	

}

?>