<?php
namespace models;
class lineas_pedidos{
    private ?int $id;
    private ?int $pedido_id;
    private ?int $producto_id;
    private ?int $unidades;
    public function __construct(?int $id=null,int $pedido_id=null,int $producto_id=null,int $unidades=0)
    {
        $this->id=$id;
        $this->pedido_id=$pedido_id;
        $this->producto_id=$producto_id;
        $this->unidades=$unidades;
    }
    public static function fromArray(array $data):array
    {
        $lineas_pedidos=[];
        foreach ($data as $dt) {
            $lineas_pedido = new lineas_pedidos(
                $dt['id'] ?? null,
                $dt['pedido_id'] ?? null,
                $dt['producto_id'] ?? null,
                $dt['unidades'] ?? null,
            );
            $lineas_pedidos[]=$lineas_pedido;
        }
        return $lineas_pedidos;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getPedidoId(): ?int
    {
        return $this->pedido_id;
    }

    public function setPedidoId(?int $pedido_id): void
    {
        $this->pedido_id = $pedido_id;
    }

    public function getProductoId(): ?int
    {
        return $this->producto_id;
    }

    public function setProductoId(?int $producto_id): void
    {
        $this->producto_id = $producto_id;
    }

    public function getUnidades(): ?int
    {
        return $this->unidades;
    }

    public function setUnidades(?int $unidades): void
    {
        $this->unidades = $unidades;
    }


}