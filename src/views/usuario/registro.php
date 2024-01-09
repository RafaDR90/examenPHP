<div class="registroContainer">
    <h3>Registrate</h3>
    <form action="<?=BASE_URL?>CreateAccount" method="post">
        <p>
        <label for="nombre">Nombre</label>
        <input id="nombre" type="text" name="data[nombre]" required>
        </p>
        <p>
        <label for="apellidos">Apellidos</label>
        <input id="apellidos" type="text" name="data[apellidos]" required>
        </p>
        <p>
        <label for="email">Email</label>
        <input id="email" type="text" name="data[email]" required>
        </p>
        <p>
        <label for="password">Contraseña</label>
        <input id="password" type="password" name="data[password]" required>
        </p>
        <p>
        <input type="submit" value="Registrarse">
        </p>
    </form>
</div>