<?php
namespace controllers;
use lib\Pages;
use models\usuario;
use service\usuarioService;
use utils\utils;
use utils\ValidationUtils;

class usuarioController{
    private usuarioService $usuarioService;
    private Pages $pages;

    public function __construct()
    {
        $this->usuarioService=new usuarioService();
        $this->pages=new Pages();
    }

    /**
     * funcion que muestra la vista de login y si viene por post comprueba los datos y si son correctos inicia sesion
     * @return void renderiza la vista de login o la pagina de inicio si se ha iniciado sesion
     */
    public function login():void{
        if (isset($_SESSION['identity'])){
           header("Location: " . BASE_URL);
           exit();
        }
        if ($_SERVER['REQUEST_METHOD']=='POST'){
            if ($_POST['data']){
                $login=$_POST['data'];

                $usuario=usuario::fromArray($login);
                $error=$usuario->saneaYValidaUsuario();
                if($error){
                    $this->pages->render('producto/muestraInicio', ['error' => $error]);
                    exit();
                }
                $existeEmail=$this->usuarioService->compruebaCorreo($usuario->getEmail());
                if (is_string($existeEmail)){
                    $this->usuarioService->cierraConexion();
                    $this->pages->render('producto/muestraInicio', ['error' => 'Parece que ha habido algun problema con el correo, por favor contacte con el soporte tecnico']);
                    exit();
                }elseif (!$existeEmail){
                    $this->usuarioService->cierraConexion();
                    $this->pages->render('usuario/login', ['error' => 'El correo no existe']);
                    exit();
                }
                $usuarioFromEmail=$this->usuarioService->getUsuarioFromEmail($usuario->getEmail());
                if (!$usuarioFromEmail){
                    $this->pages->render('usuario/login', ['error' => 'Parece que ha habido algun problema con el correo, por favor contacte con el soporte tecnico']);
                    exit();
                }elseif(is_string($usuarioFromEmail)){
                    $this->pages->render('usuario/login', ['error' => $usuarioFromEmail]);
                    exit();
                }
                if($usuario->comprobarPassword($usuarioFromEmail['password'])){
                    $_SESSION['identity']=$usuarioFromEmail;
                    $nombre=ucfirst(strtolower($_SESSION['identity']['nombre']));
                    $this->pages->render('producto/muestraInicio', ['exito' => 'Bienvenido '.$nombre]);
                    exit();
                }else{
                    $this->pages->render('usuario/login', ['error' => 'Email o contraseña incorrectos']);
                    exit();
                }
            }
        }
            $this->pages->render('usuario/login');
    }

    /**
     * funcion que muestra la vista de registro y si viene por post comprueba los datos y si son correctos crea el usuario
     * @return void
     */
    public function registro():void{
        if (isset($_SESSION['identity'])){
            header("Location: " . BASE_URL);
            exit();
        }
        if($_SERVER['REQUEST_METHOD']=='POST'){
            if ($_POST['data']){
                $registrado=$_POST['data'];
                $usuario=usuario::fromArray($registrado);
                $error=$usuario->saneaYValidaUsuario();
                if($error){
                    $this->pages->render('usuario/registro', ['error' => $error]);
                    exit();
                }
                $usuario->setPassword(password_hash($usuario->getPassword(),PASSWORD_BCRYPT,['cost'=>4]));
                $correoUsado=$this->usuarioService->compruebaCorreo($usuario->getEmail());
                if (is_string($correoUsado)){
                    $error='Parece que ha habido algun problema con el correo, por favor contacte con el soporte tecnico';
                    $this->pages->render('usuario/registro', ['error' => $error]);
                    exit();
                }
                if ($correoUsado){
                    $error='El correo ya esta en uso';
                    $this->pages->render('usuario/registro', ['error' => $error]);
                    exit();
                }
                $save=$this->usuarioService->createUser($usuario);
                if (!$save) {
                    $error = 'No se ha podido registrar el usuario';
                }else{
                    $exito='Usuario registrado correctamente';
                }
            }else{
                $error='No se ha podido registrar el usuario';
            }
            $this->pages->render('producto/muestraInicio', ['error' => isset($error) ? $error : null, 'exito' => isset($exito) ? $exito : null]);
            exit();
        }
        $this->pages->render('usuario/registro');
    }

    /**
     * cierra la sesion del usuario
     * @return void redirige a la pagina de inicio
     */
    public function logout():void{
        if (!isset($_SESSION['identity'])){
            header("Location: " . BASE_URL);
            exit();
        }
        utils::deleteSession('identity');
        utils::deleteSession('editandoProducto');
        header("Location: " . BASE_URL);
    }

    /**
     * muestra la vista de gestion de usuarios y si se le pasa un rol muestra los usuarios de ese rol
     * @param $rol string el rol de los usuarios a mostrar
     * @return void renderiza la vista de gestion de usuarios
     */
    public function muestraGestionUsuarios($rol=null):void{
        if (!isset($_SESSION['identity'])or $_SESSION['identity']['rol']!='admin'){
            $this->pages->render('producto/muestraInicio', ['error' => 'No tienes permisos para acceder a esta pagina']);
            exit();
        }
        if(!isset($rol) or $rol=='all'){
            $this->pages->render('usuario/gestionUsuarios');
            exit();
        }else{
            $rol=ValidationUtils::sanidarStringFiltro($rol);
            if ($rol=='admin' or $rol=='user'){
                $usuarios=$this->usuarioService->getUsuariosPorRol($rol);
            }else{
                $this->pages->render('producto/muestraInicio', ['error' => 'Ha ocurrido un error inesperado']);
            }
        }
        if (is_string($usuarios)){
            $this->pages->render('usuario/gestionUsuarios', ['error' => $usuarios]);
            exit();
        }
        $this->pages->render('usuario/gestionUsuarios', ['usuarios' => $usuarios]);
    }
    public static function obtenerUsuarios(){
        $usuarioService=new usuarioService();
        return $usuarioService->getUsuarios();

    }

    /**
     * funcion que cambia el rol de un usuario, no te deja cambiarte el rol a ti mismo
     * @param $id int el id del usuario a cambiar el rol
     * @param $rol string el rol al que se va a cambiar
     * @param $nombre string el nombre del usuario para mostrarlo en el mensaje de exito
     * @return void renderiza la vista de gestion de usuarios con un mensaje de exito o error
     */
    public function cambiarRol($id, $rol, $nombre):void{
        //comprueba que el usuario sea admin
        if (!isset($_SESSION['identity'])or $_SESSION['identity']['rol']!='admin'){
            $this->pages->render('producto/muestraInicio', ['error' => 'No tienes permisos para acceder a esta pagina']);
            exit();
        }
        //valida id
        $id=ValidationUtils::SVNumero($id);
        if (!isset($id)){
            $this->pages->render('producto/muestraInicio', ['error' => 'Ha ocurrido un error inesperado']);
            exit();
        }
        //comprueba que el usuario no se cambie el rol a si mismo
        if ($id==$_SESSION['identity']['id']){
            $this->pages->render('usuario/gestionUsuarios', ['error' => 'No puedes cambiarte el rol a ti mismo']);
            exit();
        }
        //sanea rol y nombre
        $rol=ValidationUtils::sanidarStringFiltro($rol);
        $nombre=ValidationUtils::sanidarStringFiltro($nombre);
        //comprueba que el rol sea valido
        if ($rol=='admin' or $rol=='user'){
            $usuario=$this->usuarioService->getUsuarioPorId($id);
            if (is_string($usuario)){
                $this->pages->render('usuario/gestionUsuarios', ['error' => $usuario]);
                exit();
            }
            //comprueba que el usuario no tenga ya ese rol
            if ($usuario['rol']==$rol){
                $this->pages->render('usuario/gestionUsuarios', ['error' => 'El usuario ya tiene ese rol']);
                exit();
            }
            //cambia el rol en la base de datos
            $update=$this->usuarioService->updateRol($id,$rol);
            //si hay algun error lo muestra
            if (is_string($update)){
                $this->pages->render('usuario/gestionUsuarios', ['error' => $update]);
                exit();
            }
            //si no hay errores muestra un mensaje de exito
            $this->pages->render('usuario/gestionUsuarios', ['exito' => 'Rol cambiado correctamente a '.$nombre]);
            exit();
        //en caso de error en la validacion muestra un mensaje de error
        }else{
            $this->pages->render('producto/muestraInicio', ['error' => 'Ha ocurrido un error inesperado']);
            exit();
        }
    }


}