<?php
use controllers\carritoController;
?>
<div class="carritoContainer">
    <?php
    if (!session_status() == PHP_SESSION_ACTIVE){
        session_start();
    }
    if (!isset($_SESSION['carrito']) or empty($_SESSION['carrito'])):
        ?>
        <div class="carritoVacio">
            <h2>Carrito vacio</h2>
            <a href="<?=BASE_URL?>">Volver a la tienda</a>
        </div>
    <?php
     else:
        $totalUnidades=0;
        $totalPrecio=0;
        foreach ($_SESSION['carrito'] as $id=>$unidades){
            $totalUnidades+=$unidades;
        }

        ?>
        <div class="productosCarrito">
    <?php $productosCarrito=carritoController::obtenerProductosCarrito();
        foreach ($productosCarrito as $producto) :?>
            <div class="carritoCard">
                <div class="carritoCardImg">
                    <img src="<?=BASE_URL?>public/img/productos/<?=$producto['imagen']?>" alt="Imagen producto">
                </div>
                <h4><?=$producto['nombre']?></h4>
                <p class="cantidad">Cantidad: <a href="<?=BASE_URL?>restar-producto/<?=$producto['id']?>">-</a><span><?=$producto['unidades']?></span><a href="<?=BASE_URL?>aumentar-producto/<?=$producto['id']?>">+</a></p>
                <p class="precio">Precio: <span><?=$producto['precio']*$producto['unidades']?>€</span></p>
                <a class="eliminarProductoCarrito" href="<?=BASE_URL?>eliminarProducto/<?=$producto['id']?>">Eliminar</a>
                <?php $totalPrecio+=($producto["precio"]*$producto['unidades']) ?>
            </div>
        <?php endforeach;?>
        </div>
        <div class="opcionesCarrito">
            <a href="<?=BASE_URL?>vaciar-carrito">Vaciar Carrito</a>
            <p>Total unidades: <span><?=$totalUnidades?></span></p>
            <p>Total precio: <span><?=$totalPrecio?>€</span></p>
            <a href="<?=BASE_URL?>comprar">Comprar</a>
        </div>
    <?php
    endif ?>
</div>