<?php
include_once('./fpdf.php');
include_once "./clase.tool.php";

define("HEAD_X",10);
define("HEAD_Y",10);
define("GLOBAL_WIDTH",190);
define("HEAD_LINE_HEIGHT",15);

define("HEAD2_LINE_HEIGHT",8);
define("HEAD2_Y",30);

define("BODY_LINE_HEIGHT",5);
define("OFFSETY_CENTRAL",10);

define("POSY_PRECIO",165);
define("POSY_DATA",220);


class Ticket{

public $nombre;
public $apellidos;
public $email;
public $IdCompra;
public $codigo;
public $IdTipo;

public function creaPDF($paraEmail=false,$numPaginas=1){
	$pdf=new PDF();
	$i=0;
	while($i!=$numPaginas){
		$pdf=$this->addPagina($pdf,$i);
		$i=$i+1;
	}
	if($paraEmail){
		$doc=$pdf->Output("",'S');	
		return $doc;
	}
	else{
		$pdf->Output();	
	}	
}

 public function addPagina($pdf,$num){		
	$pdf->AddPage();
	
	//Cabecera bienvenidos	
	$pdf->SetXY(HEAD_X,HEAD_Y);
	$pdf->SetFont('Arial','B',48);	
	$pdf->Cell(GLOBAL_WIDTH,HEAD_LINE_HEIGHT,"BIENVENIDO",0,2,"C",0);
	
	$pdf->SetFont('Arial','B',32);	
	$pdf->SetTextColor(150,150,150);
	$pdf->Cell(GLOBAL_WIDTH,HEAD_LINE_HEIGHT,"WELCOME",0,1,"C",0);
	
	///////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////
	
	//Seccion <<This is your ticket>> ESP
	$posX=HEAD_X;
	$posY=HEAD_Y+2*HEAD_LINE_HEIGHT;
	$pdf->SetXY($posX,$posY);	
	
	$pdf->SetFont('Arial','B',16);	
	$pdf->SetTextColor(255,255,255);
	$pdf->SetFillColor(0,0,0);	
	$pdf->Cell(GLOBAL_WIDTH/2,HEAD2_LINE_HEIGHT,"ESTA ES TU ENTRADA 2016",1,2,'C',1);
	$pdf->SetFont('Arial','',12);	
	$pdf->Cell(GLOBAL_WIDTH/2,HEAD2_LINE_HEIGHT,"IMPRIME Y PRESENTA ESTA ",1,2,'C',1);
	$pdf->Cell(GLOBAL_WIDTH/2,HEAD2_LINE_HEIGHT,"PAGINA EL DIA DEL EVENTO",1,1,'C',1);
	
	//Seccion <<This is your ticket>> ING
	$posX=GLOBAL_WIDTH/2+HEAD_X;	
	$pdf->SetXY($posX,$posY);	
	
	$pdf->SetFont('Arial','B',16);	
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFillColor(255,255,255);	
	$pdf->Cell(GLOBAL_WIDTH/2,HEAD2_LINE_HEIGHT,"THIS IS YOUR TICKET 2016","LTR",2,'C',0);
	$pdf->SetFont('Arial','',12);	
	$pdf->Cell(GLOBAL_WIDTH/2,HEAD2_LINE_HEIGHT,"PRINT AND SHOW THIS PAGE ","LR",2,'C',0);
	$pdf->Cell(GLOBAL_WIDTH/2,HEAD2_LINE_HEIGHT,"THE DAY OF THE EVENT","LRB",1,'C',0);
	
	///////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////
	
	$posYCentro=$posY+(HEAD2_LINE_HEIGHT*3)+OFFSETY_CENTRAL;
	
	//Seccion central
	$posX=HEAD_X;
	$posY=$posYCentro;
	$pdf->SetXY($posX,$posY);	
	//Celda que contendra a 3 celdas más
	$pdf->Cell(0,HEAD2_LINE_HEIGHT*2+BODY_LINE_HEIGHT*14,"",1,2,'L',1);
	
	//Seccion central izquierda
	$pdf->SetXY($posX,$posY);	
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(GLOBAL_WIDTH/2-20,HEAD2_LINE_HEIGHT,"MIERCOLES 20 ABRIL 2016",0,2,"",0);
	$pdf->SetFont('Arial','',10);	
	$pdf->MultiCell(GLOBAL_WIDTH/2-20,BODY_LINE_HEIGHT,"PINAR JURADO\n(ALMONTE-SPAIN)\n\n CEREMONIA DE APERTURA: 22:22 H\n WWW.TRANSITIONFESTIVAL.ORG\n\n\n",0,2,"",0);
			
	$pdf->SetFont('Arial','B',12);
	$pdf->SetTextColor(150,150,150);
	$pdf->Cell(GLOBAL_WIDTH/2-20,HEAD2_LINE_HEIGHT,"WEDNESDAY 20th APRIL 2016",0,2,"",0);
	$pdf->SetFont('Arial','',10);	
	$pdf->MultiCell(GLOBAL_WIDTH/2-20,BODY_LINE_HEIGHT,"PINAR JURADO\n(ALMONTE-SPAIN)\n\n OPENING CEREMONY: 22:22 H\n WWW.TRANSITIONFESTIVAL.ORG",0,2,"",0);
	
	//Seccion central centro
	$posX=GLOBAL_WIDTH/2-10;
	$posY=$posYCentro+0.3;
	$pdf->SetXY($posX,$posY);
	$pdf->SetTextColor(255,255,255);
	$pdf->SetFillColor(0,0,0);	
	$pdf->SetFont('Arial','B',32);	
	$pdf->MultiCell(40,14.25,"T\nI\nC\nK\nE\nT",0,'C',1);
	
	//Seccion central derecha
	$posX=$posX+40;
	$pdf->SetXY($posX,$posY);
	$pdf->Image("http://pruebas.transitionfestival.org/testing/logo.jpg",$posX,$posY,GLOBAL_WIDTH/2-20.5,85.8);
	
	///////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////
/*
	//Seccion PRECIO	
	$posX=GLOBAL_WIDTH/2-45;
	$posY=POSY_PRECIO;
	$pdf->SetXY($posX,$posY);
	
	$pdf->SetFont('Arial','B',14);
	$pdf->SetTextColor(0,0,0);
	
	$pdf->Write(5,"EARLY BIRD TICKET ");
	$pdf->SetTextColor(255,0,0);
	$pdf->Write(5,"40 " . Chr(128));
	$pdf->SetTextColor(0,0,0);
	$pdf->Write(5," - PRESALE ");
	$pdf->SetTextColor(255,0,0);
	$pdf->Write(5,"50 " . Chr(128));
	
	$posX=HEAD_X-5;
	$posY=POSY_PRECIO+5;
	$pdf->SetXY($posX,$posY);
	$pdf->SetFont('Arial','',10);
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(0,BODY_LINE_HEIGHT,"+ GASTOS DE GESTION (MANAGEMENT COST)",0,0,'C',0);
*/

	///////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////
	
	//Seccion REMEMBER izquierda
	$posX=HEAD_X;
	$posY=POSY_PRECIO;
	$pdf->SetXY($posX,$posY);
	
	$pdf->SetFont('Arial','B',14);
	$pdf->SetTextColor(255,255,255);
	$pdf->SetFillColor(0,0,0);
	$pdf->Cell(GLOBAL_WIDTH/2,BODY_LINE_HEIGHT,"IMPORTANTE!",1,2,'C',1);
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(GLOBAL_WIDTH/2,BODY_LINE_HEIGHT,"RECUERDA:",1,2,'C',1);
	$pdf->SetFont('Arial','',10);
	$pdf->MultiCell(GLOBAL_WIDTH/2,BODY_LINE_HEIGHT,"\nDEBES PRESENTAR \n COPIA DEL DNI O PASAPORTE DEL COMPRADOR JUNTO CON ESTA ENTRADA \n 1 CODIGO = 1 ACCESO 1 PERSONA",1,'C',1);
	
	//Seccion REMEMBER derecha
	$posX=GLOBAL_WIDTH/2+HEAD_X;
	$posY=POSY_PRECIO;
	$pdf->SetXY($posX,$posY);
	
	$pdf->SetFont('Arial','B',14);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(GLOBAL_WIDTH/2,BODY_LINE_HEIGHT,"IMPORTANT!","LTR",2,'C',1);
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(GLOBAL_WIDTH/2,BODY_LINE_HEIGHT,"REMEMBER:","LR",2,'C',1);
	$pdf->SetFont('Arial','',10);
	$pdf->MultiCell(GLOBAL_WIDTH/2,BODY_LINE_HEIGHT,"\nYOU MUST SHOW  \n A COPY OF THE PASSPORT/ID CARD OF THE BUYER WITH THIS TICKET \n 1 CODE = 1 ACCESS 1 PERSON","LBR",'C',1);
	
	///////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////
	
	//Seccion DATOS $ CODES
	$posX=HEAD_X+10;
	$posY=POSY_DATA;
	$baseline=1;
	$alturaCOD=10;
	
	$pdf->SetXY($posX,$posY);
	
	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(0,45,"",1,0,'L',1);
	$pdf->SetXY($posX,$posY);
	
	$pdf->MultiCell(0,BODY_LINE_HEIGHT,"DATOS DEL COMPRADOR / BUYER INFO:\nNombre:   " . $this->nombre . "\nEmail:       " . $this->email . "\nCOD:        " . $this->codigo . $num . "\n",1,'L',0);
	
	//Primer código de barras
	$posX=$posX+GLOBAL_WIDTH/16;
	$posY=POSY_DATA+4*BODY_LINE_HEIGHT+5;
	
	$pdf->Code39($posX,$posY,$this->codigo . $num,$baseline,$alturaCOD);

 /*
	//Segundo código de barras
	$posX=$posX+GLOBAL_WIDTH/2;
	
	$pdf->SetXY($posX,$posY);		
	$pdf->Code39($posX,$posY,$this->codigo . $num,$baseline,$alturaCOD);
*/
	return $pdf;
 } 


    /*
public function estaEntregado(){
	$db=Tool::conectaBD();
	
	$cod=$this->codigo;	
	$sql="SELECT Entregado FROM prv_tickets WHERE Codigo='" . $cod . "'";
	$res=Tool::consulta($sql,$db);	
	$res=$res[0];
	$entrega=$res["Entregado"];
	
	if($entrega!=null){		
		Tool::desconectaBD($db);
		return $entrega;
	}
	else{		
		//el codigo no existe o error de base de datos
		Tool::desconectaBD($db);
		return 0;
	}	
}

public function existe(){
	$t=new Tool();
	$db=Tool::conectaBD();
	
	$cod=$this->codigo;	
	$sql="SELECT * FROM prv_tickets WHERE Codigo='" . $cod . "'";
	$res=Tool::consulta($sql,$db);	
	Tool::desconectaBD($db);
	
	if($res[0]!=null){
		return true;
	}
	else{
		return false;
	}	
}

public function addTicket($nombre,$apellidos,$email,$idcompra,$codigo){
    $t=new Tool();
    $db=Tool::conectaBD();

    $tck=new Ticket();
    $tck->codigo;
    if ($tck->existe()){
        return false;
    }
    else{
        $sql="INSERT INTO prv_tickets (Nombre,Apellidos,Email,IdCompra,Codigo)
        VALUES (" . $nombre . "," . $apellidos . "," . $email . "," . $idcompra . "," . $codigo . ")";

        $res=mysql_query($sql,$db);
    }
}

public function entregaTicket(){
	$db=Tool::conectaBD();
	
	if($this->codigo!=""){
		$sql="UPDATE `prv_tickets` SET `Entregado`=1,`fechaEntrega`=now() WHERE `Codigo`=" . $this->codigo;		
		Tool::ejecuta($sql,$db);
		
		if(mysql_affected_rows($db)==1){
			$res=true;
		}
		else{
			$res=false;
		}
	}
	else{
		$res=false;
	}
	
	Tool::desconectaBD($db);
	return $res;
}
*/

public static function archivaTicket($id){
	$db=Tool::conectaBD();
	$archivado=false;
	
	if(!$db){
		//error
		//echo "Error conectando <br/>";
	}
	else{
		if(!Ticket::estaArchivado($id)){
			$aux=new Ticket();
			$aux->getTicket($id);
			$sql="INSERT INTO HistoricoTickets (IdCompra,Codigo,IdTipo,Entregado) VALUES " .
			"('" . $aux->IdCompra . "','" . $aux->codigo . "','" . $aux->IdTipo . "',0)" ;
			
			if($aux->codigo<>""){				
				if(Tool::ejecutaConsulta($sql, $db)){
					$archivado=true;
				}
				else{
					//error	
					echo "Error insertando " . $aux->codigo . "<br/>SQL: " . $sql . "</br>";
				}				
			}
			else{
				//error
				echo "Error obteniendo " . $id . "<br/>SQL: " . $sql . "</br>";
			}
		}
		else{
			$archivado=true;
		}
		
		if($archivado){
			Ticket::deleteTicket($id);
		}
	}
	Tool::desconectaBD($db);
	
	return $archivado;
}

public static function estaArchivado($id){
	$db=Tool::conectaBD();
	
	$sql="SELECT * FROM HistoricoTickets WHERE Codigo='" . $id . "'";
	$res=Tool::ejecutaConsulta($sql, $db);
	
	$aux=mysqli_affected_rows($db);
	
	Tool::desconectaBD($db);
	
	return ($aux>0);	
}

public static function listadoTickets($filtro="1"){
    $db=Tool::conectaBD();
    
    $sql="SELECT * FROM Tickets WHERE " . $filtro;

    $res=Tool::ejecutaConsulta($sql,$db);

    Tool::desconectaBD($db);

    return $res;
}

public static function listadoTicketsPDF(){
    $db=Tool::conectaBD();

    $sql="SELECT cli.Apellidos,cli.Nombre,cli.Email, c.Fecha,t.Codigo
    FROM Compras as c
    INNER JOIN Compradores AS cli ON cli.email=c.IdComprador
    INNER JOIN Tickets AS t ON c.Id=t.IdCompra
    ORDER BY cli.Nombre,cli.Apellidos,t.Codigo";

    $res=Tool::ejecutaConsulta($sql,$db);

    Tool::desconectaBD($db);

    return $res;
}

/**
 * Funci�n que importa los datos del ticket de la base de datos al objeto Ticket que la invoca.
 * @param unknown $id Id del ticket a importar.
 */
public function getTicket($id){
	$db=Tool::conectaBD();
	
	$err=false;
	
	if(!$db){
		$err=true;
		//echo "Error conectando a BD<br/>";
	}
	else{
		$sql="SELECT * FROM Tickets WHERE Codigo='" . $id . "'";
		$res=Tool::ejecutaConsulta($sql, $db);
		
		//echo "Obteniendo " . $id . " con SQL -> " . $sql . "<br/>";
		
		if(!$res){
			$err=true;
			echo "Error obteniendo a " . $id . " <br/>SQL: " . $sql . "<br/>";
		}
		else{			
			$aux=mysqli_fetch_assoc($res);
			
			$this->IdCompra=$aux['IdCompra'];
			$this->codigo=$aux['Codigo'];
			$this->IdTipo=$aux['IdTipo'];
			$this->nombre="";
			$this->apellidos="";
			$this->email="";
			
			mysqli_free_result($res);
		}
	}
	
	if($err){
		$this->IdCompra="";
		$this->codigo="";
		$this->IdTipo="";
		$this->nombre="";
		$this->apellidos="";
		$this->email="";
	}
	
	Tool::desconectaBD($db);
}

public static function deleteTicket($id){
	$db=Tool::conectaBD();
	
	if(!$db){
		//error
	}
	else{
		$sql="DELETE FROM Tickets WHERE Codigo='" . Tool::limpiaCadena($id) . "'";
		$res=Tool::ejecutaConsulta($sql, $db);
		
	}
	
	Tool::desconectaBD($db);
	
	return $res;
}
 //Fin de la clase Ticket
}

class PDF extends FPDF{

	function Header(){
		//$this->Image('logo.jpg',50,85,100);
	}
	
	function Code39($xpos, $ypos, $code, $baseline=0.5, $height=5){

    $wide = $baseline;
    $narrow = $baseline / 3 ; 
    $gap = $narrow;

    $barChar['0'] = 'nnnwwnwnn';
    $barChar['1'] = 'wnnwnnnnw';
    $barChar['2'] = 'nnwwnnnnw';
    $barChar['3'] = 'wnwwnnnnn';
    $barChar['4'] = 'nnnwwnnnw';
    $barChar['5'] = 'wnnwwnnnn';
    $barChar['6'] = 'nnwwwnnnn';
    $barChar['7'] = 'nnnwnnwnw';
    $barChar['8'] = 'wnnwnnwnn';
    $barChar['9'] = 'nnwwnnwnn';
    $barChar['A'] = 'wnnnnwnnw';
    $barChar['B'] = 'nnwnnwnnw';
    $barChar['C'] = 'wnwnnwnnn';
    $barChar['D'] = 'nnnnwwnnw';
    $barChar['E'] = 'wnnnwwnnn';
    $barChar['F'] = 'nnwnwwnnn';
    $barChar['G'] = 'nnnnnwwnw';
    $barChar['H'] = 'wnnnnwwnn';
    $barChar['I'] = 'nnwnnwwnn';
    $barChar['J'] = 'nnnnwwwnn';
    $barChar['K'] = 'wnnnnnnww';
    $barChar['L'] = 'nnwnnnnww';
    $barChar['M'] = 'wnwnnnnwn';
    $barChar['N'] = 'nnnnwnnww';
    $barChar['O'] = 'wnnnwnnwn'; 
    $barChar['P'] = 'nnwnwnnwn';
    $barChar['Q'] = 'nnnnnnwww';
    $barChar['R'] = 'wnnnnnwwn';
    $barChar['S'] = 'nnwnnnwwn';
    $barChar['T'] = 'nnnnwnwwn';
    $barChar['U'] = 'wwnnnnnnw';
    $barChar['V'] = 'nwwnnnnnw';
    $barChar['W'] = 'wwwnnnnnn';
    $barChar['X'] = 'nwnnwnnnw';
    $barChar['Y'] = 'wwnnwnnnn';
    $barChar['Z'] = 'nwwnwnnnn';
    $barChar['-'] = 'nwnnnnwnw';
    $barChar['.'] = 'wwnnnnwnn';
    $barChar[' '] = 'nwwnnnwnn';
    $barChar['*'] = 'nwnnwnwnn';
    $barChar['$'] = 'nwnwnwnnn';
    $barChar['/'] = 'nwnwnnnwn';
    $barChar['+'] = 'nwnnnwnwn';
    $barChar['%'] = 'nnnwnwnwn';

    $this->SetFont('Arial','',10);
    //$this->Text($xpos, $ypos + $height + 4, $code);
    $this->SetFillColor(0);

    $code = '*'.strtoupper($code).'*';
    for($i=0; $i<strlen($code); $i++){
        $char = $code[$i];
        if(!isset($barChar[$char])){
            $this->Error('Invalid character in barcode: '.$char);
        }
        $seq = $barChar[$char];
        for($bar=0; $bar<9; $bar++){
            if($seq[$bar] == 'n'){
                $lineWidth = $narrow;
            }else{
                $lineWidth = $wide;
            }
            if($bar % 2 == 0){
                $this->Rect($xpos, $ypos, $lineWidth, $height, 'F');
            }
            $xpos += $lineWidth;
        }
        $xpos += $gap;
    }
}
}

?>