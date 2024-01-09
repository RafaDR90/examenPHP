<?php
namespace repository;
use lib\BaseDeDatos,
    PDO,
    PDOException;
class usuarioRepository{
    public function __construct()
    {
        $this->db=new BaseDeDatos();
    }
    public function createUser($usuario){
        $id=null;
        $nombre=$usuario->getNombre();
        $apellidos=$usuario->getApellidos();
        $email=$usuario->getEmail();
        $password=$usuario->getPassword();
        $rol='user';
        $this->db=new BaseDeDatos();
        try{
            $ins=$this->db->prepara("INSERT INTO usuarios (id,nombre,apellidos,email,password,rol) values (:id,:nombre,:apellidos,:email,:password,:rol)");
            $ins->bindValue(':id',$id);
            $ins->bindValue(':nombre',$nombre);
            $ins->bindValue(':apellidos',$apellidos);
            $ins->bindValue(':email',$email);
            $ins->bindValue(':password',$password);
            $ins->bindValue(':rol',$rol);
            $ins->execute();
            $result=true;
        }catch (PDOException $err){
            $result=false;
        } finally {
            $ins->closeCursor();
            $this->db->cierraConexion();
        }
        return $result;
    }

    public function compruebaCorreo($email){
        try{
            $sel=$this->db->prepara("SELECT email FROM usuarios WHERE email=:email");
            $sel->bindValue(':email',$email);
            $sel->execute();
            if ($sel->rowCount()>0) {
                return true;
            }else{
                return false;
            }
        }catch (PDOException $err){
            return $err->getMessage();
        } finally {
            $sel->closeCursor();
            $this->db->cierraConexion();
        }
    }
    public function getUsuarioFromEmail($email){
        $this->db=new BaseDeDatos();
        try{
            $sel=$this->db->prepara("SELECT * FROM usuarios WHERE email=:email");
            $sel->bindValue(':email',$email);
            $sel->execute();
            if ($sel->rowCount()>0) {
                $usuario=$sel->fetch(PDO::FETCH_ASSOC);
                return $usuario;
            }else{
                return false;
            }
        }catch (PDOException $err){
            return $err->getMessage();
        } finally {
            $sel->closeCursor();
            $this->db->cierraConexion();
        }
    }
    public function cierraConexion(){
        $this->db->cierraConexion();
    }
    public function getUsuariosPorRol($rol){
        try{
            $sel=$this->db->prepara("SELECT * FROM usuarios WHERE rol=:rol");
            $sel->bindValue(':rol',$rol);
            $sel->execute();
            if ($sel->rowCount()>0) {
                $usuarios=$sel->fetchAll(PDO::FETCH_ASSOC);
                return $usuarios;
            }else{
                return "No se han encontrado usuarios con ese rol";
            }
        }catch (PDOException $err){
            return $err->getMessage();
        } finally {
            $sel->closeCursor();
            $this->db->cierraConexion();
        }
    }
    public function getUsuarios(){
        try{
            $sel=$this->db->prepara("SELECT * FROM usuarios order by nombre asc");
            $sel->execute();
            if ($sel->rowCount()>0) {
                $usuarios=$sel->fetchAll(PDO::FETCH_ASSOC);
                return $usuarios;
            }else{
                return "No se han encontrado usuarios";
            }
        }catch (PDOException $err){
            return $err->getMessage();
        } finally {
            $sel->closeCursor();
            $this->db->cierraConexion();
        }
    }
    public function getUsuarioPorId($id){
        try{
            $sel=$this->db->prepara("SELECT * FROM usuarios WHERE id=:id");
            $sel->bindValue(':id',$id);
            $sel->execute();
            if ($sel->rowCount()>0) {
                $usuario=$sel->fetch(PDO::FETCH_ASSOC);
                return $usuario;
            }else{
                return "No se ha encontrado el usuario";
            }
        }catch (PDOException $err){
            return $err->getMessage();
        } finally {
            $sel->closeCursor();
            $this->db->cierraConexion();
        }
    }
    public function updateRol($id,$rol){
            $this->db=new BaseDeDatos();
            try{
                $upd = $this->db->prepara("UPDATE usuarios SET rol=:rol WHERE id=:id");
                $upd->bindValue(':id',(int)$id);
                $upd->bindValue(':rol',$rol);
                $upd->execute();
                if ($upd->rowCount()>0) {
                    return true;
                }else{
                    return "No se ha podido actualizar el rol";
                }
            }catch (PDOException $err){
                return $err->getMessage();
            } finally {
                $upd->closeCursor();
                $this->db->cierraConexion();
            }


    }
}
