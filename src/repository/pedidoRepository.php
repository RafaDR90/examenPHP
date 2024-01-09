<?php
namespace repository;
use lib\BaseDeDatos,
    PDO,
    PDOException;
class pedidoRepository{
    private $db;
    public function __construct()
    {
        $this->db=new BaseDeDatos();
    }

    private function create($datos){
        $error = null;

        $fechaHora = explode(" ", $datos['fecha']);
        $fecha = $fechaHora[0];
        $hora = $fechaHora[1];

        try {
            $ins = $this->db->prepara("INSERT INTO pedidos (usuario_id, coste, estado, fecha, hora) VALUES (:usuario_id, :coste, :estado, :fecha, :hora)");
            $ins->bindValue(':usuario_id', $datos['usuario_id']);
            $ins->bindValue(':coste', $datos['coste']);
            $ins->bindValue(':estado', $datos['estado']);
            $ins->bindValue(':fecha', $fecha);
            $ins->bindValue(':hora', $hora);
            $ins->execute();
        } catch (PDOException $e) {
            $error = $e->getMessage();
        } finally {
            $ins->closeCursor();
            $this->db->cierraConexion();
            return $error;
        }
    }
}