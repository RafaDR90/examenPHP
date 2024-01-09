<?php
namespace models;
class pedido{
    private ?int $id;
    private ?int $usuario_id;
    private float $coste;
    private string $estado;
    private string $fecha;
    private string $hora;
    public function __construct(?int $id=null,int $usuario_id=null,float $coste=0,string $estado='',string $fecha='',string $hora='')
    {
        $this->id=$id;
        $this->usuario_id=$usuario_id;
        $this->coste=$coste;
        $this->estado=$estado;
        $this->fecha=$fecha;
        $this->hora=$hora;
    }
    public static function fromArray(array $data):array
    {
        $pedidos=[];
        foreach ($data as $dt) {
            $pedido = new pedido(
                $dt['id'] ?? null,
                $dt['usuario_id'] ?? null,
                $dt['coste'] ?? 0,
                $dt['estado'] ?? '',
                $dt['fecha'] ?? '',
                $dt['hora'] ?? '',
            );
            $pedidos[]=$pedido;
        }
        return $pedidos;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUsuarioId(): ?int
    {
        return $this->usuario_id;
    }

    public function setUsuarioId(?int $usuario_id): void
    {
        $this->usuario_id = $usuario_id;
    }

    public function getCoste(): float
    {
        return $this->coste;
    }

    public function setCoste(float $coste): void
    {
        $this->coste = $coste;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    public function getFecha(): string
    {
        return $this->fecha;
    }

    public function setFecha(string $fecha): void
    {
        $this->fecha = $fecha;
    }

    public function getHora(): string
    {
        return $this->hora;
    }

    public function setHora(string $hora): void
    {
        $this->hora = $hora;
    }


}