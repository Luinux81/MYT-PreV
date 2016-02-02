<?php
include_once "./clase.tool.php";

class TipoTicket{

    public $id;
    public $descripcion;
    public $precio;

    public function addTipo(){
        $t=new Tool();
        $db=$t->conectaBD();

       if($db){
           if(!TipoTicket::existeTipo($this->id)){
               $sql="INSERT INTO TipoTicket (Id,Descripcion,Precio) VALUES ('". $this->id . "','" . $this->descripcion . "','" . $this->precio . "')";
               $res=$t->ejecuta($sql,$db);
           }
           else{
               //El id ya existe
               $res=false;
           }
       }
       else{
           //error conectando a base de datos
           $res=false;
       }
        $t->desconectaBD($db);

        return $res;
    }

    public function modTipo(){
        $t=new Tool();
        $db=$t->conectaBD();

        if($db){
            if(TipoTicket::existeTipo($this->id)){
                $sql="UPDATE TipoTicket SET Id=" . $this->id . ",Descripcion=" . $this->descripcion . ",Precio=" . $this->precio . " WHERE Id=" . $this->id;
                $res=$t->ejecuta($sql,$db);
            }
            else{
                //El id no existe
                $res=false;
            }
        }
        else{
            //error conectando a base de datos
            $res=false;
        }
        $t->desconectaBD($db);

        return $res;

    }

    public function delTipo(){
        $t=new Tool();
        $db=$t->conectaBD();

        if($db){
            if(TipoTicket::existeTipo($this->id)){
                $sql="DELETE FROM TipoTicket WHERE Id=" . $this->id;
                $res=$t->ejecuta($sql,$db);
            }
            else{
                //El id no existe
                $res=false;
            }
        }
        else{
            //error conectando a base de datos
            $res=false;
        }
        $t->desconectaBD($db);

        return $res;
    }

    public function getTipo($id){
        $t=new Tool();
        $db=$t->conectaBD();

        if($db){
            $sql="SELECT * FROM TipoTicket WHERE Id=" . $id ;
            $res=$t->consulta($sql,$db);

            $aux=mysql_affected_rows($db);
            if($aux>0){
                $this->id=$res[0]['Id'];
                $this->descripcion=$res[0]['Descripcion'];
                $this->precio=$res[0]['Precio'];
            }
            else{
                $this->id=-1;
                $this->descripcion="";
                $this->precio=0;
            }
        }
        else{
            //error conectando a base de datos
        }
        $t->desconectaBD($db);
    }

    public static function existeTipo($id){
        $t=new Tool();
        $db=$t->conectaBD();

        if($db){
            $sql="SELECT * FROM TipoTicket WHERE Id=" . $id ;
            $res=$t->consulta($sql,$db);

            $aux=mysql_affected_rows($db);
            return $aux>0;
        }
        else{
            //error conectando a base de datos
            return false;
        }
        $t->desconectaBD($db);
    }
}
?>