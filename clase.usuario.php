<?php

include_once "./clase.tool.php";

class Usuario {

    public $id;
    public $username;
    public $pass;

    public static function getUsuario($id){
        $u=new Usuario();
        $db=Tool::conectaBD();
        $sql="SELECT * FROM Usuarios WHERE Id='" . $id . "'";

        $res=Tool::consulta($sql,$db);

        if(count($res)<1){
            $u=null;
            throw new Exception("Usuario no encontrado");
        }
        else{
            $u->id=$res[0]['Id'];
            $u->username=$res[0]['Nombre'];
            $u->pass=$res[0]['Pass'];
        }

        Tool::desconectaBD($db);
        return $u;
    }

    public static function loginValido($name,$pass){
        $db=Tool::conectaBD();

        $sql="SELECT * FROM Usuarios WHERE Nombre='" . $name ."'";

        $res=Tool::consulta($sql,$db);

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

        Tool::desconectaBD($db);
        return $aux;

    }
} 