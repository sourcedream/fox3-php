<?php include_once 'Views/header.php'; ?>

<div class="container">

    <?php
        $success = flashMessage('success');
        if (isset($success) && !empty($success)) {
    ?>
    <div class="alert alert-success" role="alert"><?=$success?></div>
    <?php } ?>

    <a href="/novo-mdfe" class="btn btn-info">Novo MDFE</a>
    <div style="float: right">
    <a href="/trocar-senha" class="btn btn-info">Trocar Senha</a>
    <a href="/sair" class="btn btn-warning">Sair do Sistema</a>
    </div>

    <table class="table" style="margin-top: 15px">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Filial</th>
                <th scope="col">Chave</th>
                <th scope="col">Protocolo</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($dados as $dado) { ?>
            <tr>
                <th scope="row"><?=$dado['id'];?></th>
                <td><?=$dado['nome_filial'];?></td>
                <td><?=$dado['chave'];?></td>
                <td><?=$dado['protocolo'];?></td>
                <td><?=$dado['status'];?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>


<?php include_once 'Views/footer.php'; ?>
