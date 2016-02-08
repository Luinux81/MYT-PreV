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

    public static function loginValido($username,$pass){
        $t=new Tool();
        $db=$t->conectaBD();

        $sql="SELECT * FROM Usuarios WHERE Nombre='" . $username ."'";

        $res=$t->consulta($sql,$db);

        $storedpass=$res[0]['Pass'];

        $t->desconectaBD($db);

        if(count($res)<1){
            return false;
        }
        else{
            return $pass==$storedpass;
        }
    }


} 