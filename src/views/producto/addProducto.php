<div class="addProductoFormContainer">
    <h3>Añadir un nuevo Producto</h3>
    <form action="<?=BASE_URL?>add-producto" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="producto[nombre]" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="producto[descripcion]" required></textarea>
        </div>
        <div class="form-group inline">
            <div>
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="producto[precio]" step="0.01" required>
            </div>
            <div>
                <label for="stock">Stock:</label>
                <input type="number" id="stock" name="producto[stock]" required>
            </div>
        </div>
        <div class="form-group">
            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="producto[imagen]" accept="image/*" required>
        </div>
        <div class="form-group">
            <input type="submit" name="submit" value="Añadir Producto">
        </div>
    </form>
</div>