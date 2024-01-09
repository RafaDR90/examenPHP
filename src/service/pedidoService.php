<?php
namespace service;
use repository\pedidoRepository;
class pedidoService{
    private $pedidoRepository;
    public function __construct()
    {
        $this->pedidoRepository=new pedidoRepository();
    }
    public function create($pedido){
        return $this->pedidoRepository->create($datos);
    }
}