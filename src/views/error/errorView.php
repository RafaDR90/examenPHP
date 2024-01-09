<?php
use controllers\ErrorController;
?>
<div class="PaginaNoExiste">
    <?=ErrorController::show_error404()?>
    <a href="<?=BASE_URL?>">Volver a la página principal</a>
</div>


