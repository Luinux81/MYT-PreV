<?php
include_once "./clase.tool.php";

if (!defined('LOG')) define('LOG', './ipn.log');

class Comprador{

	public $nombre;
	public $apellidos;
	public $email;
		
	public function estaRegistrado($actualiza=true){
		$t=new Tool();				
		$db=$t->conectaBD();
		
		if(!$db){
            Tool::log("[ERROR] Error conectando a la base de datos buscando registro de comprador" . PHP_EOL . mysql_errno . ":" . mysql_error($db),LOG);
        }
		else{
			$sql="SELECT * FROM Compradores WHERE email='" . $this->email . "'";
			$res=$t->consulta($sql,$db);
			
			$aux=mysql_affected_rows();
			
			if($aux<0){
                Tool::log("[ERROR] Error ejecutando consulta buscando registro de comprador" . PHP_EOL . mysql_errno . ":" . mysql_error($db),LOG);
				$t->desconectaBD($db);			
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
                            $t->ejecuta($sql,$db);
                        }
                        if($res[0]['Apellidos']==""){
                            $sql="UPDATE Compradores SET Apellidos='" . $this->apellidos . "' WHERE Email='" . $this->email . "'";
                            $t->ejecuta($sql,$db);
                        }
                    }
                    ////
				}
                $t->desconectaBD($db);
                return $resul;
			}
		}	
	}
	
	public function registraComprador(){
		$t=new Tool();
		$db=$t->conectaBD();
		if(!db){
            Tool::log("[ERROR] Error conectando a la base de datos registrando comprador" . PHP_EOL . mysql_errno . ":" . mysql_error($db),LOG);
			$res=false;
		}
		else{
			$sql="INSERT INTO Compradores (nombre,apellidos,email) VALUES ('" . $this->nombre . "','" . $this->apellidos . "','" . $this->email . "')";
			$res=$t->ejecuta($sql,$db);
			$t->desconectaBD($db);
			
			return $res;
		}
	}
	
	public function registraCompradorDatos($nombre,$apellidos,$email){
		$t=new Tool();
		$db=$t->conectaBD();
		if(!db){
            Tool::log("[ERROR] Error conectando a la base de datos registrando comprador" . PHP_EOL . mysql_errno . ":" . mysql_error($db),LOG);
			$res=false;
		}
		else{
			$sql="INSERT INTO Compradores (nombre,apellidos,email) VALUES ('" . $nombre . "','" . $apellidos . "','" . $email . "')";
			$res=$t->ejecuta($sql,$db);
			$t->desconectaBD($db);
			
			return $res;
		}
	}
	
	public function listadoCompradores($filtro="1"){
		$t=new Tool();
		$db=$t->conectaBD();
		if(!db){
		//error
		}
		else{
			$sql="SELECT * FROM Compradores WHERE " . $filtro;
			$res=$t->consulta($sql,$db);
			$t->desconectaBD($db);
			
			return $res;
		}
	}

    public function getDatosComprador($id){
        $t=new Tool();
        $db=$t->conectaBD();
        if(!db){
            //error
        }
        else{
            $id=Tool::limpiaCadena($id);

            $sql="SELECT * FROM Compradores WHERE Email='" . $id . "'";
            $res=$t->consulta($sql,$db);
            $t->desconectaBD($db);

            return $res[0];
        }
    }

    public function updateComprador($nuevo_nombre,$nuevo_apellidos,$nuevo_email, $emailID){
        $t=new Tool();
        $db=$t->conectaBD();
        if(!db){
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


            $res=$t->ejecuta($sql,$db);

            $t->desconectaBD($db);

            return $res;
        }
    }

    public function deleteComprador($id){
        $t=new Tool();
        $db=$t->conectaBD();
        if(!db){
            //error
            $res=false;
        }
        else{

            //Saneamiento cadenas de entrada
            $emailID=Tool::limpiaCadena($id);

            $sql="DELETE FROM Compradores WHERE Email='" . $emailID . "'";


            $res=$t->ejecuta($sql,$db);

            $t->desconectaBD($db);

            return $res;
        }
    }

    public function getComprador($id){
        $t=new Tool();
        $db=$t->conectaBD();
        if(!db){
            //error
        }
        else{
            $id=Tool::limpiaCadena($id);

            $sql="SELECT * FROM Compradores WHERE Email='" . $id . "'";
            $res=$t->consulta($sql,$db);
            $t->desconectaBD($db);

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

	public function aTexto(){
		return ($this->nombre . " " . $this->apellidos . " * " . $this->email);
	}
	
//Fin de la clase Comprador

}

?>