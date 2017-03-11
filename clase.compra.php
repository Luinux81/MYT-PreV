<?php
include_once "./clase.ticket.php";
include_once "./clase.tipoTicket.php";
include_once "./clase.tool.php";
//include_once "./clase.oferta.php";

if (!defined('LOG')) define('LOG', './ipn.log');


define ("EMAIL_PAYPAL1","contact@transitionfestival.org");
define ("EMAIL_PAYPAL2","tickets@transitionfestival.org");

/*
define ("EMAIL_PAYPAL1","seller@paypalsandbox.com");
define ("EMAIL_PAYPAL2","luinux81-facilitator@gmail.com");
*/
define ("EMAIL_NOTIFICACION","druida@transitionfestival.org");
define ("DEBUG_CLASES",0);

class Compra{

	public $email_vendedor;
	public $email_comprador;
    public $id_evento;
	public $item;
	public $item_id;
	public $precio;
    public $importe;
	public $cantidad;
	public $comprador;
	public $estado;
	public $id_transaccion;
	public $fecha;
	

	/**
	 * Esta función comprueba el estado de la compra es "Completed"
	 * @return boolean
	 */
	public function compraCompletada(){
		return ($this->estado=="Completed");		
	}
	
	/**
	 * Esta función comprueba que el email del vendedor está configurado en la app, que el estado ded la compra este completado y que el tipo de ticket existe
	 * @return boolean
	 */
	public function compraVerificada(){
		if ((($this->email_vendedor==EMAIL_PAYPAL1) || ($this->email_vendedor==EMAIL_PAYPAL2)) && $this->estado=="Completed"){
            if(TipoTicket::existeTipo($this->item_id)){
                return true;
            }
            else{
                Tool::log("[ERROR]El tipo de ticket no está registrado",LOG);
                return false;
            }
        }
        else{
            return false;
        }
	}
	
	/**
	 * Esta función devuelve True si existe una compra en la base de datos con ID igual al id_transaccion
	 * @return boolean
	 */
	public function compraRegistrada(){				
		$db=Tool::conectaBD();
		
		if(!$db){
            Tool::log("[ERROR] Error conectando a la base de datos buscando registro de compra" . PHP_EOL . mysql_errno . " : " . mysql_error($db),LOG);
		}
		else{
			$sql="SELECT * FROM Compras WHERE Id='" . $this->id_transaccion . "'";
			$res=Tool::consulta($sql,$db);			
			
			$aux=mysql_affected_rows();
			
			if($aux<0){
                Tool::log("[ERROR] Error ejecutando consulta buscando registro de compra" . PHP_EOL . mysql_errno . ":" . mysql_error($db),LOG);
                Tool::desconectaBD($db);			
				return false;			
			}
			else{
				Tool::desconectaBD($db);
				if($aux==0){
					return false;					 
				}
				else{
					return true;
				}
			}			
			
			
			return (count($res)>0);	
		}
	}
	
	/**
	 * Esta función comprueba que la compra está guardada en el historico.
	 * @param string $id ID de la compra  
	 * @return boolean True si la compra esta guardada
	 */
	public static function estaArchivada($id){
		$db=Tool::conectaBD();
		
		$sql="SELECT * FROM HistoricoCompras WHERE Id='" . $id ."'";
		$res=Tool::ejecutaConsulta($sql, $db);
		
		$aux=mysqli_affected_rows($db);
		
		Tool::desconectaBD($db);
		
		return ($aux>0);
	}
	
	/**
	 * Esta función guarda los valores de los atributos en la base de datos.
	 * @param string $autoTickets Si True genera los valores de los tickets y los guarda en la base de datos.
	 * @return boolean True si éxito guardando.
	 */
	public function registraCompra($autoTickets=true){
		$db=Tool::conectaBD();
		if(!$db){
			return false;
		}
		else{
			$sql="INSERT INTO Compras (Id,IdVendedor,IdComprador,importe,fecha,cantidad,IdEvento)
			VALUES ('" . $this->id_transaccion . "','" . $this->email_vendedor . "','" . $this->email_comprador . "','" . $this->precio . "',FROM_UNIXTIME(" . time()/*date("d/m/Y H:i")*/ . ")," . $this->cantidad . ",'" . $this->id_evento . "')";
			
			if(DEBUG_CLASES){
				Tool::log("SQL: " . $sql,LOG);
			}
			
			$res=Tool::ejecuta($sql,$db);
			//Aqui control de error de la consulta, logear

            if($autoTickets){
                for($i=0;$i<$this->cantidad;$i++){
                    $sql="INSERT INTO Tickets (IdCompra,Codigo,IdTipo) VALUES ('" . $this->id_transaccion . "','" . $this->id_transaccion . $i . "','" . $this->item_id . "')";
                    $res=Tool::ejecuta($sql,$db);
                }
            }

			/*
            //Codigo para bloquear la oferta al comprar una vez
            if($this->item_id=="00001"){
                Oferta::aceptarOferta(1,$this->email_comprador);
            }
			*/
			
            Tool::desconectaBD($db);
			
			if(DEBUG_CLASES){
                Tool::log("RES: " . $res,LOG);
			}
			
			if(!$res){
                Tool::log("[ERROR] Error en registro de compra con ID " . $this->id_transaccion . PHP_EOL . "SQL: " . $sql . PHP_EOL . "Mysql Error: " . mysql_error(),LOG);
			}
			
			return $res;
		}
	}
	
	/**
	 * PARA ELIMINAR
	 * @return string
	 */
	public function aTexto(){
		return $this->id_transaccion;
	}


	/**
	 * Esta función devuelve las compras guardadas en la base de datos
	 * @param string $filtro Cadena para la clausula WHERE
	 * @return unknown Array de resultados
	 */
    public function listadoCompras($filtro="1"){
        $db=Tool::conectaBD();

        if(!$db){
            //error
        }
        else{
        	$filtro=Tool::limpiaCadena($filtro);
            $sql="SELECT * FROM Compras WHERE " . $filtro;
            $res=Tool::ejecutaConsulta($sql,$db);
            Tool::desconectaBD($db);

            return $res;
        }
    }

    /**
     * Esta función obtiene la información de compra de la base de datos y la guarda en el objeto invocador.
     * @param unknown $id ID de la compra
     */
    public function getCompra($id){
        $db=Tool::conectaBD();

        if(!$db){
            $this->id_transaccion="";
            $this->email_comprador="";
            $this->email_vendedor="";
            $this->fecha="";
            $this->importe="";
            $this->cantidad="";
        }
        else{
            $id=Tool::limpiaCadena($id);

            $sql="SELECT * FROM Compras WHERE Id='" . $id . "'";
            $res=Tool::consulta($sql,$db);
            Tool::desconectaBD($db);

            if(!is_null($res[0])){
                $this->id_transaccion=$res[0]['Id'];
                $this->email_comprador=$res[0]['IdComprador'];
                $this->email_vendedor=$res[0]['Idvendedor'];
                $this->fecha=$res[0]['Fecha'];
                $this->importe=$res[0]['Importe'];
                $this->precio=$res[0]['Importe'];
                $this->item="Tickets";

                $this->cantidad=$res[0]['Cantidad'];

                $cli=new Comprador();
                $cli->getComprador($this->email_comprador);
                $this->comprador=$cli;
            }
            else{
                $this->id_transaccion="";
                $this->email_comprador="";
                $this->email_vendedor="";
                $this->fecha="";
                $this->importe="";
                $this->cantidad="";
            }
        }
    }

    /**
     * Esta función obtiene información de la base de datos y devuelve un array de objetos Ticket asociados a una compra.
     * @param unknown $id ID de la compra
     * @return Ticket[] Tickets asociados a la compra
     */
    public static function getTickets($id){
        $tick=new Ticket();

        $db=Tool::conectaBD();

        if(!$db){

        }
        else{
            $sql="SELECT cli.Apellidos,cli.Nombre,cli.Email, c.Id,t.Codigo
        FROM Compras as c INNER JOIN Compradores AS cli ON cli.email=c.IdComprador
        INNER JOIN Tickets AS t ON c.Id=t.IdCompra
        WHERE c.Id='" . $id . "'";

            $aux=Tool::consulta($sql,$db);
            $res=array();
            $i=0;

            foreach($aux as $a){
                $tick->nombre=$a['Nombre'];
                $tick->apellidos=$a['Apellidos'];
                $tick->email=$a['Email'];
                $tick->IdCompra=$a['Id'];
                $tick->codigo=$a['Codigo'];

                $res[$i]=$tick;
                $i=$i+1;
            }
            Tool::desconectaBD($db);

            return $res;
        }
    }

    /**
     * Esta función crea registros de nuevos tickets en la base de datos.
     * @param unknown $idCompra ID de la compra a la que estarán asociados los tickets.
     * @param unknown $num Número de tickets a añadir
     * @return boolean
     */
    public static function addTickets($idCompra,$num){
        $res=true;
        for($i=1;$i<=$num;$i++){
            if(!Compra::addTicket($idCompra)){
                $res=false;
            }
        }
        return $res;
    }

    private static function addTicket($idCompra){
        $db=Tool::conectaBD();
        $idCompra=Tool::limpiaCadena($idCompra);

        if($idCompra==""){
            return false;
        }

        if(!$db){
            return false;
        }
        else{
            $sql="SELECT * FROM Tickets WHERE IdCompra='" . $idCompra . "'";
            //$res=Compra::getTickets($idCompra);
            $res=Tool::consulta($sql,$db);
            $num=count($res);

            $nuevo_cod=$idCompra . $num;

            $sql="INSERT INTO Tickets(Codigo,IdCompra,IdTipo,Entregado) VALUES ('" .
                 $nuevo_cod ."','" . $idCompra . "','00002',0)";

            $res=Tool::ejecuta($sql,$db);

            Tool::desconectaBD($db);

            return $res;
        }
    }

    /**
     * Esta función elimina registros de tickets en la base de datos
     * @param unknown $idCompra ID de la compra 
     * @param unknown $num Número de tickets a eliminar
     * @return boolean
     */
    public static function deleteTickets($idCompra,$num){
        $res=true;
        for($i=1;$i<=$num;$i++){
            if(!Compra::deleteTicket($idCompra)){
                $res=false;
            }
        }
        return $res;
    }

    private static function deleteTicket($idCompra){
        $db=Tool::conectaBD();
        $idCompra=Tool::limpiaCadena($idCompra);

        if($idCompra==""){
            return false;
        }

        if(!$db){
            return false;
        }
        else{
            $sql="SELECT * FROM Tickets WHERE IdCompra='" . $idCompra . "'";
            //$res=Compra::getTickets($idCompra);
            $res=Tool::consulta($sql,$db);
            $num=count($res)-1;

            if($num<0){
                return true;
            }
            $ultimo_cod=$idCompra . $num;

            $sql="DELETE FROM Tickets WHERE Codigo='" . $ultimo_cod . "'";

            $res=Tool::ejecuta($sql,$db);


            Tool::desconectaBD($db);

            return $res;
        }
    }

    public static function actualizaCantidad($idcompra){
        if($idcompra==""){
            return false;
        }

        $db=Tool::conectaBD();
        $idcompra=Tool::limpiaCadena($idcompra);

        if(!$db){
            return false;
        }

        else{
            $sql="SELECT * FROM Tickets WHERE IdCompra='" . $idcompra . "'";
            $res=Tool::consulta($sql,$db);
            $num=count($res);

            $sql="SELECT * FROM Compras WHERE Id='" . $idcompra . "'";
            $res=Tool::consulta($sql,$db);
            if(count($res)>0){
                $precio=$res[0]['Importe'];
                $cantidad=$res[0]['Cantidad'];

                $precio=($precio/$cantidad)*$num;

                $sql="UPDATE Compras SET Cantidad='" . $num . "', Importe='" . $precio . "' WHERE Id='" . $idcompra . "'";
                $res=Tool::ejecuta($sql,$db);
            }
            else{
                $res=false;
            }

            Tool::desconectaBD($db);

            return $res;
        }
    }

    /**
     * Función para modificar el registro de una compra existente en la base de datos.
     * @param unknown $nuevo_ID Nuevo ID de la compra
     * @param unknown $nuevo_email Email del comprador
     * @param unknown $nueva_cantidad Cantidad
     * @param unknown $nuevo_importe Importe
     * @param unknown $idCompra ID de la compra existente
     * @return boolean
     */
    public function updateCompra($nuevo_ID,$nuevo_email,$nueva_cantidad, $nuevo_importe,$idCompra){
        $db=Tool::conectaBD();
        if(!db){
            //error
            $res=false;
        }
        else{

            //Saneamiento cadenas de entrada
            $id=Tool::limpiaCadena($nuevo_ID);
            $email=Tool::limpiaCadena($nuevo_email);
            $cantidad=Tool::limpiaCadena($nueva_cantidad);
            $importe=Tool::limpiaCadena($nuevo_importe);

            $sql="UPDATE Compras
            SET Id='" . $id . "',IdComprador='" . $email . "', Importe='" . $importe . "', Cantidad='" . $cantidad . "'
            WHERE Id='" . $idCompra . "'";

            $res=Tool::ejecuta($sql,$db);

            Tool::desconectaBD($db);

            return $res;
        }
    }

    /**
     * Función para borrar una compra de la tabla Compras.
     * @param unknown $id Id de la compra.
     */
    public static function deleteCompra($id){
        $db=Tool::conectaBD();
        if(!$db){
            //error
            $res=false;
        }
        else{

            //Saneamiento cadenas de entrada
            $id=Tool::limpiaCadena($id);

            $sql="DELETE FROM Compras WHERE Id='" . $id . "'";

            $res=Tool::ejecutaConsulta($sql,$db);

            Tool::desconectaBD($db);

            return $res;
        }
    }

    public function getEvento($idTicket){
        $db=Tool::conectaBD();

        if(!$db){
            return false;
        }
        else{
            $sql="SELECT IdEvento FROM TipoTicket WHERE Id='" . $idTicket . "'";
            $res=Tool::consulta($sql,$db);

            if(!is_null($res[0])){
                return $res[0]['IdEvento'];
            }
            else{
                return false;
            }
        }

        Tool::desconectaBD($db);
    }

    /**
     * Función para mover una compra de la tabla Compras a HistoricoCompras. El registro desaparecerá de la tabla Compras.
     * @param unknown $id Id de la compra.
     */
    public static function archivaCompra($id){
    	$db=Tool::conectaBD();
    	$archivado=false;
    	
    	if(!$db){
    		Tool::log("[ERROR] Error conectando a la base de datos archivando compra" . PHP_EOL . mysql_errno . ":" . mysql_error($db),LOG);
    	}
    	else{
    		$c=new Compra();
    		$c->getCompra($id);
    	
    		$sql="INSERT INTO HistoricoCompras (Id,IdVendedor,IdComprador,importe,fecha,cantidad,IdEvento)
			VALUES ('" . $c->id_transaccion . "','" . $c->email_vendedor . "','" . $c->email_comprador . "','" 
					. $c->precio . "','" . $c->fecha . "'," . $c->cantidad . ",'" . $c->id_evento . "')";    		
    		
    		    	
    		if($c->id_transaccion<>""){
    			if(!Compra::estaArchivada($c->id_transaccion)){
    				if(Tool::ejecutaConsulta($sql, $db)){
    					//echo "Compra " . $c->id_transaccion . " archivado<br/>";
    					$archivado=true;
    				}
    				else{
    					//echo "Error en la insercion del compra " . $c->id_transaccion . " -> " . mysqli_error($db) . "<br/>
    					//	  SQL->" . $sql . "<br/><hr/>";
    				}
    			}
    			else{
    				$archivado=true;
    				//echo "Compra " . $id . " ya esta archivado<br/>";
    			}
    		}
    		else{
    			//echo "Compra " . $id . " no encontrado<br/>";
    		}
    	
    		if($archivado){
    			Compra::deleteCompra($id);
    		}
    	}
    	Tool::desconectaBD($db);
    	 
    	return $archivado;
    }
//Fin de la clase Compra
}


?>
