<?php
include_once "../fpdf.php";
include_once "../clase.ticket.php";
include_once "../clase.compra.php";
include_once "../clase.comprador.php";
include_once "../clase.tool.php";

session_start();

if(isset($_SESSION["username"])){

    $codigo=$_GET['code'];

    $ticket=new Ticket();

    $db=Tool::conectaBD();

    $sql="SELECT * FROM Compras as C INNER JOIN Compradores as Co ON C.IdComprador=Co.Email WHERE Id='" . $codigo . "'";
    $res=Tool::consulta($sql,$db);

    $nombre=$res[0]['Nombre'] . " " . $res[0]['Apellidos'];
    $cantidad=$res[0]['Cantidad'];

    $ticket->nombre=$nombre;
    $ticket->codigo=$res[0]['Id'];
    $ticket->email=$res[0]['Email'];

    $ticket->creaPDF(false,$cantidad);


    Tool::desconectaBD($db);
}
else{
    //ERROR: Al usar el include_once "../clase.tool.php" provoca llamada a config.php que escribe algo y da error al escribir las cabeceras. Queda atascado aqui.
    header("Location:login.php");
}
?>