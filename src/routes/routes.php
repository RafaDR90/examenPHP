<?php
namespace routes;
use controllers\usuarioController;
use controllers\categoriaController;
use controllers\productoController;
use controllers\carritoController;
use controllers\ErrorController;
use lib\Router;
class routes{
    const PATH="/Examen";

    public static function getRoutes(){
    $router=new Router();

    // CREO CONTROLADORES
    $productoController=new productoController();
    $usuarioController=new usuarioController();
    $categoriaController=new categoriaController();
    $carritoController=new carritoController();
    $errorController=new ErrorController();
    // PAGINA PRINCIPAL
        $router->get(self::PATH, function () use ($productoController){
            $productoController->showIndex();
        });
    // CREAR CUENTA
        $router->get(self::PATH.'/CreateAccount', function () use ($usuarioController){
                $usuarioController->registro();
            });
        $router->post(self::PATH.'/CreateAccount', function () use ($usuarioController){
                $usuarioController->registro();
            });
    // LOGIN
        $router->get(self::PATH.'/Login', function () use ($usuarioController){
                $usuarioController->login();
            });
        $router->post(self::PATH.'/Login', function () use ($usuarioController){
                $usuarioController->login();
            });
        $router->get(self::PATH.'/CierraSesion', function () use ($usuarioController){
                $usuarioController->logout();
            });

    //                                                    CATEGORIAS
    // GESTIONAR CATEGORIAS
        $router->get(self::PATH.'/gestionarCategorias', function () use ($categoriaController){
                $categoriaController->gestionarCategorias();
            });
        $router->get(self::PATH.'/gestionarCategorias/$page', function ($page) use ($categoriaController){
                $categoriaController->gestionarCategorias($page);
            });

    // EDITAR CATEGORIA
        $router->get(self::PATH.'/editarCategoria/$id', function ($id) use ($categoriaController){
                $categoriaController->editarCategoria($id);
            });
        $router->post(self::PATH.'/editarCategoria/$id', function ($id) use ($categoriaController){
                $categoriaController->editarCategoria($id);
            });
        $router->get(self::PATH.'/eliminarCategoria/$id', function ($id) use ($categoriaController){
                $categoriaController->eliminarCategoriaPorId($id);
            });

        $router->post(self::PATH.'/NuevaCategoria', function () use ($categoriaController){
                $categoriaController->crearCategoria();
            });
        $router->get(self::PATH.'/obtenerProductosPorId/$id', function ($id) use ($productoController){
                $productoController->obtenerProductosPorId($id);
            });

    //                                                      PRODUCTOS

    // GESTIONAR PRODUCTOS
        $router->get(self::PATH.'/gestion-productos',function () use ($productoController){
                $productoController->muestraGestionProductos();
            });
        $router->post(self::PATH.'/gestion-productos',function () use ($productoController){
                $productoController->muestraGestionProductos();
            });
    // AÑADIR PRODUCTO
        $router->get(self::PATH.'/add-producto', function () use ($productoController){
                $productoController->addProducto();
            });
        $router->post(self::PATH.'/add-producto', function () use ($productoController){
                $productoController->addProducto();
            });
    // ELIMINAR PRODUCTO
        $router->get(self::PATH.'/eliminar-producto/$id', function ($id) use ($productoController){
                $productoController->eliminarProducto($id);
                });
    // EDITAR PRODUCTO
        $router->get(self::PATH.'/editar-producto/$id', function ($id) use ($productoController){
                $productoController->editarProducto($id);
            });
        $router->post(self::PATH.'/editar-producto', function () use ($productoController){
                $productoController->confirmaEdicion($_POST['id'],$_POST["edit"]);
            });

    // VER PRODUCTOS
        $router->get(self::PATH.'/productos/$id', function ($id) use ($productoController){
                $productoController->obtenerProductosPorId($id);
            });

    // AÑADIR PRODUCTO A LA CESTA
        $router->get(self::PATH.'/AddCesta/$id', function ($id) use ($carritoController){
                $carritoController->addProducto($id);
            });
    // VER CARRITO
        $router->get(self::PATH.'/mostrarCarrito', function () use ($carritoController){
                $carritoController->mostrarCarrito();
            });

    // RESTAR PRODUCTO
        $router->get(self::PATH.'/restar-producto/$id', function ($id) use ($carritoController){
                $carritoController->restarProducto($id);
            });
    // AUMENTAR PRODUCTO
        $router->get(self::PATH.'/aumentar-producto/$id', function ($id) use ($carritoController){
                $carritoController->aumentarProducto($id);
            });
    // ELIMINAR PRODUCTO
        $router->get(self::PATH.'/eliminarProducto/$id', function ($id) use ($carritoController){
                $carritoController->eliminarProducto($id);
            });
    // VACIAR CARRITO
        $router->get(self::PATH.'/vaciar-carrito', function () use ($carritoController){
                $carritoController->vaciarCarrito();
            });
    // COMPRAR
        $router->get(self::PATH.'/comprar', function () use ($carritoController){
                $carritoController->comprar();
            });
    //                                               USUARIOS
    // GESTIONAR USUARIOS
        $router->get(self::PATH.'/gestion-usuarios', function () use ($usuarioController){
                $usuarioController->muestraGestionUsuarios();
            });
        $router->post(self::PATH.'/rolUsuarios', function () use ($usuarioController){
                $usuarioController->muestraGestionUsuarios($_POST['tipoUsuario']);
            });
    // CAMBIAR ROL
        $router->post(self::PATH.'/cambiarRol', function () use ($usuarioController){
                $usuarioController->cambiarRol($_POST['id'],$_POST['rol'],$_POST['nombre']);
            });


    // LA PAGINA NO SE ENCUENTRA
        $router->any('/404', function (){
            header('Location: ' . self::PATH . '/error');
            });
        $router->get(self::PATH.'/error', function (){
                ErrorController::showErrorView();
            });

        $router->resolve();
        }
}

?>