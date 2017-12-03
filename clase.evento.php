<?php

include_once SITE_ROOT . "/clase.tool.php";

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
	 * Guarda la informaci�n de un evento en la tabla de ArchivoEventos y elimina el registro de la tabla Eventos.
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
			    
			    $ventas=Evento::getVentas($idEvento);
			    foreach ($ventas as $v){
			        Venta::archivarVenta($v->IdVenta);
			    }
			    
			    $tipos=TipoEntrada::listarTiposEntrada("IdEvento='" . $idEvento . "'");
			    foreach ($tipos as $tipo) {
			        TipoEntrada::archivarTipoEntrada($tipo->IdTipoEntrada);
			    }
			    
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
		
		$aux=Tool::ejecutaConsulta($sql, $db);
		
		$i=0;
		$res=[];
		foreach ($aux as $a){
		    $res[$i]=Evento::array2Evento($a);
		    $i++;
		}
		
		Tool::desconectaBD($db);
		
		return $res;		
	}
	
	/**
	 * Devuelve un objeto de la clase Evento con la informaci�n de la base de datos del evento con Id pasado como par�metro.
	 * @param integer $idEvento Id del evento a obtener
	 * @return Evento Objeto con la informaci�n de la base de datos.
	 */
	public static function getEvento($idEvento){
		$db=Tool::conectaBD();
		
		$sql="SELECT * FROM Eventos WHERE IdEvento='" . $idEvento ."'";
		
		$aux=Tool::ejecutaConsulta($sql, $db);
		
		$res=$aux->fetch_assoc();
		
		$evt=new Evento();
		$evt->IdEvento=$res['IdEvento'];
		$evt->Nombre=$res['Nombre'];
		$evt->FechaInicio=$res['FechaInicio'];
		$evt->FechaFin=$res['FechaFin'];
		$evt->AforoEvento=$res['AforoEvento'];
		$evt->EntradasVendidas=$res['EntradasVendidas'];
		$evt->TipoEvento=$res['TipoEvento'];
		$evt->Estado=$res['Estado'];
		$evt->Lugar=$res['Lugar'];
		$evt->Direccion=$res['Direccion'];
		$evt->Ciudad=$res['Ciudad'];
		$evt->Pais=$res['Pais'];
		
		Tool::desconectaBD($db);
		
		return $evt;		
	}
	
	public static function getVentas($idEvento){
	   $db=Tool::conectaBD();
	   
	   $sql="SELECT IdVenta FROM Ventas WHERE IdEvento='" . Tool::limpiaCadena($idEvento) . "'";
	   
	   $aux=Tool::ejecutaConsulta($sql, $db);
	   
	   $i=0;
	   $res=[];
	   foreach($aux as $a){
	       $res[$i]=Venta::getVenta($a['IdVenta']);
	       $i++;
	   }
	   
	   Tool::desconectaBD($db);
	   
	   return $res;
	}
	
    private static function array2Evento($array){
        $evt=new Evento();
        $evt->IdEvento=$array['IdEvento'];
        $evt->Nombre=$array['Nombre'];
        $evt->FechaInicio=$array['FechaInicio'];
        $evt->FechaFin=$array['FechaFin'];
        $evt->AforoEvento=$array['AforoEvento'];
        $evt->EntradasVendidas=$array['EntradasVendidas'];
        $evt->TipoEvento=$array['TipoEvento'];
        $evt->Estado=$array['Estado'];
        $evt->Lugar=$array['Lugar'];
        $evt->Direccion=$array['Direccion'];
        $evt->Ciudad=$array['Ciudad'];
        $evt->Pais=$array['Pais'];
        
        return $evt;
    }
}

?>