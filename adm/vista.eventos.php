<?php
include_once __DIR__ . "/../config.php";
include_once SITE_ROOT . "/clase.evento.php";
include_once SITE_ROOT . "/clase.tool.php";

$err=$_GET['err'];
$res=$_GET['res'];

session_start();

if(isset($_SESSION["username"])){
    //cabecera y menu izquierdo
    echo Tool::inicioDocWeb();

    echo "<div>";

    echo "<h1>Eventos</h1>
          <form action='./filtro.php' name='filtro_eventos' method='post'>
            <select name='NombreParametro'>
                <option>Id</option>
                <option>Nombre</option>
            </select>
            <input type='text' name='Parametro'>
            <input type='submit' name='Filtrar'>
            <input type='hidden' name='origen' value='eventos'>
          </form>
    ";


    if($_SESSION["Filtro"]=="" || $_SESSION["Filtro"]=="1"){
        echo "<h3>Listado completo</h3>";
        $_SESSION["Filtro"]="1";
    }else{
        echo "<h3>Listado filtrado</h3>";        
    }
    
    try {
        $lista=Evento::listarEventos($_SESSION["Filtro"]);
        $_SESSION["Filtro"]="1";
    } 
    catch (Exception $e) {
        echo "<h4>ERROR:" . $e->getMessage() . "</h4>";
    }    
    
    
    if ($err!=""){
        echo "<div style='width: 100%;background-color: #AA0000;'> " . $err . "</div>";
    }
    if ($res!=""){
        echo "<div style='width: 100%;background-color: #00AA00;'> " . $res . "</div>";
    }


    echo "<table style='width:100%;'><tr>
    <td><a href='vista.eventosDetalle.php?accion=nuevo'>Nuevo</a> </td>
    <td></td>
    <td></td>
    </tr></table>";

    $aux="";
    $col1="#999999";
    $col2="#777777";
    $aux2=$col1;
    $i=0;

    
    echo "<table><tr><th>Fecha</th><th>Nombre</th><th>Estado</th><th>Editar Estado</th><th></th></tr>";
    
    $l=new Evento();
    
    foreach ($lista as $l){
        $aux=$aux . "<tr style='background-color:" . $aux2 . ";'>
    			<td>" . $l->FechaInicio . "</td>" . "
    			<td>" . $l->Nombre . "</td>" . "
    			<td>" . $l->Estado . "</td>" . "
    			<td>" . "<select>
    						<option>Activo</option>
    						<option>Inactivo</option>
    						<option>Agotadas</option>
    						<option>Cancelado</option>
    					</select></td>" . "
    			<td>
    				<a href='./vista.eventosDetalle.php?accion=editar&id=" . $l->IdEvento . "'>Editar</a>
    				<a href='./controlador.eventos.php?action=borrar&id=" . $l->IdEvento . "'>Borrar</a>
    				<a href='./controlador.eventos.php?action=duplicar&id=" . $l->IdEvento . "'>Duplicar</a>
    			</td>
    			</tr>";
    	if($aux2==$col1){
    		$aux2=$col2;
    	}
    	else{
    		$aux2=$col1;
    	}
    }
    
    echo $aux . "</table>
    </div>";
    
    

}
else{
    header("Location:login.php");
}



?>

