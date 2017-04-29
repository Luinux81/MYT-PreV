<?php

//include_once SITE_ROOT . "/config.php";


include_once "./config.php";
include_once "../config.php";


if (!defined('LOG')) define('LOG', './ipn.log');
define ("DEBUG_SQL",0);

/**
 * Clase con funciones útiles para el resto de clases
 *
 * La clase Tool agrupa funciones de conexión y consulta a base de datos; para log de errores; envio de notificaciones y tickets por email.
 */
class Tool{

	/**
	 * Función para conectar a la base de datos de la aplicación.
	 *
	 * @return resource Devuelve un enlace a la base de datos o lanza excepcion en caso de error.
	 */
	public static function conectaBD(){
		$link=new mysqli(BD_URL,BD_USUARIO,BD_PASS,BD_NOMBRE);
		
		if ($link->connect_errno) {
			$mes="Error en la conexión a la base de datos" . PHP_EOL . $link->connect_errno . ":" . $link->connect_error;
			Tool::log($mes,LOG);
			throw new Exception($mes);
		}
		else{
			return $link;
		}
	}

	/**
	 * Función para cerrar un enlace con la base de datos de la aplicación.
	 *
	 * @param resource $db Enlace a la base de datos.
	 */
	public static function desconectaBD($db){
		$db->close();
	}

	/**
	 * Función que ejecuta una consulta SQL sobre una conexión abierta.
	 * @param string $sql Consulta SQL
	 * @param resource $db Enlace a la base de datos
	 * @return mixed Devuelve FALSE en caso de error. Si una consulta del tipo SELECT, SHOW, DESCRIBE o EXPLAIN es exitosa, mysqli_query() devolverá un objeto mysqli_result. Para otras consultas exitosas devolverá TRUE. 
	 */
	public static function ejecutaConsulta($sql,$db){
		$res=mysqli_query($db, $sql);
		
		return $res;		
	}

    /**
     * Esta función escribe una entrada con fecha en el archivo de log pasado como parámetro
     * @param string $linea Cadena de texto que se escribirá en el archivo.
     * @param string $file Archivo en el que se escribirá la línea.
     */
    public function loglinea($linea,$file){
		error_log(date('[d/m/Y H:i]') . " " . $linea . PHP_EOL, 3, $file);
	}

    /**
     * Versión estática de la función loglinea. Escribe una entrada con fecha en el archivo de log pasado como parámetro.
     * @param string $linea Cadena de texto que se escribirá en el archivo.
     * @param string $file Archivo en el que se escribirá la línea.
     */
    public static function log($linea,$file){
        error_log(date('[d/m/Y H:i]') . " " . $linea . PHP_EOL, 3, $file);
    }

    /**
     * Esta función recibe como parámetro un objeto Compra, a partir del cual genera un archivo PDF con los tickets correspondientes y los envia adjuntos en un email. Registra en el archivo de log el resultado del envio de cada email.
     * @param $resultado Este parámetro no se usa.
     * @param $compra Objeto compra con todos los datos. (NO SE VERIFICA QUE EL OBJETO TENGA LOS DATOS CORRECTOS)
     */
    public static function notificaMAIL($resultado,$compra){
        $msg="<html><head></head><body><img src='" . EMAIL_URL_IMG_CABECERA . "' width='350' height='100' >";
		$msg= $msg . "<br><b>Compra Realizada</b> <br>ID:" . $compra->id_transaccion . "<br><br> <b>Datos del comprador:</b> <br>   Nombre completo: " . $compra->comprador->nombre . " " . $compra->comprador->apellidos . " <br>   Email: " . $compra->comprador->email;
		$msg=$msg . "<br><br><b>Detalles de la compra:</b><br><br>   " . $compra->cantidad . " x " . $compra->item . " ______________ " . $compra->precio . " euros <br><br>";
        $msg=$msg . "</body>";
		
		$subject=EMAIL_ASUNTO;

		$ticket=new Ticket();
		$ticket->nombre=$compra->comprador->nombre . " " . $compra->comprador->apellidos;
		$ticket->email=$compra->comprador->email;
		$ticket->codigo=$compra->id_transaccion;
		
		$doc=$ticket->creaPDF(true,$compra->cantidad);
		
		$doc = chunk_split(base64_encode($doc));

		// a random hash will be necessary to send mixed content
		$separator = md5(time());

        $message="";

/*
		// carriage return type (we use a PHP end of line constant)
		$eol = "\r\n";
        //$eol=PHP_EOL;

		// main header (multipart mandatory)
		$headers = "From: Transition Mailer <tickets@transitionfestival.org>" . $eol;
		$headers .= "MIME-Version: 1.0" . $eol;
		$headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol . $eol;
		$headers .= "Content-Transfer-Encoding: 7bit" . $eol;
		$headers .= "This is a MIME encoded message." . $eol . $eol;

		// message
		$headers .= "--" . $separator . $eol;
		$headers .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
		$headers .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
		$headers .= $msg . $eol . $eol;


		// attachment
		$headers .= "--" . $separator . $eol;
		$headers .= "Content-Type: application/octet-stream; name=ticket.pdf" . $eol;
		$headers .= "Content-Transfer-Encoding: base64" . $eol;
		$headers .= "Content-Disposition: attachment" . $eol . $eol;
		$headers .= $doc . $eol . $eol;
		$headers .= "--" . $separator . "--";
*/

        $eol = PHP_EOL;

        $headers = "From: " . EMAIL_REMITENTE . $eol;
        $headers .= "MIME-Version: 1.0" .$eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"";
        $message = "--".$separator.$eol;
        $message .= "Content-Type: text/html; charset=ISO-8859-1".$eol;
        $message .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
        $message .= $msg.$eol;
        $message .= "--".$separator.$eol;
        $message .= "Content-Type: application/octet-stream; name=ticket.pdf".$eol;
        $message .= "Content-Transfer-Encoding: base64".$eol;
        $message .= "Content-Disposition: attachment; filename=ticket.pdf".$eol.$eol;
        $message .= $doc.$eol.$eol;
        $message .= "--".$separator."--";


        //SEND Mail
		$direcciones=array();
        /*
		$direcciones[0]="tickets@transitionfestival.org";
		$direcciones[1]=$compra->comprador->email;
        $direcciones[2]="druida@transitionfestival.org";
        */
        $direcciones[0]="druida@transitionfestival.org";
        $direcciones[1]=$compra->comprador->email;

		$i=0;
		
		foreach($direcciones as $dir){
			$i=$i+1;
			if(mail($dir, $subject, $message, $headers)){
				$this->loglinea("[OK] Email " . $i . " enviado",LOG);
				}
			else{
                $error=error_get_last();
				$this->loglinea("[ERROR] Email " . $i . " NO enviado. ERROR: Tipo " . $error["type"] . " Mensaje: " . $error["message"],LOG);
			}						
		}
	}

    /**
     * Versión de prueba de la funcion notificaMAIL, esta función no registra en el archivo de log el resultado del envio de los emails.
     * @param $resultado Este parámetro no se usa.
     * @param $compra Objeto compra con todos los datos. (NO SE VERIFICA QUE EL OBJETO TENGA LOS DATOS CORRECTOS)
     * @return bool Devuelve false en caso de error y true en caso contrario.
     */
    public function notificaMAIL2($resultado,$compra){
        $msg="<html><head></head><body><img src='http://www.transitionfestival.org/images/2014_typo.jpg' width='350' height='100' >";
        $msg= $msg . "<br><b>Compra Realizada</b> <br>ID:" . $compra->id_transaccion . "<br><br> <b>Datos del comprador:</b> <br>   Nombre completo: " . $compra->comprador->nombre . " " . $compra->comprador->apellidos . " <br>   Email: " . $compra->comprador->email;
        $msg=$msg . "<br><br><b>Detalles de la compra:</b><br><br>   " . $compra->cantidad . " x " . $compra->item . " ______________ " . $compra->precio . " euros <br><br>";
        $msg=$msg . "</body>";

        $subject="TEST MYTickets";

        $ticket=new Ticket();
        $ticket->nombre=$compra->comprador->nombre . " " . $compra->comprador->apellidos;
        $ticket->email=$compra->comprador->email;
        $ticket->codigo=$compra->id_transaccion;

        $doc=$ticket->creaPDF(true,$compra->cantidad);

        $doc = chunk_split(base64_encode($doc));

        // a random hash will be necessary to send mixed content
        $separator = md5(time());

        // carriage return type (we use a PHP end of line constant)
        $eol = "\r\n";

        // main header (multipart mandatory)
        $headers = "From: Transition Mailer <tickets@transitionfestival.org>" . $eol;
        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol . $eol;
        $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
        $headers .= "This is a MIME encoded message." . $eol . $eol;

        // message
        $headers .= "--" . $separator . $eol;
        $headers .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
        $headers .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
        $headers .= $msg . $eol . $eol;


        // attachment
        $headers .= "--" . $separator . $eol;
        $headers .= "Content-Type: application/octet-stream; name=ticket.pdf" . $eol;
        $headers .= "Content-Transfer-Encoding: base64" . $eol;
        $headers .= "Content-Disposition: attachment" . $eol . $eol;
        $headers .= $doc . $eol . $eol;
        $headers .= "--" . $separator . "--";

        //SEND Mail
        $direcciones=array();
        //$direcciones[0]="tickets@transitionfestival.org";
        $direcciones[1]=$compra->comprador->email;
        $direcciones[0]="druida@transitionfestival.org";
        $i=0;

        $res=true;
        foreach($direcciones as $dir){
            $i=$i+1;
            if(mail($dir, $subject, "", $headers)){
                //$this->loglinea("[OK] Email " . $i . " enviado",LOG);
            }
            else{
                $res=false;
                //$this->loglinea("[ERROR] Email " . $i . " NO enviado",LOG);
            }
        }

        return $res;
    }

    /**
     * Función para evitar inyección de cadenas SQL maliciosas.
     * @param string $valor Cadena SQL que se va a sanear.
     * @return string Cadena SQL saneada.
     */
    public static function limpiaCadena($valor){
    	/*
    	 * Las lineas comentadas son necesarias para que funcionen los filtros de filtro.php
    	 */
        $valor = str_ireplace("SELECT","",$valor);
        $valor = str_ireplace("COPY","",$valor);
        $valor = str_ireplace("DELETE","",$valor);
        $valor = str_ireplace("DROP","",$valor);
        $valor = str_ireplace("DUMP","",$valor);
        $valor = str_ireplace(" OR ","",$valor);
        //$valor = str_ireplace("%","",$valor);
        //$valor = str_ireplace("LIKE","",$valor);
        $valor = str_ireplace("--","",$valor);
        $valor = str_ireplace("^","",$valor);
        $valor = str_ireplace("[","",$valor);
        $valor = str_ireplace("]","",$valor);
        $valor = str_ireplace("\\","",$valor);
        $valor = str_ireplace("!","",$valor);
        $valor = str_ireplace("Â¡","",$valor);
        $valor = str_ireplace("?","",$valor);
        //$valor = str_ireplace("=","",$valor);
        $valor = str_ireplace("&","",$valor);
        $valor = str_ireplace("*","",$valor);
        $valor = str_ireplace(";","",$valor);

        return $valor;
    }
    
    /**
     * Función para escribir el menú principal.
     * 
     */
    public static function menuPrincipal(){
    	 return "<ul>
    	 	<li><a href='./vista.eventos.php'>Eventos</a></li>
    		<li><a href='./vista.compradores.php'>Compradores</a></li>
        	<li><a href='./vista.ventas.php'>Ventas</a></li>
        	<li><a href='./vista.tickets.php'>Tickets</a></li>
    	 		</ul>
    			";
    }

    /**
     * Adapta el formato de una fecha al formato necesario para asignar el valor a la propiedad value de un tag HTML5 datetime-local
     * @param string $fecha Fecha de entrada en un foramto aceptado por date_parse de PHP
     * @return string Fecha en formato aceptado por el tag HTML5 datetime-local
     */
    public static function adaptaFechaBDaForm($fecha){
    	$aux=date_parse($fecha);
    	
    	$mes=$aux['month'];
    	if($mes<10){
    		$mes="0" . $mes;
    	}
    	
    	$dia=$aux['day'];
    	if($dia<10){
    		$dia="0" . $dia;
    	}
    	
    	$h=$aux['hour'];
    	if($h<10){
    		$h="0" . $h;
    	}
    	
    	$min=$aux['minute'];
    	if($min<10){
    		$min="0" . $min;
    	}
    	
    	$res=$aux['year'] . "-" . $mes . "-" . $dia . "T" . $h . ":" . $min;
    	
    	return $res;
    }




    /**
     * OBSOLETA Función para la ejecución de consultas SELECT sobre la base de datos.
     *
     * @param $sql Cadena de consulta SQL
     * @param $db Enlace a la base de datos sobre la que se realizará la consulta.
     * @param int $indiceArray Tipo de índice del array que devuelve la función. Por defecto MYSQL_BOTH, puede ser MYSQL_BOTH, MYSQL_NUM o MYSQL_ASSOC.
     * @return array|bool Devuelve un array con todos los registros de la base de datos resultantes de la consulta SQL pasada como parámetro o false en caso de error.
     */
    public static function consulta($sql,$db,$indiceArray=MYSQL_BOTH){
    	$res = mysql_query($sql,$db);
    
    
    	if(!$res){
    		Tool::log("[ERROR] SQL:" . $sql,LOG);
    		return false;
    	}
    	else{
    		if(DEBUG_SQL){
    			Tool::log("[OK] SQL:" . $sql,LOG);
    			Tool::log("FILAS:" . mysql_affected_rows(),LOG);
    		}
    			
    		if ($indiceArray!=MYSQL_BOTH && $indiceArray!=MYSQL_NUM && $indiceArray!=MYSQL_ASSOC){
    			$indiceArray=MYSQL_BOTH;
    		}
    			
    		$i=0;
    		$array=array();
    			
    		while($array[$i]=mysql_fetch_array($res,$indiceArray)){
    			$i=$i+1;
    		}
    		array_pop($array);
    			
    		return $array;
    	}
    }
    
    /**
     * OBSOLETA Función para la ejecución de consultas sobre la base de datos que no devuelven un conjunto de registros como resultado (UPDATE, INSERT o DELETE).
     * @param $sql Cadena de instrucción SQL.
     * @param $db Enlace a la base de datos sobre la que se ejecuta la consulta.
     * @return bool Devuelve true en caso de ejecución correcta y false en caso de error.
     */
    public static function ejecuta($sql,$db,$logErrors=false){
    
    	$res=mysql_query($sql,$db);
    
    	if(!$res){
    		if($logErrors){
    			Tool::log("[ERROR] SQL:" . $sql,LOG);
    		}
    			
    		echo "SQL:" . $sql . "<br/>Error: " . mysql_error($db) . "<br/>";
    			
    		return false;
    	}
    	else{
    		return true;
    	}
    
    }
    


}

?>