<?php

use controllers\productoController;
?>
<div class="productosContainerPrincipal">
<?php
foreach ($categorias as $categoria):
    $productos=productoController::productosPorCategoria($categoria->getId());
    if (count($productos)>0):?>
    <h4><?=$categoria->getNombre()?></h4>
    <hr>
    <div class="gridProductos">
<?php $maxVueltas = min(4, count($productos));
        for ($i=0;$i<$maxVueltas;$i++):
            if ($productos[$i]->getStock()==0){
                continue;
            }
        $oferta=null;
        if($productos[$i]->getOferta()){
            $oferta='Oferta';
        }?>
    <div class="productoCard<?=$oferta?>">
        <?php if ($productos[$i]->getOferta()):?>
        <h3>¡Oferta!</h3>
        <?php endif;?>
        <p class="nombre__Producto"><?=$productos[$i]->getNombre()?></p>
        <p class="descripcion__Producto"<?=$productos[$i]->getDescripcion()?>></p>

        <div class="imgContainer">
            <img src="<?=BASE_URL?>public/img/productos/<?=$productos[$i]->getImagen()?>" alt="Imagen del producto">
        </div>
        <p class="precio__Producto"><?=$productos[$i]->getPrecio()?>€</p>
        <p class="stock__Producto"><?=$productos[$i]->getStock()?> unidades disponibles</p>
        <a class="botonAddCesta" href="<?=BASE_URL?>AddCesta/<?=$productos[$i]->getId()?>">Añadir a la cesta</a>
    </div>
<?php endfor;?>
    </div>
<?php endif;
endforeach;
if (isset($_SESSION['productosCarrito']))

?>
</div>