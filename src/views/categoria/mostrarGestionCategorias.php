<?php
use JasonGrimes\Paginator;
use controllers\categoriaController;
$categorias= categoriaController::obtenerCategorias();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Assuming $entradas is your array of entries
$totalItems = count($categorias);
$itemsPerPage = 6; // Set the number of items per page
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$urlPattern = BASE_URL.'gestionarCategorias/?page=(:num)';

$paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);

// Get the subset of entries for the current page
$start = ($currentPage - 1) * $itemsPerPage;
$end = $start + $itemsPerPage;
$currentPageEntries = array_slice($categorias, $start, $itemsPerPage);

$classActive='';
?>
<div class="gestionCategoriasContainer">
<h3>Gestion de categorias</h3>
<ul>
    <?php if (isset($_SESSION['identity'])&& $_SESSION['identity']['rol']=='admin'): ?>
    <li class="form-li">
        <form action="<?=BASE_URL?>NuevaCategoria" method="post">
            <label for="nuevaCategoria">Nueva categoria</label>
            <input type="text" name="nuevaCategoria" id="nuevaCategoria">
            <input type="submit" value="A&ntilde;adir">
        </form>
    </li>
    <?php
    foreach ($currentPageEntries as $categoria):?>
    <li><span><?= $categoria->getNombre() ?></span><span><a href="<?=BASE_URL?>editarCategoria/<?= $categoria->getId() ?>">Editar</a><a class="rojo" href="<?= BASE_URL ?>eliminarCategoria/<?= $categoria->getId() ?>">Eliminar</a></span></li>
    <?php endforeach;
    endif;?>

</ul>
    <div class="paginationLinksContainer">
        <?php if ($paginator->getNumPages() > 1): ?>
            <ul class="pagination">
                <?php if ($paginator->getPrevUrl()): ?>
                    <li class="pagination__previous--li"><a href="<?php echo $paginator->getPrevUrl(); ?>">&laquo; Previous</a></li>
                <?php endif; ?>
                <?php foreach ($paginator->getPages() as $page): ?>
                    <?php if ($page['url']): ?>
                        <li class="pagination__li<?php echo $page['isCurrent'] ? 'active' : ''; ?>">
                            <a href="<?php echo $page['url']; ?>"><?php echo $page['num']; ?></a>
                        </li>
                    <?php else: ?>
                        <li class="disabled"><span><?php echo $page['num']; ?></span></li>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if ($paginator->getNextUrl()):; ?>
                    <li><a class="pagination__next--li" href="<?php echo $paginator->getNextUrl(); ?>">Next &raquo;</a></li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
