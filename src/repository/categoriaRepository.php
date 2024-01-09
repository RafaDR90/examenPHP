<?php
namespace repository;
use lib\BaseDeDatos,
    PDO,
    PDOException;
class categoriaRepository{
    private $db;
    public function __construct()
    {
        $this->db=new BaseDeDatos();
    }

    public function getAll():array|string
    {
        try{
            $sel=$this->db->prepara("SELECT * FROM categorias ORDER BY id DESC");
            $sel->execute();
            $categorias=$sel->fetchAll(PDO::FETCH_ASSOC);
        }catch (\PDOException $e){
            $categorias=$e->getMessage();
        } finally {
            $sel->closeCursor();
            $this->db->cierraConexion();
            return $categorias;
        }
    }
    /**
     * Elimina una categoría y todos los productos asociados a ella.
     *
     * @param int $id El ID de la categoría a eliminar.
     * @return string|null Devuelve null si la operación es exitosa, o un mensaje de error en caso de excepción.
     */
    public function borrarCategoriaPorId(int $id):?string{
        try {
            //elimina antes los productos de dicha categoria
            $this->db->beginTransaction();
            $deleteProductos = $this->db->prepara("DELETE FROM productos WHERE categoria_id = :id");
            $deleteProductos->bindValue(':id', $id);
            $deleteProductos->execute();
            //elimina la categoria
            $deleteCategoria = $this->db->prepara("DELETE FROM categorias WHERE id = :id");
            $deleteCategoria->bindValue(':id', $id);
            $deleteCategoria->execute();
            $this->db->commit();
            $resultado = null;
        }catch (PDOException $e){
            $this->db->rollBack();
            $resultado=$e->getMessage();
        } finally {
            if (isset($deleteProductos)) {
                $deleteProductos->closeCursor();
            }
            if (isset($deleteCategoria)) {
                $deleteCategoria->closeCursor();
            }
            $this->db->cierraConexion();
            return $resultado;
        }

    }

    /**
     * Obtiene una categoría por su ID.
     * @param int $id El ID de la categoría a obtener.
     * @return array|string Devuelve un array con los datos de la categoría, o un string con el mensaje de error.
     */
    public function obtenerCategoriaPorID(int $id):array | string{
        try{
            $sel=$this->db->prepara("SELECT * FROM categorias WHERE id=:id");
            $sel->bindValue(':id',$id);
            $sel->execute();
            $categoria=$sel->fetch(PDO::FETCH_ASSOC);
        }catch (\PDOException $e){
            $categoria=$e->getMessage();
        } finally {
            $sel->closeCursor();
            $this->db->cierraConexion();
            return $categoria;
        }
    }

    public function update($categoria):?string{
        try{
            $update=$this->db->prepara("UPDATE categorias SET nombre=:nombre WHERE id=:id");
            $update->bindValue(':nombre',$categoria->getNombre());
            $update->bindValue(':id',$categoria->getId());
            $update->execute();
            $error=null;
        }catch (PDOException $e){
            $error=$e->getMessage();
        } finally {
            $update->closeCursor();
            $this->db->cierraConexion();
            return $error;
        }
    }

    public function create($categoria):?string{
        try{
            $insert=$this->db->prepara("INSERT INTO categorias (nombre) VALUES (:nombre)");
            $insert->bindValue(':nombre',$categoria->getNombre());
            $insert->execute();
            $error=null;
        }catch (PDOException $e){
            $error=$e->getMessage();
        } finally {
            $insert->closeCursor();
            $this->db->cierraConexion();
            return $error;
        }
    }
}
