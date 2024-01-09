<?php
namespace controllers;
use lib\Pages;
use models\categoria;
use service\categoriaService;
use utils\ValidationUtils;

class categoriaController{
    private categoriaService $categoriaService;
    private Pages $pages;
    public function __construct()
    {
        $this->categoriaService=new categoriaService();
        $this->pages=new Pages();
    }

    /**
     * funcion estatica que obtiene todas las categorias
     * @return array|null devuelve un array de categorias o null si no hay categorias
     */
    public static function obtenerCategorias(): ?array{
        $categoriaService=new categoriaService();
        $categorias=$categoriaService->getAll();
        return categoria::fromArray($categorias);

    }


    /**
     * funcion que muestra la vista de gestion de categorias
     * si no hay sesion iniciada o el usuario no es admin se redirige a la pagina de inicio
     * @return void renderiza la vista de gestion de categorias
     */
    public function gestionarCategorias(){
        if (!isset($_SESSION['identity'])|| $_SESSION['identity']['rol']!='admin'){
            $this->pages->render('producto/muestraInicio',['error'=>'No tienes permisos para acceder a esta página']);
            exit();
        }
        $this->pages->render('categoria/mostrarGestionCategorias',['categorias'=>categoriaController::obtenerCategorias()]);
    }

    /**
     * funcion que elimina una categoria por su id
     * @param $id int el id de la categoria a eliminar
     * @return void renderiza la vista de gestion de categorias
     */
    public function eliminarCategoriaPorId($id){
        if (!isset($_SESSION['identity'])|| $_SESSION['identity']['rol']!='admin'){
            $this->pages->render('producto/muestraInicio',['error'=>'No tienes permisos para acceder a esta página']);
            exit();
        }
        if(!isset($id)){
            $this->pages->render('categoria/mostrarGestionCategorias',['error'=>'Ha que ha ocurrido un error inesperado']);
            exit();
        }
        $idCategoria=ValidationUtils::SVNumero($id);
        $error=$this->categoriaService->borrarCategoriaPorId($idCategoria);
        if ($error){
            $this->pages->render('categoria/mostrarGestionCategorias',['error'=>$error]);
            exit();
        }
        $this->pages->render('categoria/mostrarGestionCategorias',['exito'=>'Categoria eliminada correctamente']);
    }

    /**
     * funcion que muestra la vista de editar categoria
     * @param $id
     * @return void
     */
    public function editarCategoria($id):void{
        if (!isset($_SESSION['identity'])|| $_SESSION['identity']['rol']!='admin'){
            $this->pages->render('producto/muestraInicio',['error'=>'No tienes permisos para acceder a esta página']);
            exit();
        }
        //si no se ha enviado el formulario de editar categoria renderiza la vista de editar dicha categoria
        if(!isset($_POST['nuevoNombre'])){
            if (isset($id)){
                $id=ValidationUtils::SVNumero($id);
                if (isset($id)){
                    $resultado=$this->categoriaService->obtenerCategoriaPorID($id);
                    if(is_string($resultado)){
                        $this->pages->render('categoria/mostrarGestionCategorias',['error'=>$resultado]);
                        exit();
                    }
                    $resultado=categoria::fromArray([$resultado]);
                    $this->pages->render('categoria/editarCategoria',['categoriaEdit'=>$resultado]);
                    exit();
                }
            }
            $this->pages->render('categoria/mostrarGestionCategorias',['error'=>'Ha que ha ocurrido un error inesperado']);
        }else{
            //si se ha enviado el formulario de editar categoria se edita la categoria
            $id=ValidationUtils::SVNumero($id);
            if (!isset($id)){
                $this->pages->render('categoria/mostrarGestionCategorias',['error'=>'Ha que ha ocurrido un error inesperado']);
                exit();
            }
            //sanea y valida el nombre de la categoria
            $nuevoNombre=ValidationUtils::sanidarStringFiltro($_POST['nuevoNombre']);
            if (!ValidationUtils::noEstaVacio($nuevoNombre)){
                $error='El nombre no puede estar vacío';
            }elseif (!ValidationUtils::son_letras($nuevoNombre)){
                $error='El nombre solo puede contener letras';
            }elseif(!ValidationUtils::TextoNoEsMayorQue($nuevoNombre,50)){
                $error='El nombre no puede tener más de 50 caracteres';
            }
            //si hay algun error al validar el nombre de la categoria se renderiza la vista de editar categoria
            if (isset($error)){
                $this->pages->render('categoria/mostrarGestionCategorias',['error'=>$error]);
                exit();
            }
            $categoriaEditada=new categoria($id,$nuevoNombre);
            //se edita la categoria
            $error=$this->categoriaService->update($categoriaEditada);
            if($error){
                $this->pages->render('categoria/mostrarGestionCategorias',['errores'=>$error]);
            }else{
                    $this->pages->render('categoria/mostrarGestionCategorias',['exito'=>'Categoria editada correctamente']);
            }
        }
    }

    /**
     * funcion que muestra la vista de crear categoria, si se ha enviado el formulario de crear categoria se crea la categoria
     * @return void
     */
    public function crearCategoria():void{
        if (!isset($_SESSION['identity'])|| $_SESSION['identity']['rol']!='admin') {
            $this->pages->render('producto/muestraInicio', ['error' => 'No tienes permisos para acceder a esta página']);
            exit();
        }
        if ($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['nuevaCategoria'])){
            $nuevaCategoria=ValidationUtils::sanidarStringFiltro($_POST['nuevaCategoria']);
            if (!ValidationUtils::noEstaVacio($nuevaCategoria)){
                $error='El nombre no puede estar vacío';
            }elseif (!ValidationUtils::son_letras($nuevaCategoria)){
                $error='El nombre solo puede contener letras';
            }elseif(!ValidationUtils::TextoNoEsMayorQue($nuevaCategoria,50)){
                $error='El nombre no puede tener más de 50 caracteres';
            }
            if (isset($error)){
                $this->pages->render('categoria/mostrarGestionCategorias',['error'=>$error]);
                exit();
            }
            $categoria=new categoria(null,$nuevaCategoria);
            $error=$this->categoriaService->create($categoria);
            if($error){
                $this->pages->render('categoria/mostrarGestionCategorias',['error'=>"error"]);
            }else{
                $this->pages->render('categoria/mostrarGestionCategorias',['exito'=>'Categoria creada correctamente']);
            }
        }
    }
}
