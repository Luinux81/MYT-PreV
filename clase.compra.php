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
	

	public function compraCompletada(){
		return ($this->estado=="Completed");		
	}
	
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
	
	public function compraRegistrada(){
		$t=new Tool();		
		$db=$t->conectaBD();
		if(!$db){
            Tool::log("[ERROR] Error conectando a la base de datos buscando registro de compra" . PHP_EOL . mysql_errno . " : " . mysql_error($db),LOG);
		}
		else{
			$sql="SELECT * FROM Compras WHERE Id='" . $this->id_transaccion . "'";
			$res=$t->consulta($sql,$db);			
			
			$aux=mysql_affected_rows();
			
			if($aux<0){
                Tool::log("[ERROR] Error ejecutando consulta buscando registro de compra" . PHP_EOL . mysql_errno . ":" . mysql_error($db),LOG);
				$t->desconectaBD($db);			
				return "-1";			
			}
			else{
				$t->desconectaBD($db);
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
	
	public function registraCompra($autoTickets=true){
		$t=new Tool();
		$db=$t->conectaBD();
		if(!$db){
			return false;
		}
		else{
			$sql="INSERT INTO Compras (Id,IdVendedor,IdComprador,importe,fecha,cantidad,IdEvento)
			VALUES ('" . $this->id_transaccion . "','" . $this->email_vendedor . "','" . $this->email_comprador . "','" . $this->precio . "',FROM_UNIXTIME(" . time()/*date("d/m/Y H:i")*/ . ")," . $this->cantidad . ",'" . $this->id_evento . "')";
			
			if(DEBUG_CLASES){
				Tool::log("SQL: " . $sql,LOG);
			}
			
			$res=$t->ejecuta($sql,$db);
			//Aqui control de error de la consulta, logear

            if($autoTickets){
                for($i=0;$i<$this->cantidad;$i++){
                    $sql="INSERT INTO Tickets (IdCompra,Codigo,IdTipo) VALUES ('" . $this->id_transaccion . "','" . $this->id_transaccion . $i . "','" . $this->item_id . "')";
                    $res=$t->ejecuta($sql,$db);
                }
            }

			/*
            //Codigo para bloquear la oferta al comprar una vez
            if($this->item_id=="00001"){
                Oferta::aceptarOferta(1,$this->email_comprador);
            }
			*/
			
			$t->desconectaBD($db);
			
			if(DEBUG_CLASES){
                Tool::log("RES: " . $res,LOG);
			}
			
			if(!$res){
                Tool::log("[ERROR] Error en registro de compra con ID " . $this->id_transaccion . PHP_EOL . "SQL: " . $sql . PHP_EOL . "Mysql Error: " . mysql_error(),LOG);
			}
			
			return $res;
		}
	}
	
	public function aTexto(){
		return $this->id_transaccion;
	}


    public function listadoCompras($orden){
        $t=new Tool();
        $db=$t->conectaBD();

        if(!db){
            //error
        }
        else{
            $sql="SELECT * FROM Compras";
            $res=$t->consulta($sql,$db);
            $t->desconectaBD($db);

            return $res;
        }
    }

    public function getCompra($id){
        $t=new Tool();
        $db=$t->conectaBD();

        if(!db){
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
            $res=$t->consulta($sql,$db);
            $t->desconectaBD($db);

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

    public static function getTickets($id){
        $t=new Tool();
        $tick=new Ticket();

        $db=$t->conectaBD();

        if(!db){

        }
        else{
            $sql="SELECT cli.Apellidos,cli.Nombre,cli.Email, c.Id,t.Codigo
        FROM Compras as c INNER JOIN Compradores AS cli ON cli.email=c.IdComprador
        INNER JOIN Tickets AS t ON c.Id=t.IdCompra
        WHERE c.Id='" . $id . "'";

            $aux=$t->consulta($sql,$db);
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
            $t->desconectaBD($db);

            return $res;
        }
    }

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
        $t=new Tool();
        $db=$t->conectaBD();
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
            $res=$t->consulta($sql,$db);
            $num=count($res);

            $nuevo_cod=$idCompra . $num;

            $sql="INSERT INTO Tickets(Codigo,IdCompra,IdTipo,Entregado) VALUES ('" .
                 $nuevo_cod ."','" . $idCompra . "','00002',0)";

            $res=$t->ejecuta($sql,$db);

            $t->desconectaBD($db);

            return $res;
        }
    }

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
        $t=new Tool();
        $db=$t->conectaBD();
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
            $res=$t->consulta($sql,$db);
            $num=count($res)-1;

            if($num<0){
                return true;
            }
            $ultimo_cod=$idCompra . $num;

            $sql="DELETE FROM Tickets WHERE Codigo='" . $ultimo_cod . "'";

            $res=$t->ejecuta($sql,$db);


            $t->desconectaBD($db);

            return $res;
        }
    }

    public static function actualizaCantidad($idcompra){
        $t=new Tool();

        if($idcompra==""){
            return false;
        }

        $db=$t->conectaBD();
        $idcompra=Tool::limpiaCadena($idcompra);

        if(!$db){
            return false;
        }

        else{
            $sql="SELECT * FROM Tickets WHERE IdCompra='" . $idcompra . "'";
            $res=$t->consulta($sql,$db);
            $num=count($res);

            $sql="SELECT * FROM Compras WHERE Id='" . $idcompra . "'";
            $res=$t->consulta($sql,$db);
            if(count($res)>0){
                $precio=$res[0]['Importe'];
                $cantidad=$res[0]['Cantidad'];

                $precio=($precio/$cantidad)*$num;

                $sql="UPDATE Compras SET Cantidad='" . $num . "', Importe='" . $precio . "' WHERE Id='" . $idcompra . "'";
                $res=$t->ejecuta($sql,$db);
            }
            else{
                $res=false;
            }

            $t->desconectaBD($db);

            return $res;
        }
    }

    public function updateCompra($nuevo_ID,$nuevo_email,$nueva_cantidad, $nuevo_importe,$idCompra){
        $t=new Tool();
        $db=$t->conectaBD();
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

            $res=$t->ejecuta($sql,$db);

            $t->desconectaBD($db);

            return $res;
        }
    }

    public function deleteCompra($id){
        $t=new Tool();
        $db=$t->conectaBD();
        if(!db){
            //error
            $res=false;
        }
        else{

            //Saneamiento cadenas de entrada
            $id=Tool::limpiaCadena($id);

            $sql="DELETE FROM Compras WHERE Id='" . $id . "'";

            $res=$t->ejecuta($sql,$db);

            $t->desconectaBD($db);

            return $res;
        }
    }

    public function getEvento($idTicket){
        $t=new Tool();
        $db=$t->conectaBD();

        if(!$db){
            return false;
        }
        else{
            $sql="SELECT IdEvento FROM TipoTicket WHERE Id='" . $idTicket . "'";
            $res=$t->consulta($sql,$db);

            if(!is_null($res[0])){
                return $res[0]['IdEvento'];
            }
            else{
                return false;
            }
        }

        $t->desconectaBD($db);
    }

//Fin de la clase Compra
}


?>