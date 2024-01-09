<?php
namespace models;
use utils\ValidationUtils;

class usuario{
    private string|null $id;
    private string $nombre;
    private string $apellidos;
    private string $email;
    private string $password;
    private string $rol;


    public function __construct(string|null $id, string $nombre, string $apellidos, string $email, string $password, string $rol)
    {
        $this->id=$id;
        $this->nombre=$nombre;
        $this->apellidos=$apellidos;
        $this->email=$email;
        $this->password=$password;
        $this->rol=$rol;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRol(): string
    {
        return $this->rol;
    }

    public function setRol(string $rol): void
    {
        $this->rol = $rol;
    }

    public static function fromArray(array $data):usuario{
        return new usuario(
            $data['id']?? null,
            $data['nombre']??'',
            $data['apellidos']??'',
            $data['email']??'',
            $data['password']??'',
            'user',
        );
    }

    public function saneaYValidaUsuario(){
        //saneamos los datos
            $this->setNombre(ValidationUtils::sanidarStringFiltro($this->getNombre()));
            $this->setApellidos(ValidationUtils::sanidarStringFiltro($this->getApellidos()));
            $this->setEmail(filter_var($this->getEmail(),FILTER_SANITIZE_EMAIL));
            $this->setPassword(ValidationUtils::sanidarStringFiltro($this->getPassword()));
        //Valida Nombre
        if ($this->getNombre()!=''){
            if (empty($this->getNombre())){
                return 'El nombre no puede estar vacio';
            }else if (!ValidationUtils::son_letras($this->getNombre())){
                return 'El nombre solo puede contener letras';
            }else if (!ValidationUtils::TextoNoEsMayorQue($this->getNombre(),30)){
                return 'El nombre no puede tener mas de 30 caracteres';
            }
        }
        //Valida Apellidos
        if ($this->getApellidos()!=''){
            if (empty($this->getApellidos())){
                return 'Los apellidos no pueden estar vacios';
            }else if (!ValidationUtils::son_letras($this->getApellidos())){
                return 'Los apellidos solo pueden contener letras';
            }else if (!ValidationUtils::TextoNoEsMayorQue($this->getApellidos(),50)){
                return 'Los apellidos no pueden tener mas de 50 caracteres';
            }
        }

        //Valida Email
        if (empty($this->getEmail())){
            return 'El email no puede estar vacio';
        }else if (!filter_var($this->getEmail(),FILTER_VALIDATE_EMAIL)){
            return 'El email no es valido';
        }
        //Valida Password
        if (empty($this->getPassword())){
            return 'La contraseña no puede estar vacia';
        }else if (!ValidationUtils::validarContrasena($this->getPassword())) {
            return 'La contraseña no es valida';
        }else if(!ValidationUtils::TextoNoEsMayorQue($this->getPassword(),70)){
            return 'La contraseña no puede tener mas de 70 caracteres';
        }
        return null;
    }

    public function comprobarPassword($pasword){
        if (password_verify($this->getPassword(),$pasword)){
            return true;
        }else{
            return false;
        }
    }

    public function validar(){}

    public function sanear(){}

}