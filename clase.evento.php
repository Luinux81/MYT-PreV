<?php

/** 
 * @author Luis
 * 
 */
class Evento
{
    private $id="";
    public $nombre="";
    public $descripcion="";
    public $fechaInicio="";
    public $fechaFin="";
    public $aforo="";
    public $local="";
    public $direccion="";
    public $ciudad="";
    public $pais="";
    public $gps="";
    public $estadoEvento="";
    
    
    /**
     * Constructor de evento vacío. 
     */
    public function __construct(){
        
    }
    
    public function getID(){
        return $this->id;
    }
    
    public function setID($id){
        $this->id=$id;    
    }
    
    /**
     * Esta función guarda la información pasada como parámetros en los atributos del objeto. NO se puede modificar el atributo $id.
     * @param string $nombre 
     * @param string $descripcion 
     * @param string $fechaInicio Fecha y hora de inicio del evento. Formato en la base de datos-> "1900-1-31 00:00:00"
     * @param string $fechaFin Fecha y hora de fin del evento. Formato en la base de datos-> "1900-1-31 00:00:00"
     * @param integer $aforo 
     * @param string $local
     * @param string $direccion
     * @param string $ciudad
     * @param string $pais
     * @param string $gps
     * @param string $estadoEvento
     */
    public function crearEvento($nombre,$descripcion,$fechaInicio,$fechaFin,$aforo,$local,$direccion,$ciudad,$pais,$gps,$estadoEvento="inactivo"){
        $this->nombre=$nombre;
        $this->descripcion=$descripcion;
        $this->fechaInicio=$fechaInicio;
        $this->aforo=$aforo;
        $this->local=$local;
        $this->direccion=$direccion;
        $this->ciudad=$ciudad;
        $this->pais=$pais;
        $this->gps=$gps;
        if($estadoEvento=="inactivo" || $estadoEvento=="activo" || $estadoEvento=="cancelado"){
            $this->estadoEvento=$estadoEvento;
        }
        else{
            $this->estadoEvento="inactivo";
        }
    }
    
    /**
     * Está funcion carga la información de la base de datos con id igual al parámetro de entrada en el objeto actual.
     * @param integer $id_evento 
     */
    public function dbGetEvento($id_evento){
        $sql="SELECT * FROM Eventos WHERE Id=" . $id_evento;
    }
    
    /**
     * Esta función guarda los datos del objeto $evento en la base de datos.
     * @param Evento $evento
     */
    public function dbSetEvento($evento){
        $sql="INSERT INTO EVENTOS (nombre,descripcion,fechaInicio,fechaFin,aforo,local,direccion,ciudad,pais,gps,estadoEvento) VALUES " 
            . "("
            . "'" . $evento->nombre . "',"
            . "'" . $evento->descripcion . "',"
            . "'" . $evento->fechaInicio . "',"
            . "'" . $evento->fechaFin . "',"
            . "" . $evento->aforo . ","
            . "'" . $evento->local . "',"
            . "'" . $evento->direccion . "',"
            . "'" . $evento->ciudad . "',"
            . "'" . $evento->pais . "',"
            . "'" . $evento->gps . "',"
            . "'" . $evento->estadoEvento . "'"                               
            . ")";
    }
    
    /**
     * Esta función comprueba que los atributos del objeto sean distinto de null.
     */
    private function listoParaBD(){
        
    }
}

