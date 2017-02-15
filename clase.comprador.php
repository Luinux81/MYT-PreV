<?php
include_once "./clase.tool.php";

if (!defined('LOG')) define('LOG', './ipn.log');

class Comprador{

	public $nombre;
	public $apellidos;
	public $email;
		
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

    /**
     * Función para borrar un comprador de la tabla Compradores.
     * @param unknown $id Email del comprador.
     */
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

    /**
     * Función para mover un comprador de la tabla Compradores a HistoricoCompradores. El registro desaparecerá de la tabla Compradores.
     * @param unknown $id Email del comprador.
     */
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
	
//Fin de la clase Comprador

}

?>