<?php
use controllers\usuarioController;
?>
<div class="gestionUsuarios">
    <h3>Gestion de usuarios</h3>
    <div class="tipoUsuariosContainer">
        <form action="<?=BASE_URL?>rolUsuarios" method="post">
            <label for="tipoUsuario">Filtrar por rol</label>
            <select name="tipoUsuario" id="tipoUsuario">
                <option value="all">Todos</option>
                <option value="admin">Administradores</option>
                <option value="user">Usuarios</option>
            </select>
            <input type="submit" value="Filtrar">
        </form>
    </div>
    <?php if (!isset($usuarios)){
        $usuarios=usuarioController::obtenerUsuarios();
    }?>
    <table class="usuariosTable">
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Email</th>
            <th>Rol</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= $usuario['nombre'] ?></td>
                <td><?= $usuario['apellidos'] ?></td>
                <td><?= $usuario['email'] ?></td>
                <td>
                    <form action="<?=BASE_URL?>cambiarRol" method="post">
                        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                        <input type="hidden" name="nombre" value="<?=$usuario['nombre']?>">
                        <select name="rol" id="rol">
                            <option value="admin" <?= $usuario['rol'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
                            <option value="user" <?= $usuario['rol'] == 'user' ? 'selected' : '' ?>>Usuario</option>
                        </select>
                        <input type="submit" value="Cambiar rol">
                    </form>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
