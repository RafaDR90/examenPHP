<?php
namespace service;
use repository\usuarioRepository;
class usuarioService{
    private usuarioRepository $usuarioRepository;
    public function __construct()
    {
        $this->usuarioRepository=new usuarioRepository();
    }
    public function createUser($usuario){
        return $this->usuarioRepository->createUser($usuario);
    }
    public function compruebaCorreo($email){
        return $this->usuarioRepository->compruebaCorreo($email);
    }
    public function getUsuarioFromEmail($email){
        return $this->usuarioRepository->getUsuarioFromEmail($email);
    }
    public function cierraConexion(){
        $this->usuarioRepository->cierraConexion();
    }
    public function getUsuariosPorRol($rol){
        return $this->usuarioRepository->getUsuariosPorRol($rol);
    }
    public function getUsuarios(){
        return $this->usuarioRepository->getUsuarios();
    }
    public function getUsuarioPorId($id){
        return $this->usuarioRepository->getUsuarioPorId($id);
    }
    public function updateRol($id,$rol){
        return $this->usuarioRepository->updateRol($id,$rol);
    }
}
