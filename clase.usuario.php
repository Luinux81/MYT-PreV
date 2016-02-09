<?php

include_once "./clase.tool.php";

class Usuario {

    public $id;
    public $username;
    public $pass;

    public static function getUsuario($id){
        $t=new Tool();
        $u=new Usuario();
        $db=$t->conectaBD();
        $sql="SELECT * FROM Usuarios WHERE Id='" . $id . "'";

        $res=$t->consulta($sql,$db);

        if(count($res)<1){
            $u=null;
            throw new Exception("Usuario no encontrado");
        }
        else{
            $u->id=$res[0]['Id'];
            $u->username=$res[0]['Nombre'];
            $u->pass=$res[0]['Pass'];
        }

        $t->desconectaBD($db);
        return $u;
    }

    public static function loginValido($name,$pass){
        $t=new Tool();
        $db=$t->conectaBD();

        $sql="SELECT * FROM Usuarios WHERE Nombre='" . $name ."'";

        $res=$t->consulta($sql,$db);

        if(is_null($res)){
            $aux=false;
        }
        else{
            if(count($res)>0){
                $storedpass=$res[0]['Pass'];
                $aux=($pass==$storedpass);
            }
            else{
                $aux=false;
            }
        }

        $t->desconectaBD($db);
        return $aux;

    }
} 