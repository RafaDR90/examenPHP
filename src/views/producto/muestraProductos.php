<div class="productosContainer">
<?php
    foreach ($vProductos as $producto):
        // Compruebo si hay stock para mostrar el producto
        if ($producto->getStock()==0){
            continue;
        }
        $oferta=null;
        if($producto->getOferta()=='si'){
            $oferta='Oferta';
        }?>
    <div class="productoCard<?=$oferta?>">
        <?php if (isset($oferta)):?>
        <h3>¡Oferta!</h3>
        <?php endif;?>
        <p class="nombre__Producto"><?=$producto->getNombre()?></p>
        <p class="descripcion__Producto"><?=$producto->getDescripcion()?></p>
        <div class="imgContainer">
            <img src="<?=BASE_URL?>public/img/productos/<?=$producto->getImagen()?>" alt="Imagen del producto">
        </div>
        <p class="precio__Producto"><?=$producto->getPrecio()?>€</p>
        <p class="stock__Producto"><?=$producto->getStock()?> unidades disponibles</p>
        <a class="botonAddCesta" href="<?=BASE_URL?>AddCesta/<?=$producto->getId()?>">Añadir a la cesta</a>
    </div>
<?php endforeach;
if (isset($_SESSION['productosCarrito']))

?>
</div>
