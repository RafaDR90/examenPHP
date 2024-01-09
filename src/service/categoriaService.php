<?php
namespace service;
use repository\categoriaRepository;

class categoriaService{
    private $categoriaRepository;
    public function __construct()
    {
        $this->categoriaRepository=new categoriaRepository();
    }

    public function getAll(){
        return $this->categoriaRepository->getAll();
    }
    public function borrarCategoriaPorId(int $id){
        $this->categoriaRepository->borrarCategoriaPorId($id);
    }
    public function obtenerCategoriaPorID(int $id){
        return $this->categoriaRepository->obtenerCategoriaPorID($id);
    }
    public function update($categoria){
        return $this->categoriaRepository->update($categoria);
    }
    public function create($categoria){
        return $this->categoriaRepository->create($categoria);
    }
}