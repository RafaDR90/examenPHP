<?php
namespace repository;
use lib\BaseDeDatos,
    PDO,
    PDOException;
class productoRepository{
    private $db;
    public function __construct()
    {
        $this->db=new BaseDeDatos();
    }

    /**
     * Obtiene un producto por ID de categoria.
     * @param int $id
     * @return array|false|string
     */
    public function productosPorCategoria($id){
        try{
            $sel=$this->db->prepara("SELECT * FROM productos WHERE categoria_id=:id");
            $sel->bindParam(':id',$id,PDO::PARAM_INT);
            $sel->execute();
            $resultado=$sel->fetchAll(PDO::FETCH_ASSOC);

        }catch (\PDOException $e){
            $resultado=$e->getMessage();
        } finally {
            $sel->closeCursor();
            $this->db->cierraConexion();
            return $resultado;
        }
    }

    public function addProducto($producto){
        $error=null;
        try{
            $insert=$this->db->prepara("INSERT INTO productos VALUES (null,:categoria_id,:nombre,:descripcion,:precio,:stock,:oferta,:fecha,:imagen)");
            $insert->bindValue(':categoria_id',$producto->getCategoriaId());
            $insert->bindValue(':nombre',$producto->getNombre());
            $insert->bindValue(':descripcion',$producto->getDescripcion());
            $insert->bindValue(':precio',$producto->getPrecio());
            $insert->bindValue(':stock',$producto->getStock());
            $insert->bindValue(':oferta',$producto->getOferta());
            $insert->bindValue(':fecha',$producto->getFecha());
            $insert->bindValue(':imagen',$producto->getImagen());
            $insert->execute();
        }catch (PDOException $e){
            $error=$e->getMessage();
        } finally {
            $insert->closeCursor();
            $this->db->cierraConexion();
            return $error;
        }
    }

    public function eliminarProducto($id){
        $error=null;
        try{
            /* Vuelvo a crear db porque antes de eliminar uso otra funcion que la cierra y no interesa modificar
               el cierre de la otra funcion */
            $this->db=new BaseDeDatos();
            $delete=$this->db->prepara("DELETE FROM productos WHERE id=:id");
            $delete->bindValue(':id',$id);
            $delete->execute();
        }catch (PDOException $e){
            $error=$e->getMessage();
        } finally {
            $delete->closeCursor();
            $this->db->cierraConexion();
            return $error;
        }
    }

    public function obtenerNombreImagen($id){
        try{
            $sel=$this->db->prepara("SELECT imagen FROM productos WHERE id=:id");
            $sel->bindValue(':id',$id);
            $sel->execute();
            $resultado=$sel->fetch(PDO::FETCH_ASSOC);
        }catch (\PDOException $e){
            $resultado=$e->getMessage();
        } finally {
            $sel->closeCursor();
            $this->db->cierraConexion();
            return $resultado;
        }
    }
    public function getProductoByIdProducto($id){
        try{
            $sel=$this->db->prepara("SELECT * FROM productos WHERE id=:id");
            $sel->bindValue(':id',$id);
            $sel->execute();
            $resultado=$sel->fetch(PDO::FETCH_ASSOC);
        }catch (\PDOException $e){
            $resultado=$e->getMessage();
        } finally {
            $sel->closeCursor();
            $this->db->cierraConexion();
            return $resultado;
        }
    }

    public function editarProducto($id,$producto){
        $error=null;
        try{
            $this->db=new BaseDeDatos();
            $update=$this->db->prepara("UPDATE productos SET nombre=:nombre,descripcion=:descripcion,precio=:precio,stock=:stock,oferta=:oferta,fecha=:fecha,imagen=:imagen WHERE id=:id");
            $update->bindValue(':id',$id);
            $update->bindValue(':nombre',$producto['nombre']);
            $update->bindValue(':descripcion',$producto['descripcion']);
            $update->bindValue(':precio',$producto['precio']);
            $update->bindValue(':stock',$producto['stock']);
            $update->bindValue(':oferta',$producto['oferta']);
            $update->bindValue(':fecha',$producto['fecha']);
            $update->bindValue(':imagen',$producto['imagen']);
            $update->execute();
        }catch (PDOException $e){
            $error=$e->getMessage();
        } finally {
            $update->closeCursor();
            $this->db->cierraConexion();
            return $error;
        }
    }
    public function editarImagenProducto($id,$imagen){
        $error=null;
        try{
            $this->db=new BaseDeDatos();
            $update=$this->db->prepara("UPDATE productos SET imagen=:imagen WHERE id=:id");
            $update->bindValue(':id',$id);
            $update->bindValue(':imagen',$imagen);
            $update->execute();
        }catch (PDOException $e){
            $error=$e->getMessage();
        } finally {
            $update->closeCursor();
            $this->db->cierraConexion();
            return $error;
        }
    }
    public function restarStock($id,$unidades){
        $error=null;
        try{
            $this->db=new BaseDeDatos();
            $update=$this->db->prepara("UPDATE productos SET stock=stock-:unidades WHERE id=:id");
            $update->bindValue(':id',$id);
            $update->bindValue(':unidades',$unidades);
            $update->execute();
        }catch (PDOException $e){
            $error=$e->getMessage();
        } finally {
            $update->closeCursor();
            $this->db->cierraConexion();
            return $error;
        }
    }
}