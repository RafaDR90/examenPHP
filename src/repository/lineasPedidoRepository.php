<?php
namespace repository;
use lib\BaseDeDatos,
    PDO,
    PDOException;
class lineasPedidoRepository{
    private $db;
    public function __construct()
    {
        $this->db=new BaseDeDatos();
    }
}