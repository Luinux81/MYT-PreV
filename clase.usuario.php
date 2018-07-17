<?php

include_once SITE_ROOT . "/clase.tool.php";

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

        $res=Tool::ejecutaConsulta($sql,$db);

        $aux=false;
        
        if ($res->num_rows>0){
        	$fila=$res->fetch_assoc();
        	$aux=($fila['Pass']==$pass);
        }

        $res->free();
        
        Tool::desconectaBD($db);
        
        return $aux;

    }
} 