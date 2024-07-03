<?php include_once 'Views/header.php'; ?>

<div class="container">
    <h1>Trocar senha</h1>

    <?php 
    if (isset($aviso) && !empty($aviso)) {
    ?>
    <div class="alert alert-warning"><?=$aviso;?></div>
    <?php } ?>

    <form method="post">
        <div class="mb-3">
            <label for="PASSWORD" class="form-label">Senha Nova</label>
            <input type="password" class="form-control" id="PASSWORD" name="PASSWORD" required>
        </div>

        <div class="mb-3">
            <label for="PASSWORD_REPEAT" class="form-label">Repita a senha nova</label>
            <input type="password" class="form-control" id="PASSWORD_REPEAT" name="PASSWORD_REPEAT" required>
        </div>

        <button type="submit" class="btn btn-primary">Trocar Senha</button>
    </form>
</div>

<?php include_once 'Views/footer.php'; ?>