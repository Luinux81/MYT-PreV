<?php
include_once "./clase.tool.php";

if (!defined('LOG')) define('LOG', './ipn.log');

class Comprador{

	public $IdComprador;
	public $Nombre;
	public $Apellidos;
	public $Email;
	public $Genero;
	public $Ciudad;
	public $Pais;
	public $FechadeNacimiento;
	public $Edad;
	
	/**
	 * Inserta un nuevo registro de comprador en la base de datos con la información del objeto Comprador pasado como parámetro.
	 * @param Comprador $c Objeto de clase Comprador con la información del nuevo comprador.
	 */
	public static function crearComprador($c){
		$db=Tool::conectaBD();
		
		$sql="INSERT INTO Compradores
		(Nombre,Apellidos,Email,Genero,Ciudad,Pais,FechadeNacimiento,Edad)
		VALUES ('" . $c->Nombre . "',
				'" . $c->Apellidos . "',
				'" . $c->Email . "',
				'" . $c->Genero . "',
				'" . $c->Ciudad . "',
				'" . $c->Pais . "',
				'" . $c->FechadeNacimiento . "',
				" . $c->Edad . ")";
		
		$res=Tool::ejecutaConsulta($sql, $db);
		
		Tool::desconectaBD($db);
	}
	
	/**
	 * Actualiza el registro del comprador con un Id concreto en la base de datos con la información contenida en el objeto de clase Comprador pasado como parámetro.
	 * @param integer $id Id del comprador a actualizar.
	 * @param Comprador $c Objeto que contiene la información con la que se actualizará el registro en la base de datos.
	 */
	public static function actualizarComprador($id,$c){
		$db=Tool::conectaBD();
		
		$aux=Comprador::getComprador("IdComprador", $id);
		
		if($aux->IdComprador<>""){
			$sql="UPDATE Compradores SET
					Nombre='" . $c->Nombre . "',
					Apellidos='" . $c->Apellidos . "',
					Email='" . $c->Email . "',
					Genero='" . $c->Genero . "',
					Ciudad='" . $c->Ciudad . "',
					Pais='" . $c->Pais . "',
					FechadeNacimiento='" . $c->FechadeNacimiento . "',
					Edad='" . $c->Edad . "' 
					WHERE IdComprador='" . Tool::limpiaCadena($id) . "'";
				
			
			Tool::ejecutaConsulta($sql, $db);
		}
		
		//print_r($sql);
		
		Tool::desconectaBD($db);
	}
	
	/**
	 * Elimina un registro de comprador de la base de datos.
	 * @param integer $id Id del comprador a eliminar.
	 */
	public static function borrarComprador($id){
		$db=Tool::conectaBD();
		
		$sql="DELETE FROM Compradores WHERE IdComprador='" . Tool::limpiaCadena($id) ."'";
		
		Tool::ejecutaConsulta($sql, $db);
		
		Tool::desconectaBD($db);
	}
	
	/**
	 * Guarda la información de un evento en la tabla de ArchivoCompradores y elimina el registro de la tabla Compradores.
	 * @param integer $id Id del comprador a archivar.
	 */
	public static function archivarComprador($id){
		$db=Tool::conectaBD();
		
		$c=Comprador::getComprador("IdComprador",$id);
		
		if($c->IdComprador<>""){
			$sql="INSERT INTO ArchivoCompradores
			(IdComprador,Nombre,Apellidos,Email,Genero,Ciudad,Pais,FechadeNacimiento,Edad)
			VALUES ('" . $c->IdComprador . "',
				'" . $c->Nombre . "',
				'" . $c->Apellidos . "',
				'" . $c->Email . "',
				'" . $c->Genero . "',
				'" . $c->Ciudad . "',
				'" . $c->Pais . "',
				'" . $c->FechadeNacimiento . "',
				" . $c->Edad . ")";
				
			if(Tool::ejecutaConsulta($sql, $db)){
				Comprador::borrarComprador($id);
			}
		}
		
		
		Tool::desconectaBD($db);
	}
	
	/**
	 * Devuelve un objeto de la clase Comprador con la información del registro en la base de datos que coincida con el par clave-valor pasado como parámetros.
	 * ATENCION: Devuelve solo el primer registro si la consulta devuelve varios registros.
	 * @param string $clave Columna de la tabla Compradores 
	 * @param string $valor Valor del registro Compradores 
	 * @return Comprador Objeto con la información del registro de la base de datos 
	 */
	public static function getComprador($clave,$valor){
		$db=Tool::conectaBD();
		
		$sql="SELECT * FROM Compradores WHERE " . Tool::limpiaCadena($clave) . "='" . Tool::limpiaCadena($valor) . "'";
		
		$aux=Tool::ejecutaConsulta($sql, $db);		
		$res=$aux->fetch_assoc();
		
		$c=new Comprador();
		$c->IdComprador=$res['IdComprador'];
		$c->Nombre=$res['Nombre'];
		$c->Apellidos=$res['Apellidos'];
		$c->Email=$res['Email'];
		$c->Ciudad=$res['Ciudad'];
		$c->Pais=$res['Pais'];
		$c->Genero=$res['Genero'];
		$c->FechadeNacimiento=$res['FechadeNacimiento'];
		$c->Edad=$res['Edad'];
		
		
		Tool::desconectaBD($db);
		
		return $c;
	}
	
	/**
	 * Devuelve un array con todos los compradores que cumplen el filtro.
	 * @param string $filtro Clausula WHERE de la consulta SQL que obtiene los compradores. Su valor por defecto es 1.
	 * @return array Array con los compradores que cumplen el filtro obtenidos de la base de datos.
	 */
	public static function listarCompradores($filtro="1"){
		$db=Tool::conectaBD();
		
		$sql="SELECT * FROM Compradores WHERE " . Tool::limpiaCadena($filtro);
		
		$res=Tool::ejecutaConsulta($sql, $db);
		
		//print_r($sql);
		
		Tool::desconectaBD($db);
		
		return $res;
	}

	
	
	
	
	
	
	
	
	/*	
	public function estaRegistrado($actualiza=true){
		$db=Tool::conectaBD();
		
		if(!$db){
            Tool::log("[ERROR] Error conectando a la base de datos buscando registro de comprador" . PHP_EOL . mysql_errno . ":" . mysql_error($db),LOG);
        }
		else{
			$sql="SELECT * FROM Compradores WHERE email='" . $this->email . "'";
			$res=Tool::consulta($sql,$db);
			
			$aux=mysql_affected_rows();
			
			if($aux<0){
                Tool::log("[ERROR] Error ejecutando consulta buscando registro de comprador" . PHP_EOL . mysql_errno . ":" . mysql_error($db),LOG);
                Tool::desconectaBD($db);			
				return "-1";
			}
			else{
				if($aux==0){
					$resul=false;
				}
				else{	
					$resul=true;
                    ////
                    if($actualiza){
                        if($res[0]['Nombre']==""){
                            $sql="UPDATE Compradores SET Nombre='" . $this->nombre . "' WHERE Email='" . $this->email . "'";
                            Tool::ejecuta($sql,$db);
                        }
                        if($res[0]['Apellidos']==""){
                            $sql="UPDATE Compradores SET Apellidos='" . $this->apellidos . "' WHERE Email='" . $this->email . "'";
                            Tool::ejecuta($sql,$db);
                        }
                    }
                    ////
				}
				Tool::desconectaBD($db);
                return $resul;
			}
		}	
	}
	
	public static function estaArchivado($id){
		$db=Tool::conectaBD();
		
		$sql="SELECT * FROM HistoricoCompradores WHERE Email='" . $id . "'";		
		$res=Tool::consulta($sql, $db);
		
		$aux=mysql_affected_rows();
		
		Tool::desconectaBD($db);
		
		if ($aux>0){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function addComprador(){
		$db=Tool::conectaBD();
		if(!$db){
            Tool::log("[ERROR] Error conectando a la base de datos registrando comprador" . PHP_EOL . mysql_errno . ":" . mysql_error($db),LOG);
			$res=false;
		}
		else{
			$sql="INSERT INTO Compradores (nombre,apellidos,email) VALUES ('" . $this->nombre . "','" . $this->apellidos . "','" . $this->email . "')";
			$res=Tool::ejecutaConsulta($sql,$db);
			Tool::desconectaBD($db);
			
			return $res;
		}
	}
	
	
	public static function addNuevoComprador($nombre,$apellidos,$email){
		$db=Tool::conectaBD();
		if(!$db){
            Tool::log("[ERROR] Error conectando a la base de datos registrando comprador" . PHP_EOL . mysql_errno . ":" . mysql_error($db),LOG);
			$res=false;
		}
		else{
			$sql="INSERT INTO Compradores (nombre,apellidos,email) VALUES ('" . $nombre . "','" . $apellidos . "','" . $email . "')";
			$res=Tool::ejecutaConsulta($sql,$db);
			Tool::desconectaBD($db);
			
			return $res;
		}
	}
	
	
	
	public function listadoCompradores(){
		$db=Tool::conectaBD();
		if(!$db){
		//error
		}
		else{
			$sql="SELECT * FROM Compradores";
			$res=Tool::consulta($sql,$db);
			Tool::desconectaBD($db);
			
			return $res;
		}
	}

    public function getDatosComprador($id){
        $db=Tool::conectaBD();
        if(!$db){
            //error
        }
        else{
            $id=Tool::limpiaCadena($id);

            $sql="SELECT * FROM Compradores WHERE Email='" . $id . "'";
            $res=Tool::consulta($sql,$db);
            Tool::desconectaBD($db);

            return $res[0];
        }
    }

    public function updateComprador($nuevo_nombre,$nuevo_apellidos,$nuevo_email, $emailID){
        $db=Tool::conectaBD();
        if(!$db){
            //error
            $res=false;
        }
        else{

            //Saneamiento cadenas de entrada
            $nombre=Tool::limpiaCadena($nuevo_nombre);
            $apellidos=Tool::limpiaCadena($nuevo_apellidos);
            $email=Tool::limpiaCadena($nuevo_email);
            $emailID=Tool::limpiaCadena($emailID);

            $sql="UPDATE Compradores SET Nombre='" . $nombre . "',Apellidos='" . $apellidos . "', Email='" . $email . "' WHERE Email='" . $emailID . "'";


            $res=Tool::ejecuta($sql,$db);

            Tool::desconectaBD($db);

            return $res;
        }
    }


    public static function deleteComprador($id){
        $db=Tool::conectaBD();
        if(!$db){
            //error
            $res=false;
        }
        else{

            //Saneamiento cadenas de entrada
            $emailID=Tool::limpiaCadena($id);

            $sql="DELETE FROM Compradores WHERE Email='" . $emailID . "'";


            $res=Tool::ejecutaConsulta($sql,$db);

            Tool::desconectaBD($db);

            return $res;
        }
    }

    public function getComprador($id){
        $db=Tool::conectaBD();
        if(!db){
            //error
        }
        else{
            $id=Tool::limpiaCadena($id);

            $sql="SELECT * FROM Compradores WHERE Email='" . $id . "'";
            $res=Tool::consulta($sql,$db);
            Tool::desconectaBD($db);

            if (!is_null($res[0])){
                $this->nombre=$res[0]['Nombre'];
                $this->apellidos=$res[0]['Apellidos'];
                $this->email=$res[0]['Email'];
            }
            else{
                $this->nombre="";
                $this->apellidos="";
                $this->email="";
            }
        }
    }


    public static function archivaComprador($id){
    	$db=Tool::conectaBD();
    	
    	$archivado=false;
    	
    	if(!$db){
    		Tool::log("[ERROR] Error conectando a la base de datos archivando comprador" . PHP_EOL . mysqli_errno($db) . ":" . mysqli_error($db),LOG);    		
    	}
    	else{    		
    		$c=new Comprador();
    		$c->getComprador($id);
    		
    		$sql="INSERT INTO HistoricoCompradores (nombre,apellidos,email) VALUES ('" . $c->nombre . "','" . $c->apellidos . "','" . $c->email . "')";

    		
    		if($c->email<>""){    			
    			if(!Comprador::estaArchivado($c->email)){
    				if(Tool::ejecutaConsulta($sql, $db)){
    					//echo "Comprador " . $c->email . " archivado<br/>";
    					$archivado=true;
    				}
    				else{
    					//echo "Error en la insercion del comprador " . $c->email . " -> " . mysql_error($db) . "<br/>
    					//	  SQL->" . $sql . "<br/><hr/>";
    				}
    			}
    			else{
    				$archivado=true;
    				//echo "Comprador " . $id . " ya esta archivado<br/>";
    			}
    		}
    		else{
    			//echo "Comprador " . $id . " no encontrado<br/>";
    		}
    		
			if($archivado){
				Comprador::deleteComprador($id);
			}
    	}
    	Tool::desconectaBD($db);
    	
    	return $archivado;
    }
    
	public function aTexto(){
		return ($this->nombre . " " . $this->apellidos . " * " . $this->email);
	}
	
*/	
//Fin de la clase Comprador

}

?>