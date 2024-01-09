<?php
namespace models;


class categoria{
    private ?int $id;
    private string $nombre;



    public function __construct(?int $id=null, string $nombre='')
    {
        $this->id=$id;
        $this->nombre=$nombre;

    }

    public static function fromArray(array $data):array
    {
        $categorias=[];
        foreach ($data as $dt) {
            $categoria = new categoria(
                $dt['id'] ?? null,
                $dt['nombre'] ?? '',
            );
            $categorias[]=$categoria;
        }
        return $categorias;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getDb(): BaseDeDatos
    {
        return $this->db;
    }

    public function setDb(BaseDeDatos $db): void
    {
        $this->db = $db;
    }









}