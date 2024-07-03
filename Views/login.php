<?php include_once 'Views/header.php'; ?>
<div class="container">

    <h1>Entrar no sistema</h1>

    <?php
    if (isset($aviso) && !empty($aviso)) {
    ?>
    <div class="alert alert-warning"><?=$aviso;?></div>
    <?php } ?>

    <form method="post">
        <div class="mb-3">
            <label for="USERNAME" class="form-label">Usuário</label>
            <input type="text" class="form-control" id="USERNAME" name="USERNAME" aria-describedby="Usuário de acesso ao sistema" required>
        </div>

        <div class="mb-3">
            <label for="PASSWORD" class="form-label">Senha</label>
            <input type="password" class="form-control" id="PASSWORD" name="PASSWORD" aria-describedby="Senha de acesso ao sistema" required>
        </div>

        <button type="submit" class="btn btn-primary">entrar</button>
    </form>
</div>
