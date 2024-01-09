<?php if(!isset($_SESSION['indentity'])): ?>
<div class="loginContainer">
<h3>Identificate</h3>
<form action="<?=BASE_URL?>Login" method="post">
    <p>
    <label for="email">Email</label>
    <input id="email" type="text" name="data[email]" required>
    </p>
    <p>
    <label for="password">Contraseña</label>
    <input id="password" type="password" name="data[password]" required>
    </p>
    <p>
    <input type="submit" value="Entrar">
    </p>
</form>
<?php else: ?>
<h3><?=$_SESSION['identity']->nombre?><?= $_SESSION['identity']->apellidos ?></h3>
    </div>
<?php endif;