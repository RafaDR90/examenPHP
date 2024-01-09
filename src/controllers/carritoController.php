<?php
namespace controllers;
use lib\Pages;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use service\lineasPedidoService;
use service\pedidoService;
use service\productoService;
use utils\ValidationUtils;

class carritoController
{
    private Pages $pages;
    public function __construct()
    {
        $this->pages = new Pages();
    }

    public function addProducto($id): void
    {
        $id=ValidationUtils::SVNumero($id);
        if (!isset($id)){
            $this->pages->render("producto/muestraInicio",["error"=>"Ha habido un problema al añadir el producto a la cesta, si el problema persiste contacte con el Belén"]);
        }
        if (!session_status() == PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = array();
        }
        if (!isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id] = 1;
        } else {
            $_SESSION['carrito'][$id]++;
        }
        $this->pages->render("carrito/vistaCarrito",["exito"=>"Producto añadido a la cesta"]);
    }

    /**
     * Funcion estatica para obtener los productos del carrito
     * @return array con los productos del carrito
     */
    public static function obtenerProductosCarrito() : array
    {
        if (!session_status() == PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = array();
        }
        $productos = array();
        foreach ($_SESSION['carrito'] as $id => $unidades) {
            $productoService=new productoService();
            $producto = $productoService->getProductoByIdProducto($id);
            $producto['unidades'] = $unidades;
            $productos[] = $producto;
        }
        return $productos;
    }

    /**
     * Funcion para mostrar la vista del carrito
     * @return void
     */
    public function mostrarCarrito() : void
    {

        $this->pages->render('carrito/vistaCarrito');
    }

    /**
     * Funcion para restar un producto del carrito, si solo hay una unidad se elimina del carrito
     * @param $id int id del producto a restar
     * @return void redirige a la vista del carrito
     */
    public function restarProducto($id): void{
        $id=ValidationUtils::SVNumero($id);
        if (!isset($id)){
            $this->pages->render("producto/muestraInicio",["error"=>"Ha habido un problema al restar el producto a la cesta, si el problema persiste contacte con soporte tecnico"]);
        }
        if (!session_status() == PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['carrito'][$id])) {
            $this->pages->render("producto/muestraInicio",["error"=>"No se encuentra el producto en la cesta"]);
        }
        if ($_SESSION['carrito'][$id] == 1) {
            unset($_SESSION['carrito'][$id]);
        } else {
            $_SESSION['carrito'][$id]--;
        }
        $this->pages->render("carrito/vistaCarrito",["exito"=>"Producto eliminado a la cesta"]);
    }

    /**
     * Funcion para aumentar un producto del carrito
     * @param $id int id del producto a aumentar
     * @return void redirige a la vista del carrito
     */
    public function aumentarProducto($id): void{
        $id=ValidationUtils::SVNumero($id);
        if (!isset($id)){
            $this->pages->render("producto/muestraInicio",["error"=>"Ha habido un problema al aumentar el producto a la cesta, si el problema persiste contacte con soporte tecnico"]);
        }
        if (!session_status() == PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['carrito'][$id])) {
            $this->pages->render("producto/muestraInicio",["error"=>"No se encuentra el producto en la cesta"]);
        }
        $productoService=new productoService();
        $producto=$productoService->getProductoByIdProducto($id);
        if ($_SESSION['carrito'][$id] >= $producto['stock']) {
            $this->pages->render("carrito/vistaCarrito",["error"=>"No hay mas stock del producto"]);
            exit();
        }
        $_SESSION['carrito'][$id]++;
        $this->pages->render("carrito/vistaCarrito",["exito"=>"Producto añadido a la cesta"]);
    }

    /**
     * Funcion para eliminar un producto del carrito
     * @param $id int id del producto a eliminar
     * @return void redirige a la vista del carrito
     */
    public function eliminarProducto($id): void{
        $id=ValidationUtils::SVNumero($id);
        if (!isset($id)){
            $this->pages->render("producto/muestraInicio",["error"=>"Ha habido un problema al eliminar el producto a la cesta, si el problema persiste contacte con soporte tecnico"]);
        }
        if (!session_status() == PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['carrito'][$id])) {
            $this->pages->render("carrito/vistaCarrito",["error"=>"No se encuentra el producto en la cesta"]);
        }
        unset($_SESSION['carrito'][$id]);
        $this->pages->render("carrito/vistaCarrito",["exito"=>"Producto eliminado a la cesta"]);
    }

    /**
     * Funcion para vaciar el carrito
     * @return void redirige a la vista del carrito
     */
    public function vaciarCarrito(): void{
        if (!session_status() == PHP_SESSION_ACTIVE) {
            session_start();
        }
        unset($_SESSION['carrito']);
        $this->pages->render("carrito/vistaCarrito",["exito"=>"Carrito vaciado"]);
    }

    /**
     * Funcion para comprar los productos del carrito y restar el stock de los productos comprados
     * @return void redirige a la vista de compra realizada
     */
    public function comprar(): void{
        $pedidoService=new pedidoService();
        $lineasPedidoService=new lineasPedidoService();


        if (!session_status() == PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['carrito']) or empty($_SESSION['carrito'])){
            $this->pages->render("carrito/vistaCarrito",["error"=>"No hay productos en el carrito"]);
        }
        $productosCarrito=carritoController::obtenerProductosCarrito();
        $productoService=new productoService();
        //crear pedido
        $precioTotal=0;
        foreach ($productosCarrito as $producto){
            $precioTotal+=($producto["precio"] * $producto['unidades']);
        }
        $datos=array("idUsuario"=>$_SESSION['usuario']['id'],"fecha"=>date("Y-m-d H:i:s"),"coste"=>$precioTotal,"estado"=>"preparacion");
        //SEGUIR POR AQUI, EN TEORIA LO ANTERIOR DEBE DE FUNCIONAR, AHORA INSERTAR LOS PEDIDOS





        
        $pedido=$pedidoService->create($datos);
        // restar stock de los productos comprados
        foreach ($productosCarrito as $producto){
            $error=$productoService->restarStock($producto['id'],$producto['unidades']);
            if (isset($error)){
                $this->pages->render("carrito/vistaCarrito",["error"=>"Ha habido un problema al comprar los productos, si el problema persiste contacte con soporte tecnico"]);
            }
        }
        //envia email
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'rafapruebasdaw@gmail.com'; // Tu dirección de correo de Gmail
            $mail->Password = 'qvhl kmae gxgc vyik'; // La contraseña de aplicación de Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Remitentes y destinatarios
            $mail->setFrom('rafapruebasdaw@gmail.com', 'Nombre del remitente');
            $mail->addAddress('rafa18220delgado@gmail.com', 'Rafa'); // Añadir un destinatario

            // Contenido del correo

            $htmlContent = "<!DOCTYPE html>
<html>
<head>
    <style>
        .carritoContainer {
            font-family: Arial, sans-serif;
            color: #333333;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .carritoCard {
            background: #ffffff;
            border: 1px solid #dddddd;
            margin-bottom: 10px;
            padding: 10px;
        }
        .carritoCardImg img {
            max-width: 100px;
            max-height: 100px;
        }
        .cantidad, .precio {
            font-size: 14px;
            color: #555555;
        }
        .total {
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
        }
        .gracias {
            font-size: 18px;
            color: #333333;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class='carritoContainer'>
        <div class='gracias'>
            <p>Gracias por su compra. Recibirá el pedido lo antes posible.</p>
        </div>
        <h1>Resumen de su compra</h1>
        <div class='productosCarrito'>";

            $totalUnidades = 0;
            $totalPrecio = 0;
            foreach ($_SESSION['carrito'] as $id => $unidades) {
                $totalUnidades += $unidades;
            }

            foreach ($productosCarrito as $producto) {
                $precioTotalProducto = $producto['precio'] * $producto['unidades'];

                $htmlContent .= "
            <div class='carritoCard'>
                
                <h4>{$producto['nombre']}</h4>
                <p class='cantidad'>Cantidad: <span>{$producto['unidades']}</span></p>
                <p class='precio'>Precio: <span>$precioTotalProducto €</span></p>
            </div>";
                $totalPrecio += ($producto["precio"] * $producto['unidades']);
            }

            $htmlContent .= "
        </div>
        <div class='total'>
            <p>Total unidades: <span>$totalUnidades</span></p>
            <p>Total precio: <span>$totalPrecio €</span></p>
        </div>
    </div>
</body>
</html>";


            $mail->isHTML(true);
            $mail->Subject = 'Su compra se ha realizado correctamente';
            $mail->Body    = $htmlContent;

            $mail->send();
            //mensaje de exito o error al enviar el email
            $tipoMensaje="exito";
            $resultado="Mensaje enviado correctamente";
        } catch (Exception $e) {
            $tipoMensaje="error";
            $resultado="El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
        }
        unset($_SESSION['carrito']);
        $this->pages->render("carrito/compra-realizada",[ $tipoMensaje=>$resultado,'htmlContent'=>$htmlContent]);
    }
}
