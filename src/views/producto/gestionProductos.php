<div class="gestionaProductosContainer">
    <div class="formSeleccionCategoria">
        <label for="categoria">Selecciona una categoria</label>
        <div class="select-submit-container">
        <form action="<?=BASE_URL?>gestion-productos" method="POST">
            <select name="categoriaId" id="categoria">
                <?php if(!isset($_SESSION['editandoProducto'])):?>
                <option value="NA" selected>Todas las categorias</option>
                <?php endif?>
<?php
foreach ($categorias as $categoria):
    if ($categoria->getId()==$_SESSION['editandoProducto']):?>
                <option value="<?= $categoria->getId() ?>" selected><?= $categoria->getNombre() ?></option>
<?php else:?>
                <option value="<?= $categoria->getId() ?>"><?= $categoria->getNombre() ?></option>
<?php endif;
endforeach;?>
            </select>
            <input type="submit" value="Ver productos">
        </form>
        </div>
    </div>
<?php
if (isset($gProductos)):?>
    <div class="modificaProductosContainer">
        <div class="addProductoContainer"><a href="<?=BASE_URL?>add-producto">Nuevo producto</a></div>
        <?php foreach ($gProductos as $producto):?>
        <div class="cardModificaProducto">
            <div class="columnaDatos"><span class="modifyProductId"><small>Producto ID:</small> <?=$producto->getId()?>&nbsp;&nbsp;</span><div class="imgModifyProduct__container"><img src="<?=BASE_URL?>public/img/productos/<?=$producto->getImagen()?>"></div><p class="modifyProductNombre"><?=$producto->getNombre()?></p></div><div class="columnaButtons"><a href="<?=BASE_URL?>editar-producto/<?=$producto->getId()?>">Editar</a><a href="<?=BASE_URL?>eliminar-producto/<?=$producto->getId()?>">Eliminar</a> </div>
        </div>
        <?php endforeach;?>
    </div>

<?php endif;?>
</div>
