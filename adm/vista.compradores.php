<?php
include_once __DIR__ . "/../config.php";
include_once SITE_ROOT . "/clase.comprador.php";
include_once SITE_ROOT . "/clase.tool.php";

$err=$_GET['err'];
$res=$_GET['res'];

session_start();

if(isset($_SESSION["username"])){
    //cabecera y menu izquierdo
    echo Tool::inicioDocWeb();

    echo "<div>";


    echo "<h1>Compradores</h1>
          <form action='./filtro.php' name='filtro_compradores' method='post'>
            <select name='NombreParametro'>
                <option>Email</option>
                <option>Nombre</option>
            </select>
            <input type='text' name='Parametro'>
            <input type='submit' name='Filtrar'>
            <input type='hidden' name='origen' value='compradores'>
          </form>
    ";

    if($_SESSION["Filtro"]=="" || $_SESSION["Filtro"]=="1"){
    	echo "<h3>Listado completo</h3>";
    	$_SESSION["Filtro"]="1";
    }else{
    	echo "<h3>Listado filtrado</h3>";
    }
    
    $cli=new Comprador();
    $lista=$cli->listarCompradores($_SESSION["Filtro"]);
    $_SESSION["Filtro"]="1";

    if ($err!=""){
        echo "<div style='width: 100%;background-color: #AA0000;'> " . $err . "</div>";
    }
    if ($res!=""){
        echo "<div style='width: 100%;background-color: #00AA00;'> " . $res . "</div>";
    }

    echo "<table style='width:100%;'><tr>
    <td><a href='vista.compradoresDetalle.php'>Nuevo</a> </td>
    <td></td>
    <td></td>
    </tr></table>";

    $aux="";
    $col1="#999999";
    $col2="#777777";
    $aux2=$col1;
    $i=0;

    echo "<table><tr><th>#</th><th>Nombre</th><th>Apellidos</th><th>Email</th><th></th></tr>";
    foreach($lista as $l){
        $i=$i+1;
        $aux= $aux . "<tr style='background-color:" . $aux2 . ";'>
        		<td>" . $i . "</td>
        		<td>" . $l['Nombre']  . "</td>
        		<td>" . $l['Apellidos']  . "</td>
        		<td><a href='./filtro.php?origen=compras&Parametro=" . $l['Email']  . "&NombreParametro=Email'>" . $l['Email']  . "</a></td>
        		<td>
        			<a href='./vista.compradoresDetalle.php?action=editar&id=" . $l['IdComprador'] . "'>Editar</a>
        			<a href='./controlador.compradores.php?action=borrar&id=" . $l['IdComprador'] . "'>Borrar</a>
        		</td></tr>";

        if($aux2==$col1){
            $aux2=$col2;
        }
        else{
            $aux2=$col1;
        }
    }
    echo $aux;

    echo "</table>
    </div>";

}
else{
    header("Location:login.php");
}



?>

